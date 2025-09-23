<?php namespace App\Models;

use CodeIgniter\Model;

class AlumnoModel extends Model
{
    protected $table      = 'alumno';
    protected $primaryKey = 'id_alumno';
    protected $allowedFields = ['dni','nombre','email','fecha_nac','id_carrera'];
    protected $useTimestamps = false;



    // Validación básica para crear
    protected $validationRules = [
        'dni'        => 'required|exact_length[8]|is_natural_no_zero', // QUITAR is_unique
        'nombre'     => 'required|min_length[3]',
        'email'      => 'permit_empty|valid_email',
        'fecha_nac'  => 'permit_empty|valid_date',
        'id_carrera' => 'required|is_natural_no_zero',
    ];
    protected $validationMessages = [
        'dni' => [
            'is_unique'     => 'Ese DNI ya existe.',
            'exact_length'  => 'El DNI debe tener 8 dígitos.'
        ],
    ];
}
