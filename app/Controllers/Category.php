<?php

namespace App\Controllers;

use App\Models\CategoryModel;

class Category extends BaseController
{
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }

    // List semua kategori
    public function index()
    {
        $data = [
            'title' => 'Kategori',
            'categories' => $this->categoryModel->findAll()
        ];
        return view('category/index', $data);
    }

    // Form create
    public function create()
    {
        $data = [
            'title' => 'Tambah Kategori',
            'validation' => \Config\Services::validation()
        ];
        return view('category/create', $data);
    }

    // Simpan kategori baru
    public function store()
    {
        if (!$this->validate([
            'name' => 'required|min_length[3]|max_length[100]',
            'description' => 'permit_empty|string',
            'image' => 'uploaded[image]|is_image[image]|max_size[image,2048]',
        ])) {
            return redirect()->back()->withInput();
        }

        // Upload file ke folder public/uploads/categories
        $file = $this->request->getFile('image');
        $fileName = $file->getRandomName();
        $file->move(FCPATH . 'uploads/categories', $fileName);

        $this->categoryModel->save([
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'image'       => $fileName
        ]);

        return redirect()->to('/category')->with('success', 'Kategori berhasil ditambahkan.');
    }
}
