<?php
namespace App\Models;

use CodeIgniter\Model;

class ProfesorCursoModel extends Model
{
    protected $table = 'profesor_curso';
    protected $primaryKey = 'id_profesor_curso';
    protected $returnType = 'array';
    protected $allowedFields = ['id_profesor', 'id_curso', 'fecha_asignacion'];

    protected $validationRules = [
        'id_profesor' => 'required|integer',
        'id_curso'    => 'required|integer'
    ];

    public function getCursosByProfesor($id_profesor)
    {
        return $this->select('profesor_curso.*, curso.nombre AS curso_nombre, carrera.nombre AS carrera_nombre')
                    ->join('curso', 'curso.id_curso = profesor_curso.id_curso')
                    ->join('carrera', 'carrera.id_carrera = curso.id_carrera', 'left')
                    ->where('profesor_curso.id_profesor', $id_profesor)
                    ->findAll();
    }

    public function isProfesorInscrito($id_profesor, $id_curso)
    {
        return $this->where([
            'id_profesor' => $id_profesor,
            'id_curso' => $id_curso
        ])->first() !== null;
    }

    public function getAllWithDetalles(): array
    {
        return $this->select(
            'profesor_curso.*, profesor.nombre AS profesor_nombre, profesor.DNI AS profesor_dni,' .
            'curso.nombre AS curso_nombre, curso.codigo AS curso_codigo,' .
            'carrera.nombre AS carrera_nombre'
        )
            ->join('profesor', 'profesor.id_profesor = profesor_curso.id_profesor')
            ->join('curso', 'curso.id_curso = profesor_curso.id_curso')
            ->join('carrera', 'carrera.id_carrera = curso.id_carrera', 'left')
            ->orderBy('profesor_curso.id_profesor_curso', 'DESC')
            ->findAll();
    }
}
