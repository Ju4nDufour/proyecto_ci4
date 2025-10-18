<?php

declare(strict_types=1);

namespace Config;

use CodeIgniter\Shield\Config\AuthGroups as ShieldAuthGroups;

class AuthGroups extends ShieldAuthGroups
{
    /**
     * Default Group
     */
    public string $defaultGroup = 'alumno';

    /**
     * Groups - Solo 3 grupos
     */
    public array $groups = [
        'admin' => [
            'title'       => 'Admin',
            'description' => 'Administrador del sistema. Puede crear, editar y eliminar usuarios.',
        ],
        'profesor' => [
            'title'       => 'Profesor',
            'description' => 'Profesor. Solo puede leer usuarios.',
        ],
        'alumno' => [
            'title'       => 'Alumno',
            'description' => 'Alumno. No tiene acceso al CRUD de usuarios.',
        ],
    ];

    /**
     * Permissions
     */
    public array $permissions = [
        'users.create'  => 'Puede crear usuarios',
        'users.read'    => 'Puede leer usuarios',
        'users.edit'    => 'Puede editar usuarios',
        'users.delete'  => 'Puede eliminar usuarios',
    ];

    /**
     * Permissions Matrix - Mapeo de permisos a grupos
     */
    public array $matrix = [
        'admin' => [
            'users.create',
            'users.read',
            'users.edit',
            'users.delete',
        ],
        'profesor' => [
            'users.read',
        ],
        'alumno' => [],
    ];
}