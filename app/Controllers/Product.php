<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\CategoryModel;

class Product extends BaseController
{
    protected $productModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Products',
            'products' => $this->productModel
                ->select('products.*, categories.name as category_name')
                ->join('categories', 'categories.id = products.category_id')
                ->findAll()
        ];

        return view('products/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Create Product',
            'categories' => $this->categoryModel->findAll(),
            'validation' => \Config\Services::validation()
        ];

        return view('products/create', $data);
    }

    public function store()
    {
        if (!$this->validate([
            'name'        => 'required|min_length[3]',
            'flower_type' => 'required',
            'flower_color' => 'required',
            'price'       => 'required|decimal',
            'stock'       => 'required|integer',
            'image'       => 'uploaded[image]|is_image[image]|max_size[image,2048]'
        ])) {
            return redirect()->back()->withInput();
        }

        $file = $this->request->getFile('image');
        $fileName = $file->getRandomName();
        $file->move(ROOTPATH . 'public/uploads/products', $fileName);

        $this->productModel->save([
            'category_id' => $this->request->getPost('category_id'),
            'name'        => $this->request->getPost('name'),
            'flower_type' => $this->request->getPost('flower_type'),
            'flower_color' => $this->request->getPost('flower_color'),
            'description' => $this->request->getPost('description'),
            'price'       => $this->request->getPost('price'),
            'stock'       => $this->request->getPost('stock'),
            'image'       => $fileName
        ]);

        return redirect()->to('/products')->with('success', 'Product added successfully.');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Product',
            'product' => $this->productModel->find($id),
            'categories' => $this->categoryModel->findAll(),
            'validation' => \Config\Services::validation()
        ];

        return view('products/edit', $data);
    }

    public function update($id)
    {
        $rules = [
            'name'        => 'required|min_length[3]',
            'flower_type' => 'required',
            'flower_color' => 'required',
            'price'       => 'required|decimal',
            'stock'       => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput();
        }

        $data = [
            'category_id' => $this->request->getPost('category_id'),
            'name'        => $this->request->getPost('name'),
            'flower_type' => $this->request->getPost('flower_type'),
            'flower_color' => $this->request->getPost('flower_color'),
            'description' => $this->request->getPost('description'),
            'price'       => $this->request->getPost('price'),
            'stock'       => $this->request->getPost('stock'),
        ];

        $file = $this->request->getFile('image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $fileName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads/products', $fileName);
            $data['image'] = $fileName;
        }

        $this->productModel->update($id, $data);

        return redirect()->to('/products')->with('success', 'Product updated successfully.');
    }

    public function delete($id)
    {
        $product = $this->productModel->find($id);
        if ($product && $product['image'] && file_exists(ROOTPATH . 'public/uploads/products/' . $product['image'])) {
            unlink(ROOTPATH . 'public/uploads/products/' . $product['image']);
        }

        $this->productModel->delete($id);
        return redirect()->to('/products')->with('success', 'Product deleted successfully.');
    }

    public function show($id)
    {
        $data = [
            'title' => 'Product Detail',
            'product' => $this->productModel
                ->select('products.*, categories.name as category_name')
                ->join('categories', 'categories.id = products.category_id')
                ->find($id)
        ];

        return view('products/show', $data);
    }
}
