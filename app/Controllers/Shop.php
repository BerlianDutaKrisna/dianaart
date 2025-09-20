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
        $filters = [
            'flower_type'  => $this->request->getGet('flower_type'),
            'flower_color' => $this->request->getGet('flower_color'),
            'min_price'    => $this->request->getGet('min_price'),
            'max_price'    => $this->request->getGet('max_price'),
        ];

        $builder = $this->productModel
            ->select('products.*, categories.name as category_name')
            ->join('categories', 'categories.id = products.category_id');

        if (!empty($filters['flower_type'])) {
            $builder->where('flower_type', $filters['flower_type']);
        }

        if (!empty($filters['flower_color'])) {
            $builder->where('flower_color', $filters['flower_color']);
        }

        if (!empty($filters['min_price'])) {
            $builder->where('price >=', $filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $builder->where('price <=', $filters['max_price']);
        }

        $products = $builder->findAll();

        return view('shop/products', ['products' => $products]);
    }
}
