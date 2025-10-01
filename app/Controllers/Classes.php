<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ClassModel;

class Classes extends BaseController
{
    protected $classModel;
    protected $uploadDir = 'uploads/classes/'; // relatif dari public/
    protected $uploadPath; // absolute path (FCPATH . uploads/classes/)

    public function __construct()
    {
        $this->classModel = new ClassModel();
        helper(['form', 'text']);

        $this->uploadPath = rtrim(FCPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $this->uploadDir);
        $this->ensureUploadDir();
    }

    /**
     * Pastikan folder upload tersedia.
     */
    protected function ensureUploadDir(): void
    {
        if (!is_dir($this->uploadPath)) {
            @mkdir($this->uploadPath, 0755, true);
        }
    }

    public function index()
    {
        $data = [
            'title'   => 'Classes',
            'classes' => $this->classModel->orderBy('id', 'DESC')->findAll(),
        ];
        return view('classes/index', $data);
    }

    public function create()
    {
        return view('classes/create', [
            'title'       => 'Create Class',
            'validation'  => \Config\Services::validation(),
        ]);
    }

    public function store()
    {
        $rules = [
            'title'       => 'required|max_length[150]',
            'description' => 'permit_empty|max_length[255]',
            'price'       => 'permit_empty|decimal',
            'image'       => 'permit_empty|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]|max_size[image,4096]',
            'is_active'   => 'permit_empty|in_list[0,1]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Validasi gagal.')
                ->with('errors', $this->validator->getErrors());
        }

        // Upload image (opsional) ke public/uploads/classes
        $imageName = null;
        $file = $this->request->getFile('image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $imageName = $file->getRandomName();
            $file->move($this->uploadPath, $imageName);
        }

        $this->classModel->save([
            'title'       => (string) $this->request->getPost('title'),
            'description' => (string) $this->request->getPost('description'),
            'price'       => $this->request->getPost('price') !== null ? (float) $this->request->getPost('price') : 0,
            'image'       => $imageName,
            'is_active'   => $this->request->getPost('is_active') ? 1 : 0,
        ]);

        return redirect()->to('/classes')->with('success', 'Class berhasil dibuat.');
    }

    public function edit($id = null)
    {
        $class = $this->classModel->find($id);
        if (!$class) {
            return redirect()->to('/classes')->with('error', 'Data tidak ditemukan.');
        }

        return view('classes/edit', [
            'title'      => 'Edit Class',
            'class'      => $class,
            'validation' => \Config\Services::validation(),
        ]);
    }

    public function update($id = null)
    {
        $class = $this->classModel->find($id);
        if (!$class) {
            return redirect()->to('/classes')->with('error', 'Data tidak ditemukan.');
        }

        $rules = [
            'title'       => 'required|max_length[150]',
            'description' => 'permit_empty|max_length[255]',
            'price'       => 'permit_empty|decimal',
            'image'       => 'permit_empty|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]|max_size[image,4096]',
            'is_active'   => 'permit_empty|in_list[0,1]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Validasi gagal.')
                ->with('errors', $this->validator->getErrors());
        }

        $imageName = $class['image']; // default tetap pakai yang lama
        $file = $this->request->getFile('image');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Hapus file lama jika ada
            if (!empty($imageName)) {
                $old = $this->uploadPath . $imageName;
                if (is_file($old)) {
                    @unlink($old);
                }
            }
            // Simpan file baru
            $imageName = $file->getRandomName();
            $file->move($this->uploadPath, $imageName);
        }

        $this->classModel->update($id, [
            'title'       => (string) $this->request->getPost('title'),
            'description' => (string) $this->request->getPost('description'),
            'price'       => $this->request->getPost('price') !== null ? (float) $this->request->getPost('price') : 0,
            'image'       => $imageName,
            'is_active'   => $this->request->getPost('is_active') ? 1 : 0,
        ]);

        return redirect()->to('/classes')->with('success', 'Class berhasil diperbarui.');
    }

    public function delete($id = null)
    {
        $class = $this->classModel->find($id);
        if (!$class) {
            return redirect()->to('/classes')->with('error', 'Data tidak ditemukan.');
        }

        // Hapus file gambar jika ada
        if (!empty($class['image'])) {
            $path = $this->uploadPath . $class['image'];
            if (is_file($path)) {
                @unlink($path);
            }
        }

        $this->classModel->delete($id);

        return redirect()->to('/classes')->with('success', 'Class berhasil dihapus.');
    }

    public function show($id = null)
    {
        $class = $this->classModel->find($id);
        if (!$class) {
            return redirect()->to('/classes')->with('error', 'Data tidak ditemukan.');
        }

        return view('classes/show', [
            'title' => 'Detail Class',
            'class' => $class,
        ]);
    }
}
