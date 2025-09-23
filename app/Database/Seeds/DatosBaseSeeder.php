<?php namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatosBaseSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        // OPCIONAL: limpiar en orden (para no romper FKs)
        $db->query('SET FOREIGN_KEY_CHECKS=0');
        $db->table('inscripcion')->truncate();
        $db->table('curso')->truncate();
        $db->table('alumno')->truncate();
        $db->table('profesor')->truncate();
        $db->table('carrera')->truncate();
        $db->query('SET FOREIGN_KEY_CHECKS=1');

        // 1) Padres primero
        $db->table('carrera')->insertBatch([
          ['nombre'=>'Ciencia de Datos','codigo'=>'CD'],
          ['nombre'=>'Desarrollo de Software','codigo'=>'DS'],
        ]);

        $db->table('profesor')->insert(['nombre'=>'Ana Pérez','email'=>'ana@uni.edu']);

        // 2) Luego alumnos y cursos que referencian a padres
        $db->table('alumno')->insert([
          'dni'=>'40000001','nombre'=>'Juan Dufour','email'=>'juan@uni.edu',
          'fecha_nac'=>'2000-01-15','id_carrera'=>1
        ]);

        $db->table('curso')->insert([
          'nombre'=>'BD1','codigo'=>'BD1','id_carrera'=>1,'id_profesor'=>1
        ]);

        // 3) Finalmente inscripciones (hijas de alumno/curso)
        $db->table('inscripcion')->insert([
          'id_alumno'=>1,'id_curso'=>1,'fecha'=>date('Y-m-d'),'nota_final'=>8.5
        ]);
    }
}


// app/Database/Seeds/CarreraSeeder.php
namespace App\Database\Seeds;
use CodeIgniter\Database\Seeder;

class CarreraSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('carreras')->insertBatch([
            ['nombre'=>'Ingeniería en Sistemas','descripcion'=>'Plan 2025'],
            ['nombre'=>'Psicología','descripcion'=>null],
        ]);
    }
}
