<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserIdToAlumno extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('alumno', [
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'id_alumno',
            ],
        ]);

        $this->db->query(
            'ALTER TABLE alumno ADD CONSTRAINT alumno_user_id_fk FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL'
        );

        $this->db->query('CREATE UNIQUE INDEX alumno_user_id_unique ON alumno(user_id)');
    }

    public function down(): void
    {
        $this->db->query('ALTER TABLE alumno DROP FOREIGN KEY alumno_user_id_fk');
        $this->db->query('DROP INDEX alumno_user_id_unique ON alumno');

        $this->forge->dropColumn('alumno', 'user_id');
    }
}

