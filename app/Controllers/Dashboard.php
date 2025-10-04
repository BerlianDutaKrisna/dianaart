<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\ClassModel;
use App\Models\ClassSessionModel;
use App\Models\RegistrationModel;
use App\Models\ClassProposalModel;

class Dashboard extends BaseController
{
    protected $userModel;
    protected $classModel;
    protected $sessionModel;
    protected $registrationModel;
    protected $proposalModel;

    public function __construct()
    {
        $this->userModel         = new UserModel();
        $this->classModel        = new ClassModel();
        $this->sessionModel      = new ClassSessionModel();
        $this->registrationModel = new RegistrationModel();
        $this->proposalModel     = new ClassProposalModel();
    }

    public function index()
    {
        $db = \Config\Database::connect();

        // Users (langsung)
        $users = $this->userModel->orderBy('created_at', 'DESC')->findAll();

        // Classes (langsung)
        $classes = $this->classModel->orderBy('created_at', 'DESC')->findAll();

        // Class Sessions + join classes (untuk tampilkan judul kelas)
        $sessions = $db->table('class_sessions cs')
            ->select("
                cs.*,
                c.title AS class_title,
                c.price AS class_price
            ")
            ->join('classes c', 'c.id = cs.class_id', 'left')
            ->orderBy('cs.schedule_date', 'DESC')
            ->get()
            ->getResultArray();

        // Registrations + join users + sessions + classes
        $registrations = $db->table('registrations r')
            ->select("
                r.*,
                u.name  AS user_name,
                u.email AS user_email,
                cs.name AS session_name,
                cs.schedule_date AS session_date,
                cs.start_time    AS session_start,
                cs.end_time      AS session_end,
                cs.location      AS session_location,
                c.title          AS class_title,
                c.price          AS class_price
            ")
            ->join('users u',          'u.id  = r.user_id',       'left')
            ->join('class_sessions cs', 'cs.id = r.session_id',     'left')
            ->join('classes c',        'c.id  = cs.class_id',      'left')
            ->orderBy('r.created_at', 'DESC')
            ->get()
            ->getResultArray();

        // Class Proposals + join users
        $proposals = $db->table('class_proposals cp')
            ->select("
                cp.*,
                u.name  AS user_name,
                u.email AS user_email
            ")
            ->join('users u', 'u.id = cp.user_id', 'left')
            ->orderBy('cp.created_at', 'DESC')
            ->get()
            ->getResultArray();

        $data = [
            'title'         => 'Data Overview',
            'users'         => $users,
            'classes'       => $classes,
            'sessions'      => $sessions,
            'registrations' => $registrations,
            'proposals'     => $proposals,
        ];

        return view('dashboard/index', $data);
    }
}
