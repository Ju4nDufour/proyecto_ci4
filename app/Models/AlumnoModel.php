<?php
namespace App\Models;

use CodeIgniter\Model;

class AlumnoModel extends Model
{
    protected $table = 'alumno';
    protected $primaryKey = 'id_alumno';
    protected $returnType = 'array';
    protected $allowedFields = ['dni','nombre','apellido','email','fecha_nac','id_carrera'];
    protected $useTimestamps = false;

    protected $validationRules = [
        'dni' => 'required|exact_length[8]|numeric',
        'nombre' => 'required|min_length[2]',
        'apellido' => 'required|min_length[2]',
        'email' => 'required|valid_email'
    ];
}
