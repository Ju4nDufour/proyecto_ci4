<?php

declare(strict_types=1);

namespace Config;

use CodeIgniter\Shield\Config\AuthGroups as ShieldAuthGroups;

class AuthGroups extends ShieldAuthGroups
{
    /**
     * Grupo por defecto para nuevos usuarios.
     */
    public string $defaultGroup = 'alumno';

    /**
     * Definición de grupos disponibles en el sistema.
     *
     * @var array<string, array<string, string>>
     */
    public array $groups = [
        'admin' => [
            'title'       => 'Administrador',
            'description' => 'Acceso total al panel y gestión de usuarios.',
        ],
        'profesor' => [
            'title'       => 'Profesor',
            'description' => 'Acceso académico con permisos de lectura.',
        ],
        'alumno' => [
            'title'       => 'Alumno',
            'description' => 'Usuarios finales sin privilegios administrativos.',
        ],
    ];

    /**
     * Permisos disponibles.
     */
    public array $permissions = [
        'users.create' => 'Puede crear usuarios',
        'users.read'   => 'Puede ver el listado de usuarios',
        'users.update' => 'Puede actualizar usuarios',
        'users.delete' => 'Puede eliminar usuarios',
    ];

    /**
     * Matriz de permisos asignados por grupo.
     */
    public array $matrix = [
        'admin' => [
            'users.create',
            'users.read',
            'users.update',
            'users.delete',
        ],
        'profesor' => [
            'users.read',
        ],
        'alumno' => [],
    ];
}