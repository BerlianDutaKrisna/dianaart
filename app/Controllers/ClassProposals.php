<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ClassProposalModel;

class ClassProposals extends BaseController
{
    protected $proposalModel;

    public function __construct()
    {
        $this->proposalModel = new ClassProposalModel();
        helper(['form', 'url']);
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
        $price        = (float) ($this->request->getPost('price') ?? 0);
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

        // Validasi rentang waktu (opsional tapi disarankan)
        if ($scheduleDate !== '' && $startTime !== '' && $endTime !== '') {
            $startTs = strtotime($scheduleDate . ' ' . $startTime);
            $endTs   = strtotime($scheduleDate . ' ' . $endTime);
            if ($startTs !== false && $endTs !== false && $endTs <= $startTs) {
                $errors['end_time'] = 'Waktu selesai harus setelah waktu mulai.';
            }
        }

        // Validasi & upload image (opsional)
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
            $targetDir = FCPATH . 'uploads/proposals';
            if (!is_dir($targetDir)) {
                @mkdir($targetDir, 0755, true);
            }
            $file->move($targetDir, $imageName);
        }

        $id = $this->proposalModel->insert([
            'user_id'       => $userId,
            'title'         => $title,
            'description'   => $description,
            'price'         => $price,
            'image'         => $imageName,
            'schedule_date' => $scheduleDate,
            'start_time'    => $startTime,
            'end_time'      => $endTime,
            'location'      => $location,
            'status'        => 'pending',
            'is_active'     => 1,
        ], true);

        return redirect()->to(base_url('proposals/success/' . $id))
            ->with('success', 'Usulan berhasil dikirim. Terima kasih!');
    }

    // GET /proposals/success/{id}
    public function success($id)
    {
        $userId = session('user_id') ?? null;
        if (empty($userId)) {
            return redirect()->to(base_url('login'));
        }

        $proposal = $this->proposalModel
            ->where('id', (int)$id)
            ->where('user_id', (int)$userId)
            ->first();

        if (!$proposal) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Proposal tidak ditemukan.');
        }

        $proposal['image_url'] = !empty($proposal['image'])
            ? base_url('uploads/proposals/' . $proposal['image'])
            : '';

        // Formatting kecil untuk tampilan
        $proposal['date_fmt'] = !empty($proposal['schedule_date']) ? date('d M Y', strtotime($proposal['schedule_date'])) : '';
        $proposal['time_fmt'] = (!empty($proposal['start_time']) && !empty($proposal['end_time']))
            ? (date('H:i', strtotime($proposal['start_time'])) . '–' . date('H:i', strtotime($proposal['end_time'])))
            : '';

        return view('proposals/success', [
            'proposal' => $proposal,
            'message'  => session('success') ?? '',
        ]);
    }
}
