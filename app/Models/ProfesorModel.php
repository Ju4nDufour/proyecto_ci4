<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfesorModel extends Model
{
    protected $table      = 'profesor';
    protected $primaryKey = 'id_profesor';
    protected $allowedFields = ['nombre', 'id_profesor', 'email'];
}
