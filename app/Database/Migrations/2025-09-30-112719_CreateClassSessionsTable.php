<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateClassSessionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'class_id'    => ['type' => 'INT', 'unsigned' => true],
            'name'        => ['type' => 'VARCHAR', 'constraint' => 100],
            'description' => ['type' => 'TEXT', 'null' => true],
            'level'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'capacity'    => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'schedule_date' => ['type' => 'DATE', 'null' => false],
            'start_time'    => ['type' => 'TIME', 'null' => false],
            'end_time'      => ['type' => 'TIME', 'null' => false],
            'location'   => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'status'      => ['type' => 'VARCHAR ', 'constraint' => 255, 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('class_id');
        $this->forge->addForeignKey('class_id', 'classes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('class_sessions');
    }

    public function down()
    {
        $this->forge->dropTable('class_sessions');
    }
}
