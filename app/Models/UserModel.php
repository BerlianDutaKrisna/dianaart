<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table         = 'users';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['name', 'email', 'password', 'phone', 'birth_date', 'role']; // tak perlu created_at/updated_at di sini
    protected $useTimestamps = true; // butuh kolom created_at & updated_at di tabel users

    // app/Models/UserModel.php

    public function getSessionOrders(int $userId): array
    {
        return $this->db->table('registrations r')
            ->select("
            r.id AS order_id, 'session' AS source_type, r.user_id,
            c.title AS class_title, cs.name AS session_name,
            cs.schedule_date AS date, cs.start_time, cs.end_time, cs.location,
            r.created_at AS ordered_at
        ")
            ->join('class_sessions cs', 'cs.id = r.session_id')
            ->join('classes c', 'c.id = cs.class_id')
            ->where('r.user_id', $userId)
            ->orderBy('r.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getProposals(int $userId): array
    {
        return $this->db->table('class_proposals cp')
            ->select("
            cp.id AS order_id, 'proposal' AS source_type, cp.user_id,
            cp.title AS class_title, NULL AS session_name,
            cp.schedule_date AS date, cp.start_time, cp.end_time, cp.location,
            cp.created_at AS ordered_at
        ")
            ->where('cp.user_id', $userId)
            ->orderBy('cp.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getAllOrders(int $userId): array
    {
        $q1 = $this->db->table('registrations r')
            ->select("
            r.id AS order_id, 'session' AS source_type, r.user_id,
            c.title AS class_title, cs.name AS session_name,
            cs.schedule_date AS date, cs.start_time, cs.end_time, cs.location,
            r.created_at AS ordered_at
        ")
            ->join('class_sessions cs', 'cs.id = r.session_id')
            ->join('classes c', 'c.id = cs.class_id')
            ->where('r.user_id', $userId)
            ->getCompiledSelect();

        $q2 = $this->db->table('class_proposals cp')
            ->select("
            cp.id AS order_id, 'proposal' AS source_type, cp.user_id,
            cp.title AS class_title, NULL AS session_name,
            cp.schedule_date AS date, cp.start_time, cp.end_time, cp.location,
            cp.created_at AS ordered_at
        ")
            ->where('cp.user_id', $userId)
            ->getCompiledSelect();

        $sql = "($q1) UNION ALL ($q2) ORDER BY ordered_at DESC";
        return $this->db->query($sql)->getResultArray();
    }
}
