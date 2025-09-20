<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\ProductModel;

class Shop extends BaseController
{
    protected $categoryModel;
    protected $productModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
        $this->productModel  = new ProductModel();
    }

    // Halaman daftar kategori (untuk customer)
    public function categories()
    {
        $data = [
            'categories' => $this->categoryModel->findAll()
        ];

        return view('shop/categories', $data);
    }

    // Halaman daftar produk (untuk customer)
    public function products()
    {
        $data = [
            'products' => $this->productModel
                ->select('products.*, categories.name as category_name')
                ->join('categories', 'categories.id = products.category_id')
                ->findAll()
        ];

        return view('shop/products', $data);
    }
}
