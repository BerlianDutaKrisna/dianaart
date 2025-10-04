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

    /* ======================
     * READ - LIST
     * ====================== */
    public function index()
    {
        $data['registrations'] = $this->registrationModel
            ->select('registrations.*, users.name as user_name, users.email as user_email, class_sessions.name as session_name, classes.title as class_title')
            ->join('users', 'users.id = registrations.user_id', 'left')
            ->join('class_sessions', 'class_sessions.id = registrations.session_id', 'left')
            ->join('classes', 'classes.id = class_sessions.class_id', 'left')
            ->orderBy('registrations.created_at', 'DESC')
            ->findAll();

        return view('registrations/index', $data);
    }

    /* ======================
     * READ - DETAIL
     * GET /registrations/show/{id}
     * ====================== */
    public function show($id)
    {
        $userId = session('user_id') ?? null;
        if (empty($userId)) return redirect()->to(base_url('login'));

        $r = $this->registrationModel
            ->select('
                registrations.*,
                users.name as user_name, users.email as user_email,
                class_sessions.name as session_name, class_sessions.schedule_date as session_date,
                class_sessions.start_time as session_start, class_sessions.end_time as session_end,
                class_sessions.location as session_location,
                classes.title as class_title, classes.price as class_price
            ')
            ->join('users', 'users.id = registrations.user_id', 'left')
            ->join('class_sessions', 'class_sessions.id = registrations.session_id', 'left')
            ->join('classes', 'classes.id = class_sessions.class_id', 'left')
            ->where('registrations.id', (int)$id)
            ->first();

        if (!$r) throw PageNotFoundException::forPageNotFound('Registration not found');

        // (opsional) batasi akses hanya owner/admin
        if (!$this->canManage($r)) {
            return redirect()->to(base_url('registrations'))->with('error', 'Tidak memiliki akses.');
        }

        // format angka untuk tampilan
        $r['unit_price_fmt']  = 'Rp ' . number_format((float)($r['unit_price'] ?? 0), 0, ',', '.');
        $r['subtotal_fmt']    = 'Rp ' . number_format((float)($r['subtotal'] ?? 0), 0, ',', '.');
        $r['final_total_fmt'] = 'Rp ' . number_format((float)($r['final_total'] ?? 0), 0, ',', '.');

        return view('registrations/show', ['reg' => $r]);
    }

    /* ======================
     * CREATE
     * GET /register[/(:num)]
     * ====================== */
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

    /* ======================
     * STORE
     * POST /register
     * ====================== */
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
            // Kurangi capacity jika dan hanya jika capacity masih >= quantity
            $affected = $db->table('class_sessions')
                ->set('capacity', 'capacity - ' . (int)$quantity, false)
                ->where('id', $sessionId)
                ->where('capacity >=', (int)$quantity)
                ->update();

            if (!$affected || $db->affectedRows() !== 1) {
                $db->transRollback();
                return redirect()->back()->withInput()->with('errors', [
                    'capacity' => 'Kelas sudah penuh atau sisa kursi tidak mencukupi.'
                ]);
            }

            // Insert registrations.
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
                $db->transRollback();
                return redirect()->back()->withInput()->with('errors', [
                    'general' => 'Gagal menyimpan registrasi.'
                ]);
            }

            $db->transCommit();

            return redirect()->to(base_url('register/success/' . $regId))
                ->with('success', 'Registrasi berhasil dibuat.');
        } catch (\Throwable $e) {
            if ($db->transStatus() !== false) {
                $db->transRollback();
            }
            return redirect()->back()->withInput()->with('errors', [
                'general' => 'Gagal menyimpan: ' . $e->getMessage()
            ]);
        }
    }

    /* ======================
     * SUCCESS (tampilan sukses)
     * GET /register/success/{id}
     * ====================== */
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
            throw PageNotFoundException::forPageNotFound('Registration not found');
        }

        // Formatting angka
        $reg['unit_price_fmt']  = 'Rp ' . number_format((float)($reg['unit_price'] ?? 0), 0, ',', '.');
        $reg['subtotal_fmt']    = 'Rp ' . number_format((float)($reg['subtotal'] ?? 0), 0, ',', '.');
        $reg['final_total_fmt'] = 'Rp ' . number_format((float)($reg['final_total'] ?? 0), 0, ',', '.');

        $data = [
            'reg'     => $reg,
            'message' => session('success') ?? '',
            'user'    => [
                'name' => session('user_name') ?? '',
            ],
            // Nomor WA tujuan dalam format internasional (Indonesia: 62…)
            'wa_number_intl' => '+6285733771515',
        ];

        return view('registrations/success', $data);
    }

    /* ======================
     * EDIT
     * GET /registrations/edit/{id}
     * ====================== */
    public function edit($id)
    {
        $userId = session('user_id') ?? null;
        if (empty($userId)) return redirect()->to(base_url('login'));

        $reg = $this->registrationModel
            ->select('
                registrations.*,
                class_sessions.name as session_name,
                class_sessions.schedule_date, class_sessions.start_time, class_sessions.end_time, class_sessions.location,
                classes.title as class_title, classes.price as class_price
            ')
            ->join('class_sessions', 'class_sessions.id = registrations.session_id', 'left')
            ->join('classes', 'classes.id = class_sessions.class_id', 'left')
            ->where('registrations.id', (int)$id)
            ->first();

        if (!$reg) throw PageNotFoundException::forPageNotFound('Registration not found');

        if (!$this->canManage($reg)) {
            return redirect()->to(base_url('registrations'))->with('error', 'Tidak memiliki akses.');
        }

        // (opsional) daftar sesi untuk pindah sesi
        $today = date('Y-m-d');
        $now   = date('H:i:s');
        $sessions = $this->classSessionModel
            ->select('class_sessions.*, classes.title AS class_title, classes.price AS class_price')
            ->join('classes', 'classes.id = class_sessions.class_id', 'inner')
            ->where('classes.is_active', 1)
            ->orderBy('class_sessions.schedule_date', 'ASC')
            ->orderBy('class_sessions.start_time', 'ASC')
            ->findAll();

        return view('registrations/edit', [
            'reg'      => $reg,
            'sessions' => $sessions,
            'errors'   => session('errors') ?? [],
            'old'      => session()->getFlashdata('_ci_old_input') ?? [],
        ]);
    }

    /* ======================
     * UPDATE
     * POST /registrations/update/{id}
     * - Bisa ubah quantity
     * - Bisa pindah session (opsional)
     * ====================== */
    public function update($id)
    {
        $userId = session('user_id') ?? null;
        if (empty($userId)) return redirect()->to(base_url('login'));

        $reg = $this->registrationModel->find((int)$id);
        if (!$reg) throw PageNotFoundException::forPageNotFound('Registration not found');

        if (!$this->canManage($reg)) {
            return redirect()->to(base_url('registrations'))->with('error', 'Tidak memiliki akses.');
        }

        $newSessionId = (int) ($this->request->getPost('session_id') ?? $reg['session_id']);
        $newQty       = (int) ($this->request->getPost('quantity') ?? $reg['quantity']);
        $newStatus    = trim($this->request->getPost('status') ?? $reg['status']);

        $errors = [];
        if ($newSessionId <= 0) $errors['session_id'] = 'Session harus dipilih.';
        if ($newQty <= 0)       $errors['quantity']   = 'Quantity minimal 1.';

        // Ambil data sesi baru + harga untuk re-calc unit_price bila pindah kelas
        $sessionRow = $this->classSessionModel
            ->select('class_sessions.*, classes.price AS class_price')
            ->join('classes', 'classes.id = class_sessions.class_id', 'inner')
            ->where('class_sessions.id', $newSessionId)
            ->first();

        if (!$sessionRow) {
            $errors['session_id'] = 'Session tidak ditemukan.';
        }

        if (!empty($errors)) {
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        // Recompute harga (unit price ikut kelas sesi tujuan)
        $unitPrice  = (float) ($sessionRow['class_price'] ?? $reg['unit_price']);
        $subtotal   = $unitPrice * $newQty;
        $finalTotal = $subtotal; // tempat hitung diskon bila ada

        // ===== Adjust kapasitas secara atomik dalam transaksi =====
        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $oldSessionId = (int)$reg['session_id'];
            $oldQty       = (int)$reg['quantity'];

            if ($newSessionId === $oldSessionId) {
                // Kasus A: sesi sama, hanya ubah quantity -> adjust delta
                $delta = $newQty - $oldQty; // jika >0 butuh kursi tambahan; jika <0 mengembalikan kursi
                if ($delta !== 0) {
                    if (!$this->adjustCapacityTx($db, $newSessionId, $delta)) {
                        $db->transRollback();
                        return redirect()->back()->withInput()->with('errors', [
                            'capacity' => 'Kapasitas tidak mencukupi untuk perubahan quantity.'
                        ]);
                    }
                }
            } else {
                // Kasus B: pindah sesi -> kembalikan kapasitas sesi lama, ambil dari sesi baru
                // kembalikan kursi lama
                if (!$this->adjustCapacityTx($db, $oldSessionId, -$oldQty)) { // -oldQty = tambah capacity
                    $db->transRollback();
                    return redirect()->back()->withInput()->with('errors', [
                        'capacity' => 'Gagal mengembalikan kapasitas sesi lama.'
                    ]);
                }
                // ambil kursi baru
                if (!$this->adjustCapacityTx($db, $newSessionId, +$newQty)) { // +newQty = mengurangi capacity
                    // batalkan pengembalian lama jika gagal ambil baru? sudah dalam 1 transaksi -> rollback
                    $db->transRollback();
                    return redirect()->back()->withInput()->with('errors', [
                        'capacity' => 'Kapasitas sesi tujuan tidak mencukupi.'
                    ]);
                }
            }

            // Update record
            $this->registrationModel->update((int)$id, [
                'session_id'  => $newSessionId,
                'quantity'    => $newQty,
                'unit_price'  => $unitPrice,
                'subtotal'    => $subtotal,
                'final_total' => $finalTotal,
                'status'      => $newStatus,
                // 'updated_at' otomatis jika pakai timestamps di model
            ]);

            $db->transCommit();

            return redirect()->to(base_url('registrations/show/' . $id))
                ->with('success', 'Registrasi berhasil diperbarui.');
        } catch (\Throwable $e) {
            if ($db->transStatus() !== false) $db->transRollback();
            return redirect()->back()->withInput()->with('errors', [
                'general' => 'Gagal memperbarui: ' . $e->getMessage()
            ]);
        }
    }

    /* ======================
     * DELETE
     * POST /registrations/delete/{id}
     * - Kembalikan kapasitas sebesar quantity jika registrasi masih “memegang” kursi
     * ====================== */
    public function delete($id)
    {
        $userId = session('user_id') ?? null;
        if (empty($userId)) return redirect()->to(base_url('login'));

        $reg = $this->registrationModel->find((int)$id);
        if (!$reg) {
            return redirect()->to(base_url('registrations'))->with('error', 'Registrasi tidak ditemukan.');
        }

        if (!$this->canManage($reg)) {
            return redirect()->to(base_url('registrations'))->with('error', 'Tidak memiliki akses.');
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            // Kembalikan kapasitas jika status registrasi masih menghitung seat (Registered/Paid).
            // Kalau kamu ingin hanya saat Registered saja, sesuaikan logic di bawah.
            $countSeatStatuses = ['registered', 'paid', 'Registered', 'Paid'];
            if (in_array($reg['status'], $countSeatStatuses, true)) {
                $ok = $this->adjustCapacityTx($db, (int)$reg['session_id'], - ((int)$reg['quantity'])); // negative delta => tambah capacity
                if (!$ok) {
                    $db->transRollback();
                    return redirect()->to(base_url('registrations'))->with('error', 'Gagal mengembalikan kapasitas.');
                }
            }

            $this->registrationModel->delete((int)$id);

            $db->transCommit();

            return redirect()->to(base_url('registrations'))->with('success', 'Registrasi berhasil dihapus.');
        } catch (\Throwable $e) {
            if ($db->transStatus() !== false) $db->transRollback();
            return redirect()->to(base_url('registrations'))->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    /* ======================
     * Helpers
     * ====================== */

    /**
     * Cek apakah user sekarang boleh mengelola data ini (owner atau admin).
     */
    private function canManage(array $row): bool
    {
        $userId   = (int) (session('user_id') ?? 0);
        $userRole = (string) (session('user_role') ?? '');
        return ((int)$row['user_id'] === $userId) || ($userRole === 'admin');
    }

    /**
     * Adjust kapasitas sesi secara atomik di dalam transaksi berjalan.
     *
     * @param \CodeIgniter\Database\BaseConnection $db
     * @param int $sessionId
     * @param int $delta  +N = butuh N kursi (capacity -= N), -N = kembalikan N kursi (capacity += N)
     * @return bool
     */
    private function adjustCapacityTx($db, int $sessionId, int $delta): bool
    {
        if ($delta === 0) return true;

        if ($delta > 0) {
            // minta tambahan kursi: kurangi capacity jika cukup
            $ok = $db->table('class_sessions')
                ->set('capacity', 'capacity - ' . $delta, false)
                ->where('id', $sessionId)
                ->where('capacity >=', $delta)
                ->update();
            return $ok && ($db->affectedRows() === 1);
        } else {
            // kembalikan kursi: tambah capacity (selalu boleh)
            $ok = $db->table('class_sessions')
                ->set('capacity', 'capacity + ' . abs($delta), false)
                ->where('id', $sessionId)
                ->update();
            return $ok && ($db->affectedRows() === 1);
        }
    }
}
