<?php namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatosBaseSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        $db->transStart();

        // Dejar todo limpio (sin romper FKs)
        $db->query('SET FOREIGN_KEY_CHECKS = 0');
        $db->table('inscripcion')->truncate();
        $db->table('curso')->truncate();
        $db->table('alumno')->truncate();
        $db->table('profesor')->truncate();
        $db->table('carrera')->truncate();
        $db->query('SET FOREIGN_KEY_CHECKS = 1');

        // ===== CARRERA (<= 15 columnas) =====
        $db->table('carrera')->insertBatch([
            ['id_carrera'=>1, 'nombre'=>'Ciencia de Datos',           'codigo'=>'CD'],
            ['id_carrera'=>2, 'nombre'=>'Desarrollo de Software',     'codigo'=>'DS'],
            ['id_carrera'=>3, 'nombre'=>'Redes y Telecomunicaciones', 'codigo'=>'RT'],
            ['id_carrera'=>4, 'nombre'=>'Administración',             'codigo'=>'ADM'],
            ['id_carrera'=>5, 'nombre'=>'Marketing Digital',          'codigo'=>'MK'],
            ['id_carrera'=>6, 'nombre'=>'Salud y Enfermería',         'codigo'=>'SLD'],
        ]);

        // ===== PROFESOR =====
        $db->table('profesor')->insertBatch([
            ['id_profesor'=>1, 'nombre'=>'Ana Pérez',     'email'=>'ana.perez@uni.edu'],
            ['id_profesor'=>2, 'nombre'=>'Bruno Gómez',   'email'=>'bruno.gomez@uni.edu'],
            ['id_profesor'=>3, 'nombre'=>'Carla Torres',  'email'=>'carla.torres@uni.edu'],
            ['id_profesor'=>4, 'nombre'=>'Diego Ríos',    'email'=>'diego.rios@uni.edu'],
            ['id_profesor'=>5, 'nombre'=>'Elisa Martínez','email'=>'elisa.martinez@uni.edu'],
        ]);

        // ===== ALUMNO =====
        $db->table('alumno')->insertBatch([
            ['id_alumno'=>1,  'dni'=>'40000001', 'nombre'=>'Juan Dufour',     'email'=>'juan@uni.edu',    'fecha_nac'=>'2000-01-15', 'id_carrera'=>1],
            ['id_alumno'=>2,  'dni'=>'40000002', 'nombre'=>'María López',     'email'=>'maria@uni.edu',   'fecha_nac'=>'2001-03-22', 'id_carrera'=>2],
            ['id_alumno'=>3,  'dni'=>'40000003', 'nombre'=>'Pedro Sánchez',   'email'=>'pedro@uni.edu',   'fecha_nac'=>'1999-11-05', 'id_carrera'=>3],
            ['id_alumno'=>4,  'dni'=>'40000004', 'nombre'=>'Lucía Romero',    'email'=>'lucia@uni.edu',   'fecha_nac'=>'2002-07-18', 'id_carrera'=>1],
            ['id_alumno'=>5,  'dni'=>'40000005', 'nombre'=>'Nicolás Vera',    'email'=>'nicolas@uni.edu', 'fecha_nac'=>'2000-09-10', 'id_carrera'=>2],
            ['id_alumno'=>6,  'dni'=>'40000006', 'nombre'=>'Sofía Álvarez',   'email'=>'sofia@uni.edu',   'fecha_nac'=>'2001-12-01', 'id_carrera'=>4],
            ['id_alumno'=>7,  'dni'=>'40000007', 'nombre'=>'Tomás Herrera',   'email'=>'tomas@uni.edu',   'fecha_nac'=>'1998-02-28', 'id_carrera'=>5],
            ['id_alumno'=>8,  'dni'=>'40000008', 'nombre'=>'Valentina Cruz',  'email'=>'valen@uni.edu',   'fecha_nac'=>'2003-06-14', 'id_carrera'=>3],
            ['id_alumno'=>9,  'dni'=>'40000009', 'nombre'=>'Agustín Molina',  'email'=>'agustin@uni.edu', 'fecha_nac'=>'1999-04-03', 'id_carrera'=>6],
            ['id_alumno'=>10, 'dni'=>'40000010', 'nombre'=>'Brenda Ortiz',    'email'=>'brenda@uni.edu',  'fecha_nac'=>'2002-10-25', 'id_carrera'=>5],
        ]);

        // ===== CURSO =====
        $db->table('curso')->insertBatch([
            ['id_curso'=>1, 'nombre'=>'Bases de Datos I',     'codigo'=>'BD1',   'id_carrera'=>1, 'id_profesor'=>1],
            ['id_curso'=>2, 'nombre'=>'Algoritmos I',         'codigo'=>'ALG1',  'id_carrera'=>2, 'id_profesor'=>2],
            ['id_curso'=>3, 'nombre'=>'Redes I',              'codigo'=>'RED1',  'id_carrera'=>3, 'id_profesor'=>3],
            ['id_curso'=>4, 'nombre'=>'Contabilidad I',       'codigo'=>'CONT1', 'id_carrera'=>4, 'id_profesor'=>4],
            ['id_curso'=>5, 'nombre'=>'SEO y Analítica',      'codigo'=>'SEO1',  'id_carrera'=>5, 'id_profesor'=>5],
            ['id_curso'=>6, 'nombre'=>'Enfermería Básica',    'codigo'=>'ENF1',  'id_carrera'=>6, 'id_profesor'=>4],
            ['id_curso'=>7, 'nombre'=>'Programación I',       'codigo'=>'PRG1',  'id_carrera'=>2, 'id_profesor'=>1],
            ['id_curso'=>8, 'nombre'=>'Estadística Aplicada', 'codigo'=>'EST1',  'id_carrera'=>1, 'id_profesor'=>2],
        ]);

        // ===== INSCRIPCION =====
        $db->table('inscripcion')->insertBatch([
            ['id_inscripcion'=>1,  'id_alumno'=>1,  'id_curso'=>1, 'fecha'=>'2024-03-10', 'nota_final'=>8.50],
            ['id_inscripcion'=>2,  'id_alumno'=>2,  'id_curso'=>2, 'fecha'=>'2024-03-11', 'nota_final'=>7.00],
            ['id_inscripcion'=>3,  'id_alumno'=>3,  'id_curso'=>3, 'fecha'=>'2024-03-12', 'nota_final'=>9.00],
            ['id_inscripcion'=>4,  'id_alumno'=>4,  'id_curso'=>1, 'fecha'=>'2024-03-13', 'nota_final'=>6.50],
            ['id_inscripcion'=>5,  'id_alumno'=>5,  'id_curso'=>7, 'fecha'=>'2024-03-14', 'nota_final'=>8.00],
            ['id_inscripcion'=>6,  'id_alumno'=>6,  'id_curso'=>4, 'fecha'=>'2024-03-15', 'nota_final'=>7.50],
            ['id_inscripcion'=>7,  'id_alumno'=>7,  'id_curso'=>5, 'fecha'=>'2024-03-16', 'nota_final'=>6.00],
            ['id_inscripcion'=>8,  'id_alumno'=>8,  'id_curso'=>3, 'fecha'=>'2024-03-17', 'nota_final'=>9.50],
            ['id_inscripcion'=>9,  'id_alumno'=>9,  'id_curso'=>6, 'fecha'=>'2024-03-18', 'nota_final'=>8.20],
            ['id_inscripcion'=>10, 'id_alumno'=>10, 'id_curso'=>5, 'fecha'=>'2024-03-19', 'nota_final'=>7.80],
            ['id_inscripcion'=>11, 'id_alumno'=>2,  'id_curso'=>8, 'fecha'=>'2024-03-20', 'nota_final'=>6.90],
            ['id_inscripcion'=>12, 'id_alumno'=>1,  'id_curso'=>8, 'fecha'=>'2024-03-21', 'nota_final'=>8.10],
        ]);

        $db->transComplete();
    }
}
