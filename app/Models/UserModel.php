<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table         = 'users';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['name', 'email', 'password', 'phone', 'birth_date', 'role']; // tak perlu created_at/updated_at di sini
    protected $useTimestamps = true; // butuh kolom created_at & updated_at di tabel users

    public function getAllOrders(int $userId): array
    {
        // Registrations (sesi kelas)
        $q1 = $this->db->table('registrations r')
            ->select("
                r.id                 AS order_id,
                'session'            AS source_type,
                r.user_id            AS user_id,
                c.id                 AS class_id,
                c.title              AS class_title,
                cs.id                AS session_id,
                cs.name              AS session_name,
                cs.schedule_date     AS date,
                cs.start_time        AS start_time,
                cs.end_time          AS end_time,
                cs.location          AS location,
                r.status             AS order_status,
                r.created_at         AS ordered_at
            ")
            ->join('class_sessions cs', 'cs.id = r.session_id', 'inner')
            ->join('classes c',       'c.id = cs.class_id',   'inner')
            ->where('r.user_id', $userId)
            ->getCompiledSelect();

        // Proposals (usulan kelas)
        $q2 = $this->db->table('class_proposals cp')
            ->select("
                cp.id                AS order_id,
                'proposal'           AS source_type,
                cp.user_id           AS user_id,
                NULL                 AS class_id,
                cp.title             AS class_title,
                NULL                 AS session_id,
                NULL                 AS session_name,
                cp.schedule_date     AS date,
                cp.start_time        AS start_time,
                cp.end_time          AS end_time,
                cp.location          AS location,
                cp.status            AS order_status,
                cp.created_at        AS ordered_at
            ")
            ->where('cp.user_id', $userId)
            ->getCompiledSelect();

        $sql = "($q1) UNION ALL ($q2) ORDER BY ordered_at DESC";
        return $this->db->query($sql)->getResultArray();
    }
}
