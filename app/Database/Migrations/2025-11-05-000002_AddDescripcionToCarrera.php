<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDescripcionToCarrera extends Migration
{
    public function up()
    {
        $fields = [
            'descripcion' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'codigo',
            ],
        ];

        $this->forge->addColumn('carrera', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('carrera', 'descripcion');
    }
}

