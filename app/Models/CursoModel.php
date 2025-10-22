<?php
namespace App\Models;

use CodeIgniter\Model;

class CursoModel extends Model
{
    protected $table = 'curso';
    protected $primaryKey = 'id_curso';
    protected $returnType = 'array';
    protected $allowedFields = ['nombre', 'id_carrera'];
    protected $useTimestamps = false;
    protected $validationRules = [
        'nombre' => 'required|min_length[3]',
        'codigo' => 'required',
        'id_carrera' => 'required|integer'
    ];
}

