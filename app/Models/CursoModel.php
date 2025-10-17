<?php
namespace App\Models;
use CodeIgniter\Model;

class CursoModel extends Model
{
    protected $table = 'Curso';
    protected $primaryKey = 'id_curso';
    protected $allowedFields = ['nombre', 'codigo', 'id_carrera', 'id_profesor'];

    protected $validationRules = [
    'nombre' => 'required|min_length[3]',
    'codigo' => 'required|alpha_numeric|max_length[10]',
    'id_carrera' => 'required|is_natural_no_zero'
];

}
