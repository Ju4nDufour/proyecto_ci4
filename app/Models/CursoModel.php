<?php
namespace App\Models;
use CodeIgniter\Model;

class CursoModel extends Model
{
    protected $table = 'Curso';
    protected $primaryKey = 'id_curso';
    protected $allowedFields = ['nombre', 'id_carrera'];
}
