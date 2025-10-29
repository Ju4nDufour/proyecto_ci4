<?php
namespace App\Models;

use CodeIgniter\Model;

class AlumnoCursoModel extends Model
{
    protected $table = 'alumno_curso';
    protected $primaryKey = 'id_alumno_curso';
    protected $returnType = 'array';
    protected $allowedFields = ['id_alumno', 'id_curso', 'modalidad', 'turno', 'fecha_inscripcion', 'nota_final'];

    protected $validationRules = [
        'id_alumno' => 'required|integer',
        'id_curso'  => 'required|integer'
    ];

    public function getInscripcionesByAlumno($id_alumno)
    {
        return $this->select('alumno_curso.*, curso.nombre AS curso_nombre, carrera.nombre AS carrera_nombre')
                    ->join('curso', 'curso.id_curso = alumno_curso.id_curso')
                    ->join('carrera', 'carrera.id_carrera = curso.id_carrera', 'left')
                    ->where('id_alumno', $id_alumno)
                    ->findAll();
    }

    public function isAlumnoInscrito($id_alumno, $id_curso)
    {
        return $this->where([
            'id_alumno' => $id_alumno,
            'id_curso'  => $id_curso
        ])->first() !== null;
    }

    public function getAllWithDetalles(): array
    {
        return $this->select(
            'alumno_curso.*, alumno.nombre AS alumno_nombre, alumno.dni AS alumno_dni,' .
            'curso.nombre AS curso_nombre, curso.codigo AS curso_codigo,' .
            'carrera.nombre AS carrera_nombre'
        )
            ->join('alumno', 'alumno.id_alumno = alumno_curso.id_alumno')
            ->join('curso', 'curso.id_curso = alumno_curso.id_curso')
            ->join('carrera', 'carrera.id_carrera = curso.id_carrera', 'left')
            ->orderBy('alumno_curso.id_alumno_curso', 'DESC')
            ->findAll();
    }
}
