<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table      = 'products';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'category_id',
        'name',
        'flower_type',
        'flower_color',
        'description',
        'price',
        'stock',
        'image'
    ];
    protected $useTimestamps = true;

    public function getFilteredProducts($filters = [])
    {
        $builder = $this->builder();

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

        return $builder->get()->getResultArray();
    }
}
