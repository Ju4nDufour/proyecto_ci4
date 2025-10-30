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

        'profesores.create' => 'Puede crear profesores',
        'profesores.read'   => 'Puede ver el listado de profesores',
        'profesores.update' => 'Puede actualizar profesores',
        'profesores.delete' => 'Puede eliminar profesores',

        'alumnos.create' => 'Puede crear alumnos',
        'alumnos.read'   => 'Puede ver el listado de alumnos',
        'alumnos.update' => 'Puede actualizar alumnos',
        'alumnos.delete' => 'Puede eliminar alumnos',

        'carreras.create' => 'Puede crear carreras',
        'carreras.read'   => 'Puede ver el listado de carreras',
        'carreras.update' => 'Puede actualizar carreras',
        'carreras.delete' => 'Puede eliminar carreras',

        'cursos.create' => 'Puede crear cursos',
        'cursos.read'   => 'Puede ver el listado de cursos',
        'cursos.update' => 'Puede actualizar cursos',
        'cursos.delete' => 'Puede eliminar cursos',

        'inscripciones.create' => 'Puede crear inscripciones',
        'inscripciones.read'   => 'Puede ver el listado de inscripciones',
        'inscripciones.update' => 'Puede actualizar inscripciones',
        'inscripciones.delete' => 'Puede eliminar inscripciones',
    ];

    /**
     * Matriz de permisos asignados por grupo.
     *
     * Admin: acceso a todo.
     * Profesor: no ve usuarios (no permisos users.*).
     * Alumno: no ve usuarios ni profesores (no users.*, no profesores.*).
     */
    public array $matrix = [
        'admin' => [
            'users.create',
            'users.read',
            'users.update',
            'users.delete',
            'profesores.create',
            'profesores.read',
            'profesores.update',
            'profesores.delete',
            'alumnos.create',
            'alumnos.read',
            'alumnos.update',
            'alumnos.delete',
            'carreras.create',
            'carreras.read',
            'carreras.update',
            'carreras.delete',
            'cursos.create',
            'cursos.read',
            'cursos.update',
            'cursos.delete',
            'inscripciones.create',
            'inscripciones.read',
            'inscripciones.update',
            'inscripciones.delete',
        ],
        'profesor' => [
            'profesores.read',
            'alumnos.read',
            'carreras.read',
            'cursos.read',
            'inscripciones.read',
        ],
        'alumno' => [
            'alumnos.read',
            'carreras.read',
            'cursos.read',
            'inscripciones.read',
        ],
    ];
}