<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSessionSchedulesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'session_id' => ['type' => 'INT', 'unsigned' => true],
            'schedule_date' => ['type' => 'DATE', 'null' => false],
            'start_time'    => ['type' => 'TIME', 'null' => false],
            'end_time'      => ['type' => 'TIME', 'null' => false],
            'location'   => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('session_id');

        $this->forge->addForeignKey('session_id', 'class_sessions', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('session_schedules');
    }

    public function down()
    {
        $this->forge->dropTable('session_schedules');
    }
}
