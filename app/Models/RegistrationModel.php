<?php

namespace App\Models;

use CodeIgniter\Model;

class RegistrationModel extends Model
{
    protected $table            = 'registrations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'user_id',
        'session_id',
        'discount_id',
        'quantity',
        'unit_price',
        'subtotal',
        'final_total',
        'status',
        'registered_at',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true; // otomatis isi created_at & updated_at
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
