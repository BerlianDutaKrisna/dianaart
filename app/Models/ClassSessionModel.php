<?php

namespace App\Models;

use CodeIgniter\Model;

class ClassSessionModel extends Model
{
    protected $table            = 'class_sessions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'class_id',
        'name',
        'description',
        'level',
        'capacity',
        'schedule_date',
        'start_time',
        'end_time',
        'location',
        'status',
    ];

    // timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'class_id'      => 'required|is_natural_no_zero',
        'name'          => 'required|max_length[100]',
        'description'   => 'permit_empty',
        'level'         => 'permit_empty|max_length[255]',
        'capacity'      => 'permit_empty|is_natural',
        'schedule_date' => 'required|valid_date',
        'start_time'    => 'required|regex_match[/^\d{2}:\d{2}(:\d{2})?$/]',
        'end_time'      => 'required|regex_match[/^\d{2}:\d{2}(:\d{2})?$/]',
        'location'      => 'permit_empty|max_length[150]',
        'status'        => 'permit_empty|max_length[255]',
    ];
}
