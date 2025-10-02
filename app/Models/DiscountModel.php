<?php

namespace App\Models;

use CodeIgniter\Model;

class DiscountModel extends Model
{
    protected $table            = 'discounts';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $allowedFields = [
        'class_id',
        'code',
        'type',
        'value',
        'min_participants',
        'max_usage',
        'usage_count',
        'starts_at',
        'ends_at',
        'is_active',
    ];

    // Validasi dasar
    protected $validationRules = [
        'code'             => 'required|max_length[50]',
        'type'             => 'required|in_list[percentage,fixed]',
        'value'            => 'required|decimal',
        'min_participants' => 'permit_empty|is_natural_no_zero',
        'max_usage'        => 'permit_empty|is_natural',
        'usage_count'      => 'permit_empty|is_natural',
        'is_active'        => 'in_list[0,1]',
        'class_id'         => 'permit_empty|is_natural_no_zero',
        'starts_at'        => 'permit_empty|valid_date[Y-m-d H:i:s]',
        'ends_at'          => 'permit_empty|valid_date[Y-m-d H:i:s]',
    ];
}
