<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDiscountsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'class_id'        => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'code'            => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
            'type'            => ['type' => 'ENUM', 'constraint' => ['percentage', 'fixed']],  
            'value'           => ['type' => 'DECIMAL', 'constraint' => '10,2'], 
            'min_participants' => ['type' => 'INT', 'unsigned' => true, 'default' => 2],
            'max_usage'       => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'usage_count'     => ['type' => 'INT', 'unsigned' => true, 'default' => 0],
            'starts_at'       => ['type' => 'DATETIME', 'null' => true],
            'ends_at'         => ['type' => 'DATETIME', 'null' => true],
            'is_active'       => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('class_id', 'classes', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('discounts');
    }

    public function down()
    {
        $this->forge->dropTable('discounts');
    }
}
