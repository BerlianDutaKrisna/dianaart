<?php

namespace App\Models;

use CodeIgniter\Model;

class ClassProposalModel extends Model
{
    protected $table            = 'class_proposals';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields = [
        'user_id',
        'title',
        'description',
        'price',
        'image',
        'schedule_date',
        'start_time',
        'end_time',
        'location', 
        'status',
        'is_active',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
