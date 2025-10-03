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
        // 1) Ambil semua sesi utk kelas aktif (pakai * + alias aman)
        $rows = $this->classSessionModel
            ->select('
            class_sessions.*,
            classes.*,
            class_sessions.id AS session_id,
            classes.id        AS class_id,
            classes.title     AS class_title,
            classes.image     AS class_image,
            classes.price     AS class_price
        ')
            ->join('classes', 'classes.id = class_sessions.class_id', 'inner')
            ->where('classes.is_active', 1)
            ->orderBy('class_sessions.schedule_date', 'ASC')
            ->orderBy('class_sessions.start_time', 'ASC')
            ->findAll();

        // 2) Group per class + hitung total sesi
        $byClass = [];       // [class_id => list of sessions[]]
        $sessionCounts = []; // [class_id => total]
        foreach ($rows as $r) {
            $cid = (int) $r['class_id'];
            $byClass[$cid][] = $r;
            $sessionCounts[$cid] = ($sessionCounts[$cid] ?? 0) + 1;
        }

        // 3) Ambil semua class aktif (untuk kartu tanpa sesi)
        $activeClasses = $this->classModel
            ->select('id, title AS class_title, image AS class_image, price AS class_price')
            ->where('is_active', 1)
            ->findAll();

        $today = date('Y-m-d');
        $now   = date('H:i:s');

        // 4) Susun cards: 1 kartu per class (upcoming terdekat; kalau tidak ada, pakai paling awal; kalau kosong, kartu tanpa sesi)
        $cards = [];
        foreach ($activeClasses as $c) {
            $cid = (int) $c['id'];
            $sessions = $byClass[$cid] ?? [];

            $chosen = null;
            if (!empty($sessions)) {
                foreach ($sessions as $s) {
                    $isFutureDay   = ($s['schedule_date'] > $today);
                    $isTodayFuture = ($s['schedule_date'] === $today && $s['start_time'] >= $now);
                    if ($isFutureDay || $isTodayFuture) {
                        $chosen = $s;
                        break;
                    }
                }
                if (!$chosen) {
                    $chosen = $sessions[0];
                }
            }

            if ($chosen) {
                // Normalisasi + gambar fallback
                $chosen['capacity']         = (int) ($chosen['capacity'] ?? 0); // dari class_sessions.*
                $chosen['class_price']      = (float) ($chosen['class_price'] ?? 0); // dari classes.*
                $chosen['class_image_url'] = !empty($chosen['class_image'])
                    ? base_url('uploads/classes/' . $chosen['class_image'])
                    : base_url('images/placeholder.jpg');
                $chosen['session_image_url'] = !empty($chosen['session_image'])
                    ? base_url('uploads/classes/' . $chosen['session_image']) // jika session image juga disimpan di folder yang sama
                    : $chosen['class_image_url'];
                // Status badge
                $startTs = strtotime($chosen['schedule_date'] . ' ' . $chosen['start_time']);
                $endTs   = strtotime($chosen['schedule_date'] . ' ' . $chosen['end_time']);
                $nowTs   = time();
                $chosen['status_badge'] =
                    ($chosen['status'] === 'Cancelled') ? 'Cancelled'
                    : (($startTs <= $nowTs && $nowTs <= $endTs) ? 'Ongoing'
                        : (($nowTs < $startTs) ? 'Upcoming' : 'Finished'));

                // Formatting
                $chosen['date_fmt']  = date('d M Y', strtotime($chosen['schedule_date']));
                $chosen['time_fmt']  = date('H:i', strtotime($chosen['start_time'])) . '–' . date('H:i', strtotime($chosen['end_time']));
                $chosen['price_fmt'] = 'Rp ' . number_format($chosen['class_price'], 0, ',', '.');

                // Total sesi per class
                $chosen['total_sessions'] = $sessionCounts[$cid] ?? 0;

                $cards[] = $chosen;
            } else {
                // Kartu tanpa sesi
                $price = (float) ($c['class_price'] ?? 0);
                $cards[] = [
                    'session_id'        => null,
                    'class_id'          => $cid,
                    'class_title'       => $c['class_title'],
                    'class_image'       => $c['class_image'],
                    'class_image_url' => !empty($c['class_image'])
                        ? base_url('uploads/classes/' . $c['class_image'])
                        : base_url('images/placeholder.jpg'),
                    'class_price'       => $price,
                    'price_fmt'         => 'Rp ' . number_format($price, 0, ',', '.'),
                    'name'              => null,  // session name (tidak ada sesi)
                    'schedule_date'     => null,
                    'start_time'        => null,
                    'end_time'          => null,
                    'location'          => null,
                    'status'            => null,
                    'status_badge'      => 'No sessions',
                    'total_sessions'    => 0,
                ];
            }
        }

        // 5) Urutkan: yang punya sesi (tanggal/time terdekat) dulu, lalu yang tanpa sesi
        usort($cards, function ($a, $b) {
            $aHas = !empty($a['schedule_date']);
            $bHas = !empty($b['schedule_date']);
            if ($aHas && $bHas) {
                $cmp = strcmp($a['schedule_date'], $b['schedule_date']);
                if ($cmp === 0) return strcmp($a['start_time'], $b['start_time']);
                return $cmp;
            }
            return $aHas ? -1 : ($bHas ? 1 : 0);
        });

        return view('shop/class_sessions', [
            'cards'         => $cards,
            'sessionCounts' => $sessionCounts,
        ]);
    }
}
