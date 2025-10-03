<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateClassProposalsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'user_id'       => ['type' => 'INT', 'unsigned' => true],
            'title'         => ['type' => 'VARCHAR', 'constraint' => 150],
            'description'   => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'price'         => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00],
            'image'         => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'schedule_date' => ['type' => 'DATE', 'null' => true],
            'start_time'    => ['type' => 'TIME', 'null' => true],
            'end_time'      => ['type' => 'TIME', 'null' => true],
            'location'      => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'status'        => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'pending'],
            'is_active'     => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('class_proposals');
    }

    public function down()
    {
        $this->forge->dropTable('class_proposals');
    }
}
