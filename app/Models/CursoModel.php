<?php

namespace App\Models;

use CodeIgniter\Model;

class CursoModel extends Model
{
    protected $table            = 'curso';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['carrera_id','nombre','anio','descripcion'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    protected $validationRules = [
        'carrera_id' => 'required|is_natural_no_zero',
        'nombre'     => 'required|min_length[3]|max_length[120]',
        'anio'       => 'permit_empty|is_natural|less_than_equal_to[10]',
    ];
}
