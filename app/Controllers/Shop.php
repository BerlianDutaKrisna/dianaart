<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\ProductModel;
use App\Models\ClassModel;
use App\Models\ClassSessionModel;

class Shop extends BaseController
{
    protected $categoryModel;
    protected $productModel;

    // tambahkan properti ini
    protected $classModel;
    protected $classSessionModel;

    public function __construct()
    {
        $this->categoryModel     = new CategoryModel();
        $this->productModel      = new ProductModel();

        // inisialisasi model kelas & sesi
        $this->classModel        = new ClassModel();
        $this->classSessionModel = new ClassSessionModel();
        helper('text');
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

    // ===============================
    // Halaman Class Sessions (customer)
    // ===============================
    public function classSessions()
    {
        // Ambil semua sesi yang terkait class aktif (is_active = 1)
        $rows = $this->classSessionModel
            ->select('
                class_sessions.*,
                classes.id   AS class_id,
                classes.title AS class_title,
                classes.image AS class_image
            ')
            ->join('classes', 'classes.id = class_sessions.class_id', 'inner')
            ->where('classes.is_active', 1)
            ->orderBy('class_sessions.schedule_date', 'ASC')
            ->orderBy('class_sessions.start_time', 'ASC')
            ->findAll();

        // Ambil hanya "sesi terdekat" per class (untuk jadi 1 kartu / koleksi)
        $collections = [];          // [class_id => first upcoming session row]
        $sessionCounts = [];        // [class_id => total session count]
        foreach ($rows as $r) {
            $cid = (int) $r['class_id'];
            if (!isset($collections[$cid])) {
                $collections[$cid] = $r; // simpan sesi pertama (karena sudah diorder ASC)
            }
            $sessionCounts[$cid] = ($sessionCounts[$cid] ?? 0) + 1;
        }

        // Jika belum ada sesi tapi class aktif ada, tetap tampilkan class-nya (tanpa sesi)
        $activeClasses = $this->classModel->select('id, title, image')
            ->where('is_active', 1)->findAll();

        // pastikan semua class aktif minimal muncul 1 kartu (meski tanpa sesi)
        foreach ($activeClasses as $c) {
            $cid = (int) $c['id'];
            if (!isset($collections[$cid])) {
                $collections[$cid] = [
                    'class_id'       => $cid,
                    'class_title'    => $c['title'],
                    'class_image'    => $c['image'],
                    'name'           => null,
                    'schedule_date'  => null,
                    'start_time'     => null,
                    'end_time'       => null,
                    'location'       => null,
                    'status'         => null,
                ];
                $sessionCounts[$cid] = 0;
            }
        }

        // Ubah menjadi array terindeks untuk mudah di-loop di view
        $cards = array_values($collections);

        return view('shop/class_sessions', [
            'cards'         => $cards,
            'sessionCounts' => $sessionCounts,
        ]);
    }
}
