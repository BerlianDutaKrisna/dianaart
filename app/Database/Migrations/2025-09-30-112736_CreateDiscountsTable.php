<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDiscountsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'code'            => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true], // e.g. GROUP2
            'type'            => ['type' => 'ENUM', 'constraint' => ['percentage', 'fixed']],  // percentage=%, fixed=nominal
            'value'           => ['type' => 'DECIMAL', 'constraint' => '10,2'],              // 10.00 => 10% atau 10.00 uang
            'min_participants' => ['type' => 'INT', 'unsigned' => true, 'default' => 2],      // minimal peserta agar diskon berlaku
            'max_usage'       => ['type' => 'INT', 'unsigned' => true, 'null' => true],      // NULL = tak dibatasi
            'usage_count'     => ['type' => 'INT', 'unsigned' => true, 'default' => 0],
            'class_id'        => ['type' => 'INT', 'unsigned' => true, 'null' => true],      // jika NULL, berlaku untuk semua class
            'starts_at'       => ['type' => 'DATETIME', 'null' => true],
            'ends_at'         => ['type' => 'DATETIME', 'null' => true],
            'is_active'       => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('discounts');
    }

    public function down()
    {
        $this->forge->dropTable('discounts');
    }
}
