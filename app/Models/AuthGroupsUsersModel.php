<?php

namespace App\Models;

use CodeIgniter\Model;

class AuthGroupsUsersModel extends Model
{
    protected $table            = 'auth_groups_users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id', 'group', 'created_at'
    ];

    protected $useTimestamps = false;
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
}
