<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTablaAlumno extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_alumno'  => ['type'=>'BIGINT','unsigned'=>true,'auto_increment'=>true],
            'dni'        => ['type'=>'CHAR','constraint'=>8],
            'nombre'     => ['type'=>'VARCHAR','constraint'=>120],
            'email'      => ['type'=>'VARCHAR','constraint'=>120,'null'=>true],
            'fecha_nac'  => ['type'=>'DATE','null'=>true],
            'id_carrera' => ['type'=>'BIGINT','unsigned'=>true],
        ]);
        $this->forge->addKey('id_alumno', true);
        $this->forge->addUniqueKey('dni');
        $this->forge->addForeignKey('id_carrera','carrera','id_carrera','RESTRICT','CASCADE');
        $this->forge->createTable('alumno', true, ['ENGINE'=>'InnoDB']);
    }
    public function down()
    {
        $this->forge->dropTable('alumno', true);
    }
}
