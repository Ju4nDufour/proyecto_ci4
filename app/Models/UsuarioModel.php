<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'username', 'email', 'password', 'active'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'username'  => 'required|min_length[3]|max_length[30]|is_unique[users.username]',
        'email'     => 'required|valid_email|is_unique[users.email]',
        'password'  => 'required|min_length[8]'
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $beforeUpdate   = [];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password']) && !empty($data['data']['password'])) {
            $data['data']['password_hash'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
            unset($data['data']['password']);
        }
        return $data;
    }

    /**
     * Obtiene usuarios con sus grupos/roles
     */
    public function getUsersWithGroups()
    {
        return $this->select('users.*, auth_groups_users.group')
                    ->join('auth_groups_users', 'auth_groups_users.user_id = users.id')
                    ->findAll();
    }

    /**
     * Obtiene un usuario específico con su grupo
     */
    public function getUserWithGroup($id)
    {
        return $this->select('users.*, auth_groups_users.group')
                    ->join('auth_groups_users', 'auth_groups_users.user_id = users.id')
                    ->where('users.id', $id)
                    ->first();
    }
}