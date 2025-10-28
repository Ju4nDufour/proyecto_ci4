<?php

namespace App\Models;
use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nombre', 'email', 'contacto', 'dni', 'rol_id'];
    protected $validationRules = [
        'nombre'    => 'required',
        'email'     => 'valid_email',
        'contacto'  => 'required|numeric|min_length[10]|max_length[15]',
        'dni'       => 'required|numeric|exact_length[8]',
        'rol_id'    => 'required|in_list[1,2]',
    ];
}
