<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ClassSessionModel;
use App\Models\ClassModel;

class ClassSessions extends BaseController
{
    protected $sessionModel;
    protected $classModel;

    public function __construct()
    {
        $this->sessionModel = new ClassSessionModel();
        $this->classModel   = new ClassModel();
        helper(['form']);
    }

    public function index()
    {
        // Join utk tampilkan title kelas
        $builder = $this->sessionModel
            ->select('class_sessions.*, classes.title AS class_title')
            ->join('classes', 'classes.id = class_sessions.class_id', 'left')
            ->orderBy('class_sessions.id', 'DESC');

        $data = [
            'title'          => 'Class Sessions',
            'class_sessions' => $builder->findAll(),
        ];
        return view('class_sessions/index', $data);
    }

    public function create()
    {
        $classes = $this->classModel->orderBy('title', 'ASC')->findAll();
        return view('class_sessions/create', [
            'title'      => 'Create Class Session',
            'classes'    => $classes,
            'validation' => \Config\Services::validation(),
        ]);
    }

    public function store()
    {
        $rules = [
            'class_id'      => 'required|is_natural_no_zero',
            'name'          => 'required|max_length[100]',
            'description'   => 'permit_empty',
            'level'         => 'permit_empty|max_length[255]',
            'capacity'      => 'permit_empty|is_natural',
            'schedule_date' => 'required|valid_date',
            'start_time'    => 'required|regex_match[/^\d{2}:\d{2}(:\d{2})?$/]',
            'end_time'      => 'required|regex_match[/^\d{2}:\d{2}(:\d{2})?$/]',
            'location'      => 'permit_empty|max_length[150]',
            'status'        => 'permit_empty|max_length[255]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Validasi gagal.')
                ->with('errors', $this->validator->getErrors());
        }

        $this->sessionModel->save([
            'class_id'      => (int) $this->request->getPost('class_id'),
            'name'          => (string) $this->request->getPost('name'),
            'description'   => (string) $this->request->getPost('description'),
            'level'         => (string) $this->request->getPost('level'),
            'capacity'      => $this->request->getPost('capacity') !== null ? (int) $this->request->getPost('capacity') : null,
            'schedule_date' => (string) $this->request->getPost('schedule_date'),
            'start_time'    => (string) $this->request->getPost('start_time'),
            'end_time'      => (string) $this->request->getPost('end_time'),
            'location'      => (string) $this->request->getPost('location'),
            'status'        => (string) $this->request->getPost('status'),
        ]);

        return redirect()->to('/class-sessions')->with('success', 'Session berhasil dibuat.');
    }

    public function edit($id = null)
    {
        $session = $this->sessionModel->find($id);
        if (! $session) {
            return redirect()->to('/class-sessions')->with('error', 'Data tidak ditemukan.');
        }

        $classes = $this->classModel->orderBy('title', 'ASC')->findAll();

        return view('class_sessions/edit', [
            'title'      => 'Edit Class Session',
            'session'    => $session,
            'classes'    => $classes,
            'validation' => \Config\Services::validation(),
        ]);
    }

    public function update($id = null)
    {
        $session = $this->sessionModel->find($id);
        if (! $session) {
            return redirect()->to('/class-sessions')->with('error', 'Data tidak ditemukan.');
        }

        $rules = [
            'class_id'      => 'required|is_natural_no_zero',
            'name'          => 'required|max_length[100]',
            'description'   => 'permit_empty',
            'level'         => 'permit_empty|max_length[255]',
            'capacity'      => 'permit_empty|is_natural',
            'schedule_date' => 'required|valid_date',
            'start_time'    => 'required|regex_match[/^\d{2}:\d{2}(:\d{2})?$/]',
            'end_time'      => 'required|regex_match[/^\d{2}:\d{2}(:\d{2})?$/]',
            'location'      => 'permit_empty|max_length[150]',
            'status'        => 'permit_empty|max_length[255]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Validasi gagal.')
                ->with('errors', $this->validator->getErrors());
        }

        $this->sessionModel->update($id, [
            'class_id'      => (int) $this->request->getPost('class_id'),
            'name'          => (string) $this->request->getPost('name'),
            'description'   => (string) $this->request->getPost('description'),
            'level'         => (string) $this->request->getPost('level'),
            'capacity'      => $this->request->getPost('capacity') !== null ? (int) $this->request->getPost('capacity') : null,
            'schedule_date' => (string) $this->request->getPost('schedule_date'),
            'start_time'    => (string) $this->request->getPost('start_time'),
            'end_time'      => (string) $this->request->getPost('end_time'),
            'location'      => (string) $this->request->getPost('location'),
            'status'        => (string) $this->request->getPost('status'),
        ]);

        return redirect()->to('/class-sessions')->with('success', 'Session berhasil diperbarui.');
    }

    public function delete($id = null)
    {
        $session = $this->sessionModel->find($id);
        if (! $session) {
            return redirect()->to('/class-sessions')->with('error', 'Data tidak ditemukan.');
        }

        $this->sessionModel->delete($id);

        return redirect()->to('/class-sessions')->with('success', 'Session berhasil dihapus.');
    }

    public function show($id = null)
    {
        $row = $this->sessionModel
            ->select('class_sessions.*, classes.title AS class_title')
            ->join('classes', 'classes.id = class_sessions.class_id', 'left')
            ->where('class_sessions.id', $id)
            ->first();

        if (! $row) {
            return redirect()->to('/class-sessions')->with('error', 'Data tidak ditemukan.');
        }

        return view('class_sessions/show', [
            'title'   => 'Detail Class Session',
            'session' => $row,
        ]);
    }
}
