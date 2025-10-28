<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserIdToProfesor extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('profesor', [
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'id_profesor',
            ],
        ]);

        // Cada profesor debe vincularse, opcionalmente, con un usuario de Shield.
        $this->db->query(
            'ALTER TABLE profesor ADD CONSTRAINT profesor_user_id_fk FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL'
        );

        $this->db->query('CREATE UNIQUE INDEX profesor_user_id_unique ON profesor(user_id)');
    }

    public function down(): void
    {
        $this->db->query('ALTER TABLE profesor DROP FOREIGN KEY profesor_user_id_fk');
        $this->db->query('DROP INDEX profesor_user_id_unique ON profesor');

        $this->forge->dropColumn('profesor', 'user_id');
    }
}

