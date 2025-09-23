<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTablaProfesor extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_profesor' => ['type'=>'BIGINT','unsigned'=>true,'auto_increment'=>true],
            'nombre'      => ['type'=>'VARCHAR','constraint'=>120],
            'email'       => ['type'=>'VARCHAR','constraint'=>120,'null'=>true],
        ]);
        $this->forge->addKey('id_profesor', true);
        $this->forge->createTable('profesor', true, ['ENGINE'=>'InnoDB']);
    }
    public function down()
    {
        $this->forge->dropTable('profesor', true);
    }
}
