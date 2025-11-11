<?php

namespace App\Models;

use CodeIgniter\Model;

class CarreraModel extends Model
{
    protected $table            = 'carrera';
    protected $primaryKey       = 'id_carrera';
    protected $allowedFields    = ['nombre', 'codigo', 'descripcion'];
    protected $useTimestamps    = false;
}
