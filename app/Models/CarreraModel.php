<?php
namespace App\Models;

use CodeIgniter\Model;

class CarreraModel extends Model
{
    protected $table         = 'carrera';       // nombre real de la tabla
    protected $primaryKey    = 'id_carrera';    // PK real
    protected $returnType    = 'array';
    
    protected $allowedFields = ['nombre', 'codigo'];

    
    // Tu tabla no tiene created_at/updated_at/deleted_at
    protected $useTimestamps  = false;
    protected $useSoftDeletes = false;

    protected $validationRules = [
        'nombre' => 'required|min_length[3]|max_length[120]',
        
    ];
}
