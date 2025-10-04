<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ClassProposalModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class ClassProposals extends BaseController
{
    protected $proposalModel;

    public function __construct()
    {
        $this->proposalModel = new ClassProposalModel();
        helper(['form', 'url']);
    }

    // GET /proposals
    public function index()
    {
        // Jika ingin batasi hanya milik user login, uncomment baris where user_id = $userId
        $userId = session('user_id') ?? null;

        $data['proposals'] = $this->proposalModel
            ->select('class_proposals.*, users.name as user_name')
            ->join('users', 'users.id = class_proposals.user_id', 'left')
            // ->where('class_proposals.user_id', (int) $userId)
            ->orderBy('class_proposals.created_at', 'DESC')
            ->findAll();

        return view('proposals/index', $data);
    }

    // GET /proposals/show/{id}
    public function show($id)
    {
        $proposal = $this->proposalModel
            ->select('class_proposals.*, users.name as user_name, users.email as user_email')
            ->join('users', 'users.id = class_proposals.user_id', 'left')
            ->find((int) $id);

        if (!$proposal) {
            throw PageNotFoundException::forPageNotFound('Proposal tidak ditemukan.');
        }

        $proposal['image_url'] = !empty($proposal['image'])
            ? base_url('uploads/proposals/' . $proposal['image'])
            : '';

        return view('proposals/show', ['proposal' => $proposal]);
    }

    // GET /proposals/create
    public function create()
    {
        $userId = session('user_id') ?? null;
        if (empty($userId)) {
            return redirect()->to(base_url('login'));
        }

        return view('proposals/create', [
            'errors' => session('errors') ?? [],
            'old'    => session()->getFlashdata('_ci_old_input') ?? [],
        ]);
    }

    // POST /proposals
    public function store()
    {
        $userId = session('user_id') ?? null;
        if (empty($userId)) {
            return redirect()->to(base_url('login'));
        }

        $title        = trim($this->request->getPost('title') ?? '');
        $description  = trim($this->request->getPost('description') ?? '');
        $scheduleDate = trim($this->request->getPost('schedule_date') ?? '');
        $startTime    = trim($this->request->getPost('start_time') ?? '');
        $endTime      = trim($this->request->getPost('end_time') ?? '');
        $location     = trim($this->request->getPost('location') ?? '');

        $errors = [];
        if ($title === '')         $errors['title']         = 'Judul wajib diisi.';
        if ($scheduleDate === '')  $errors['schedule_date'] = 'Tanggal wajib diisi.';
        if ($startTime === '')     $errors['start_time']    = 'Waktu mulai wajib diisi.';
        if ($endTime === '')       $errors['end_time']      = 'Waktu selesai wajib diisi.';
        if ($location === '')      $errors['location']      = 'Lokasi wajib diisi.';

        // Validasi rentang waktu
        if ($scheduleDate !== '' && $startTime !== '' && $endTime !== '') {
            $startTs = strtotime($scheduleDate . ' ' . $startTime);
            $endTs   = strtotime($scheduleDate . ' ' . $endTime);
            if ($startTs !== false && $endTs !== false && $endTs <= $startTs) {
                $errors['end_time'] = 'Waktu selesai harus setelah waktu mulai.';
            }
        }

        // Validasi & upload image
        $imageName = null;
        $file = $this->request->getFile('image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $ext = strtolower($file->getExtension() ?? '');
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $errors['image'] = 'Format gambar harus jpg, jpeg, png, atau webp.';
            }
        }

        if (!empty($errors)) {
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $imageName = $file->getRandomName();
            $this->ensureUploadDir();
            $file->move($this->uploadDir(), $imageName);
        }

        $id = $this->proposalModel->insert([
            'user_id'       => $userId,
            'title'         => $title,
            'description'   => $description,
            'image'         => $imageName,
            'schedule_date' => $scheduleDate,
            'start_time'    => $startTime,
            'end_time'      => $endTime,
            'location'      => $location,
            'status'        => 'Pending',
            'is_active'     => 1,
        ], true);

        return redirect()->to(base_url('proposals/success/' . $id))
            ->with('success', 'Usulan berhasil dikirim. Terima kasih!');
    }

    // GET /proposals/edit/{id}
    public function edit($id)
    {
        $userId = session('user_id') ?? null;
        if (empty($userId)) {
            return redirect()->to(base_url('login'));
        }

        $proposal = $this->proposalModel->find((int) $id);
        if (!$proposal) {
            throw PageNotFoundException::forPageNotFound('Proposal tidak ditemukan.');
        }

        // Guard: hanya pemilik atau admin
        if (!$this->canManage($proposal)) {
            return redirect()->to(base_url('proposals'))
                ->with('error', 'Tidak punya akses untuk mengedit proposal ini.');
        }

        return view('proposals/edit', [
            'proposal' => $proposal,
            'errors'   => session('errors') ?? [],
            'old'      => session()->getFlashdata('_ci_old_input') ?? [],
        ]);
    }

    // POST /proposals/update/{id}
    public function update($id)
    {
        $userId = session('user_id') ?? null;
        if (empty($userId)) {
            return redirect()->to(base_url('login'));
        }

        $proposal = $this->proposalModel->find((int) $id);
        if (!$proposal) {
            throw PageNotFoundException::forPageNotFound('Proposal tidak ditemukan.');
        }

        if (!$this->canManage($proposal)) {
            return redirect()->to(base_url('proposals'))
                ->with('error', 'Tidak punya akses untuk mengedit proposal ini.');
        }

        $title        = trim($this->request->getPost('title') ?? '');
        $description  = trim($this->request->getPost('description') ?? '');
        $scheduleDate = trim($this->request->getPost('schedule_date') ?? '');
        $startTime    = trim($this->request->getPost('start_time') ?? '');
        $endTime      = trim($this->request->getPost('end_time') ?? '');
        $location     = trim($this->request->getPost('location') ?? '');
        $status       = trim($this->request->getPost('status') ?? $proposal['status']);
        $isActive     = (int) ($this->request->getPost('is_active') ?? $proposal['is_active']);

        $errors = [];
        if ($title === '')         $errors['title']         = 'Judul wajib diisi.';
        if ($scheduleDate === '')  $errors['schedule_date'] = 'Tanggal wajib diisi.';
        if ($startTime === '')     $errors['start_time']    = 'Waktu mulai wajib diisi.';
        if ($endTime === '')       $errors['end_time']      = 'Waktu selesai wajib diisi.';
        if ($location === '')      $errors['location']      = 'Lokasi wajib diisi.';

        // Validasi rentang waktu
        if ($scheduleDate !== '' && $startTime !== '' && $endTime !== '') {
            $startTs = strtotime($scheduleDate . ' ' . $startTime);
            $endTs   = strtotime($scheduleDate . ' ' . $endTime);
            if ($startTs !== false && $endTs !== false && $endTs <= $startTs) {
                $errors['end_time'] = 'Waktu selesai harus setelah waktu mulai.';
            }
        }

        // Cek file baru (opsional)
        $file = $this->request->getFile('image');
        $newImage = null;
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $ext = strtolower($file->getExtension() ?? '');
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $errors['image'] = 'Format gambar harus jpg, jpeg, png, atau webp.';
            }
        }

        if (!empty($errors)) {
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        // Upload & replace
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newImage = $file->getRandomName();
            $this->ensureUploadDir();
            $file->move($this->uploadDir(), $newImage);

            // hapus yang lama jika ada
            if (!empty($proposal['image'])) {
                $this->deleteImage($proposal['image']);
            }
        }

        $payload = [
            'title'         => $title,
            'description'   => $description,
            'schedule_date' => $scheduleDate,
            'start_time'    => $startTime,
            'end_time'      => $endTime,
            'location'      => $location,
            'status'        => $status,
            'is_active'     => $isActive,
        ];
        if ($newImage !== null) {
            $payload['image'] = $newImage;
        }

        $this->proposalModel->update((int) $id, $payload);

        return redirect()->to(base_url('proposals/show/' . $id))
            ->with('success', 'Proposal berhasil diperbarui.');
    }

    // POST /proposals/delete/{id}
    public function delete($id)
    {
        $userId = session('user_id') ?? null;
        if (empty($userId)) {
            return redirect()->to(base_url('login'));
        }

        $proposal = $this->proposalModel->find((int) $id);
        if (!$proposal) {
            return redirect()->to(base_url('proposals'))->with('error', 'Proposal tidak ditemukan.');
        }

        if (!$this->canManage($proposal)) {
            return redirect()->to(base_url('proposals'))
                ->with('error', 'Tidak punya akses untuk menghapus proposal ini.');
        }

        // hapus file gambar jika ada
        if (!empty($proposal['image'])) {
            $this->deleteImage($proposal['image']);
        }

        $this->proposalModel->delete((int) $id);

        return redirect()->to(base_url('proposals'))->with('success', 'Proposal berhasil dihapus.');
    }

    // GET /proposals/success/{id}
    public function success($id)
    {
        // Guard sederhana: wajib login
        $userId = session('user_id') ?? null;
        if (empty($userId)) {
            return redirect()->to(base_url('login'));
        }

        // Ambil data proposal milik user ini saja
        $proposal = $this->proposalModel
            ->select('class_proposals.*') // bisa ditambah join users bila perlu
            ->where('class_proposals.id', (int) $id)
            ->where('class_proposals.user_id', (int) $userId) // hanya milik user ini
            ->first();

        if (!$proposal) {
            throw PageNotFoundException::forPageNotFound('Proposal tidak ditemukan.');
        }

        // URL gambar (jika ada)
        $proposal['image_url'] = !empty($proposal['image'])
            ? base_url('uploads/proposals/' . $proposal['image'])
            : '';

        // Formatting jadwal (persis seperti style registrasi)
        $proposal['date_fmt'] = !empty($proposal['schedule_date'])
            ? date('d M Y', strtotime($proposal['schedule_date'])) : '';
        $proposal['time_fmt'] = (!empty($proposal['start_time']) && !empty($proposal['end_time']))
            ? (date('H:i', strtotime($proposal['start_time'])) . '–' . date('H:i', strtotime($proposal['end_time'])))
            : '';

        // (Opsional) kalau mau konsistensi naming seperti di registrasi:
        $proposal['session_name']     = null; // tidak ada konsep session di proposal
        $proposal['session_date']     = $proposal['schedule_date'] ?? '';
        $proposal['session_start']    = $proposal['start_time'] ?? '';
        $proposal['session_end']      = $proposal['end_time'] ?? '';
        $proposal['session_location'] = $proposal['location'] ?? '';

        // Data untuk view: samakan struktur dengan Registration success
        $data = [
            'proposal' => $proposal,
            'message'  => session('success') ?? '',
            'user'     => [
                'name' => session('user_name') ?? '',
            ],
            // Nomor WA tujuan dalam format internasional (Indonesia: 62… tanpa +)
            // Ganti ke nomor admin/CS kamu:
            'wa_number_intl' => '6285733771515',
        ];

        return view('proposals/success', $data);
    }

    /* =======================
     * Helpers (private)
     * ======================= */

    private function canManage(array $proposal): bool
    {
        $userId   = (int) (session('user_id') ?? 0);
        $userRole = (string) (session('user_role') ?? '');
        return ($proposal['user_id'] == $userId) || ($userRole === 'admin');
    }

    private function uploadDir(): string
    {
        return FCPATH . 'uploads/proposals';
    }

    private function ensureUploadDir(): void
    {
        $dir = $this->uploadDir();
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
    }

    private function deleteImage(string $filename): void
    {
        $path = rtrim($this->uploadDir(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename;
        if (is_file($path)) {
            @unlink($path);
        }
    }
}
