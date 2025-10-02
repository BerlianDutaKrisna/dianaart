<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DiscountModel;
use App\Models\ClassModel;

class Discounts extends BaseController
{
    protected $discountModel;
    protected $classModel;
    protected $helpers = ['form'];

    public function __construct()
    {
        $this->discountModel = new DiscountModel();
        $this->classModel    = class_exists(ClassModel::class) ? new ClassModel() : null;
    }

    public function index()
    {
        $q = $this->request->getGet('q');
        $builder = $this->discountModel->orderBy('id', 'DESC');
        if ($q) {
            $builder->groupStart()->like('code', $q)->orLike('type', $q)->groupEnd();
        }

        return view('discounts/index', [
            'title'     => 'Daftar Discount',
            'q'         => $q,
            'discounts' => $builder->paginate(10),
            'pager'     => $this->discountModel->pager,
        ]);
    }

    public function show($id)
    {
        $d = $this->discountModel->find($id);
        if (!$d) return redirect()->to('/discounts')->with('error', 'Data tidak ditemukan');

        return view('discounts/show', [
            'title'    => 'Detail Discount',
            'discount' => $d,
            'class'    => $this->classModel ? $this->classModel->find($d['class_id']) : null,
        ]);
    }

    public function create()
    {
        return view('discounts/create', [
            'title'      => 'Tambah Discount',
            'classes'    => $this->classModel ? $this->classModel->findAll() : [],
            'validation' => \Config\Services::validation(),
        ]);
    }

    public function store()
    {
        $data = $this->sanitize();

        // Validasi unik kode secara manual (selain rules model)
        if ($this->discountModel->where('code', $data['code'])->countAllResults()) {
            return redirect()->back()->withInput()->with('error', 'Kode sudah digunakan.');
        }

        if (!$this->discountModel->save($data)) {
            return redirect()->back()->withInput()->with('errors', $this->discountModel->errors());
        }

        return redirect()->to('/discounts')->with('success', 'Discount ditambahkan.');
    }

    public function edit($id)
    {
        $d = $this->discountModel->find($id);
        if (!$d) return redirect()->to('/discounts')->with('error', 'Data tidak ditemukan');

        return view('discounts/edit', [
            'title'      => 'Edit Discount',
            'discount'   => $d,
            'classes'    => $this->classModel ? $this->classModel->findAll() : [],
            'validation' => \Config\Services::validation(),
        ]);
    }

    public function update($id)
    {
        if (!$this->discountModel->find($id)) {
            return redirect()->to('/discounts')->with('error', 'Data tidak ditemukan');
        }

        $data = $this->sanitize();

        $exists = $this->discountModel->where('code', $data['code'])->where('id !=', $id)->first();
        if ($exists) {
            return redirect()->back()->withInput()->with('error', 'Kode sudah dipakai kupon lain.');
        }

        if (!$this->discountModel->update($id, $data)) {
            return redirect()->back()->withInput()->with('errors', $this->discountModel->errors());
        }

        return redirect()->to('/discounts')->with('success', 'Discount diperbarui.');
    }

    public function delete($id)
    {
        if (!$this->discountModel->find($id)) {
            return redirect()->to('/discounts')->with('error', 'Data tidak ditemukan');
        }

        $this->discountModel->delete($id);
        return redirect()->to('/discounts')->with('success', 'Discount dihapus.');
    }

    // === Helpers ===
    protected function sanitize(): array
    {
        $in = $this->request->getPost([
            'class_id',
            'code',
            'type',
            'value',
            'min_participants',
            'max_usage',
            'usage_count',
            'starts_at',
            'ends_at',
            'is_active'
        ]);

        $in['is_active'] = isset($in['is_active']) ? 1 : 0;
        foreach (['class_id', 'max_usage'] as $n) {
            $in[$n] = ($in[$n] === '' ? null : $in[$n]);
        }
        foreach (['min_participants', 'usage_count'] as $n) {
            if ($in[$n] === '') $in[$n] = null;
        }
        foreach (['starts_at', 'ends_at'] as $dt) {
            if (!empty($in[$dt])) {
                // from input datetime-local: YYYY-MM-DDTHH:MM -> YYYY-MM-DD HH:MM:00
                $in[$dt] = str_replace('T', ' ', $in[$dt]) . (strlen($in[$dt]) === 16 ? ':00' : '');
            } else {
                $in[$dt] = null;
            }
        }
        return $in;
    }
}
