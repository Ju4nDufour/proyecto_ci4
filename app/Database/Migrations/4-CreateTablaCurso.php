<?php
use CodeIgniter\Database\Migration;

class CreateCursos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],
            'carrera_id'  => ['type'=>'INT','constraint'=>11,'unsigned'=>true],
            'nombre'      => ['type'=>'VARCHAR','constraint'=>120],
            'anio'        => ['type'=>'INT','constraint'=>2,'null'=>true], // opcional
            'descripcion' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
            'created_at'  => ['type'=>'DATETIME','null'=>true],
            'updated_at'  => ['type'=>'DATETIME','null'=>true],
            'deleted_at'  => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('carrera_id', 'carrera', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('curso', true);
    }

    public function down()
    {
        $this->forge->dropTable('curso', true);
    }
}
