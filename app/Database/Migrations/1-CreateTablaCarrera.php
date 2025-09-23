<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTablaCarrera extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_carrera' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => 120,
            ],
            'codigo' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id_carrera', true);
        $this->forge->createTable('carrera', true); // ⚠️ singular
    }

    public function down()
    {
        $this->forge->dropTable('carrera', true);
    }
}
