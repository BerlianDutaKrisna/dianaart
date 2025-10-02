<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRegistrationsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'user_id'       => ['type' => 'INT', 'unsigned' => true],
            'session_id'    => ['type' => 'INT', 'unsigned' => true],
            'discount_id'   => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'quantity'      => ['type' => 'INT', 'unsigned' => true, 'default' => 1],
            'unit_price'    => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => true],
            'subtotal'      => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => true],
            'final_total'   => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => true],
            'status'        => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'registered_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['user_id', 'session_id']);
        $this->forge->addKey('session_id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('session_id', 'class_sessions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('discount_id', 'discounts', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('registrations');
    }

    public function down()
    {
        $this->forge->dropTable('registrations');
    }
}
