<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use CodeIgniter\Controller;

class Category extends BaseController
{
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }

    // list data
    public function index()
    {
        $data['categories'] = $this->categoryModel->findAll();
        return view('category/index', $data);
    }

    // tampilkan form create
    public function create()
    {
        return view('category/create', [
            'validation' => \Config\Services::validation()
        ]);
    }


    // simpan data baru
    public function store()
    {
        $image = $this->request->getFile('image');
        $imageName = null;

        if ($image && $image->isValid() && !$image->hasMoved()) {
            $imageName = $image->getRandomName();
            $image->move('uploads/categories', $imageName);
        }

        $this->categoryModel->save([
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'image'       => $imageName,
        ]);

        return redirect()->to('/category')->with('success', 'Category added successfully');
    }

    // 🟢 fungsi edit
    public function edit($id)
    {
        $category = $this->categoryModel->find($id);

        if (!$category) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Category with ID $id not found");
        }

        $data['category'] = $category;

        return view('category/edit', $data);
    }

    // 🟢 fungsi update
    public function update($id)
    {
        $category = $this->categoryModel->find($id);

        if (!$category) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Category with ID $id not found");
        }

        $image = $this->request->getFile('image');
        $imageName = $category['image']; // default tetap pakai gambar lama

        if ($image && $image->isValid() && !$image->hasMoved()) {
            // hapus gambar lama kalau ada
            if ($imageName && file_exists('uploads/categories/' . $imageName)) {
                unlink('uploads/categories/' . $imageName);
            }

            $imageName = $image->getRandomName();
            $image->move('uploads/categories', $imageName);
        }

        $this->categoryModel->update($id, [
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'image'       => $imageName,
        ]);

        return redirect()->to('/category')->with('success', 'Category updated successfully');
    }

    // hapus data
    public function delete($id)
    {
        $category = $this->categoryModel->find($id);

        if ($category) {
            if ($category['image'] && file_exists('uploads/categories/' . $category['image'])) {
                unlink('uploads/categories/' . $category['image']);
            }
            $this->categoryModel->delete($id);
        }

        return redirect()->to('/category')->with('success', 'Category deleted successfully');
    }
}
