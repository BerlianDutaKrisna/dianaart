<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RegistrationModel;
use App\Models\ClassSessionModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Registration extends BaseController
{
    protected $registrationModel;
    protected $classSessionModel;

    public function __construct()
    {
        $this->registrationModel = new RegistrationModel();
        $this->classSessionModel = new ClassSessionModel();
        helper(['url', 'form']);
    }

    // GET /register[/(:num)]
    public function create($sessionId = null)
    {
        // Guard sederhana: kalau belum login, arahkan ke /login
        $userId = session('user_id') ?? null;
        if (empty($userId)) {
            return redirect()->to(base_url('login'));
        }

        $data = [
            'title'           => 'Register',
            'user_id'         => $userId,
            'selectedSession' => null,
            'sessions'        => [],
            'unit_price'      => 0,
            'price_fmt'       => '',
            'quantity'        => 1,
            'errors'          => session('errors') ?? [],
        ];

        if (!empty($sessionId)) {
            // preload sesi tertentu
            $row = $this->classSessionModel
                ->select('
                    class_sessions.*,
                    classes.id    AS class_id,
                    classes.title AS class_title,
                    classes.price AS class_price
                ')
                ->join('classes', 'classes.id = class_sessions.class_id', 'inner')
                ->where('class_sessions.id', (int) $sessionId)
                ->first();

            if (!$row) throw PageNotFoundException::forPageNotFound('Session not found');

            $unitPrice = (float) ($row['class_price'] ?? 0);

            $data['selectedSession'] = $row;
            $data['unit_price']      = $unitPrice;
            $data['price_fmt']       = 'Rp ' . number_format($unitPrice, 0, ',', '.');
        } else {
            // daftar sesi upcoming utk dipilih
            $today = date('Y-m-d');
            $now   = date('H:i:s');

            $data['sessions'] = $this->classSessionModel
                ->select('
                    class_sessions.*,
                    classes.title AS class_title,
                    classes.price AS class_price
                ')
                ->join('classes', 'classes.id = class_sessions.class_id', 'inner')
                ->where('classes.is_active', 1)
                ->groupStart()
                ->where('class_sessions.schedule_date >', $today)
                ->orGroupStart()
                ->where('class_sessions.schedule_date', $today)
                ->where('class_sessions.start_time >=', $now)
                ->groupEnd()
                ->groupEnd()
                ->orderBy('class_sessions.schedule_date', 'ASC')
                ->orderBy('class_sessions.start_time', 'ASC')
                ->findAll();
        }

        return view('registrations/create', $data);
    }

    // POST /register
    public function store()
    {
        // Guard sederhana: kalau belum login, arahkan ke /login
        $userId = session('user_id') ?? null;
        if (empty($userId)) {
            return redirect()->to(base_url('login'));
        }

        $sessionId  = (int) ($this->request->getPost('session_id') ?? 0);
        $quantity   = (int) ($this->request->getPost('quantity') ?? 1);
        $discountId = $this->request->getPost('discount_id') ? (int)$this->request->getPost('discount_id') : null;

        $errors = [];
        if ($sessionId <= 0) $errors['session_id'] = 'Session harus dipilih.';
        if ($quantity <= 0)  $errors['quantity']   = 'Quantity minimal 1.';

        // Ambil data session + harga kelas
        $row = $this->classSessionModel
            ->select('class_sessions.*, classes.price AS class_price')
            ->join('classes', 'classes.id = class_sessions.class_id', 'inner')
            ->where('class_sessions.id', $sessionId)
            ->first();

        if (!$row) {
            $errors['session_id'] = 'Session tidak ditemukan.';
        }

        if (!empty($errors)) {
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        // Hitung harga
        $unitPrice  = (float) ($row['class_price'] ?? 0);
        $subtotal   = $unitPrice * $quantity;
        $finalTotal = $subtotal; // jika nanti ada diskon, bisa dihitung di sini

        // === KAPASITAS: cek & kurangi secara atomik di dalam transaksi ===
        $db = \Config\Database::connect();
        $db->transBegin(); // mulai transaksi

        try {
            // Opsi A (satu langkah atomik, tanpa lock manual):
            // Kurangi capacity jika dan hanya jika capacity masih >= quantity
            // Perhatikan: false di parameter ke-3 set() agar ekspresi tidak di-escape.
            $affected = $db->table('class_sessions')
                ->set('capacity', 'capacity - ' . (int)$quantity, false)
                ->where('id', $sessionId)
                ->where('capacity >=', (int)$quantity)
                ->update();

            // $affected adalah boolean, tapi kita perlu pastikan barisnya benar-benar ter-update.
            if (!$affected || $db->affectedRows() !== 1) {
                // Gagal mengurangi kapasitas: berarti sisa kapasitas tidak mencukupi.
                $db->transRollback();
                return redirect()->back()->withInput()->with('errors', [
                    'capacity' => 'Kelas sudah penuh atau sisa kursi tidak mencukupi.'
                ]);
            }

            // Jika sampai di sini, kapasitas sudah berhasil dikurangi.
            // Lanjut insert registrations.
            $regId = $this->registrationModel->insert([
                'user_id'       => $userId,
                'session_id'    => $sessionId,
                'discount_id'   => $discountId,
                'quantity'      => $quantity,
                'unit_price'    => $unitPrice,
                'subtotal'      => $subtotal,
                'final_total'   => $finalTotal,
                'status'        => 'Registered',
                'registered_at' => date('Y-m-d H:i:s'),
            ], true);

            if (!$regId) {
                // Insert gagal -> rollback agar kapasitas kembali seperti semula
                $db->transRollback();
                return redirect()->back()->withInput()->with('errors', [
                    'general' => 'Gagal menyimpan registrasi.'
                ]);
            }

            // Semua ok -> commit
            $db->transCommit();

            return redirect()->to(base_url('register/success/' . $regId))
                ->with('success', 'Registrasi berhasil dibuat.');
        } catch (\Throwable $e) {
            // Ada error tak terduga: rollback
            if ($db->transStatus() !== false) {
                $db->transRollback();
            }
            return redirect()->back()->withInput()->with('errors', [
                'general' => 'Gagal menyimpan: ' . $e->getMessage()
            ]);
        }
    }

    public function success($id)
    {
        // Guard sederhana: wajib login
        $userId = session('user_id') ?? null;
        if (empty($userId)) {
            return redirect()->to(base_url('login'));
        }

        // Ambil data registrasi + join sesi & kelas
        $reg = $this->registrationModel
            ->select('
            registrations.*,
            class_sessions.name          AS session_name,
            class_sessions.schedule_date AS session_date,
            class_sessions.start_time    AS session_start,
            class_sessions.end_time      AS session_end,
            class_sessions.location      AS session_location,
            classes.title                AS class_title,
            classes.price                AS class_price
        ')
            ->join('class_sessions', 'class_sessions.id = registrations.session_id', 'inner')
            ->join('classes', 'classes.id = class_sessions.class_id', 'inner')
            ->where('registrations.id', (int)$id)
            ->where('registrations.user_id', (int)$userId) // hanya milik user ini
            ->first();

        if (!$reg) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Registration not found');
        }

        // Formatting angka
        $reg['unit_price_fmt']  = 'Rp ' . number_format((float)($reg['unit_price'] ?? 0), 0, ',', '.');
        $reg['subtotal_fmt']    = 'Rp ' . number_format((float)($reg['subtotal'] ?? 0), 0, ',', '.');
        $reg['final_total_fmt'] = 'Rp ' . number_format((float)($reg['final_total'] ?? 0), 0, ',', '.');

        // Sediakan data untuk tombol WA
        $data = [
            'reg'     => $reg,
            'message' => session('success') ?? '',
            'user'    => [
                'name' => session('user_name') ?? '',
            ],
            // Nomor WA tujuan dalam format internasional (Indonesia: 62…)
            // Ganti sesuai kebutuhan (sebaiknya taruh di .env / config)
            'wa_number_intl' => '+6285733771515',
        ];

        return view('registrations/success', $data);
    }
}
