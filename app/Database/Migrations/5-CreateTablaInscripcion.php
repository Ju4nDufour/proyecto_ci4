<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTablaInscripcion extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_inscripcion' => ['type'=>'BIGINT','unsigned'=>true,'auto_increment'=>true],
            'id_alumno'      => ['type'=>'BIGINT','unsigned'=>true],
            'id_curso'       => ['type'=>'BIGINT','unsigned'=>true],
            'fecha'          => ['type'=>'DATE'],
            'nota_final'     => ['type'=>'DECIMAL','constraint'=>'4,2','null'=>true],
        ]);
        $this->forge->addKey('id_inscripcion', true);
        $this->forge->addUniqueKey(['id_alumno','id_curso']); // evita doble inscripción
        $this->forge->addForeignKey('id_alumno','alumno','id_alumno','RESTRICT','CASCADE');
        $this->forge->addForeignKey('id_curso','curso','id_curso','RESTRICT','CASCADE');
        $this->forge->createTable('inscripcion', true, ['ENGINE'=>'InnoDB']);
    }
    public function down()
    {
        $this->forge->dropTable('inscripcion', true);
    }
}
