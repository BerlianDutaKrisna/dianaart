<?php

namespace App\Models;

use CodeIgniter\Model;

class ClassModel extends Model
{
    protected $table            = 'classes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = ['title', 'description', 'price', 'image', 'is_active'];

    // timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // validation (tambahan sisi model, tetap validasi di controller juga)
    protected $validationRules = [
        'title'       => 'required|max_length[150]',
        'description' => 'permit_empty|max_length[255]',
        'price'       => 'permit_empty|decimal',
        'is_active'   => 'permit_empty|in_list[0,1]',
        'image'       => 'permit_empty',
    ];
}
