<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfesorModel extends Model
{
    protected $table      = 'profesor';
    protected $primaryKey = 'id_profesor';

    protected $allowedFields = [
        'nombre',
        'email',
        'contacto',
        'DNI'
    ];

    // Validaciones
    protected $validationRules = [
        'nombre' => [
            'label' => 'Nombre',
            'rules' => 'required|regex_match[/^[A-ZÁÉÍÓÚÑa-záéíóúñ]{3,}( [A-ZÁÉÍÓÚÑa-záéíóúñ]{3,})+$/]'
        ],
        'email' => [
            'label' => 'Email',
            'rules' => 'permit_empty|valid_email|max_length[120]'
        ],
        'contacto' => [
            'label' => 'Contacto',
            'rules' => 'required|regex_match[/^[0-9]{10,15}$/]'
        ],
        'DNI' => [
            'label' => 'DNI',
            'rules' => 'required|regex_match[/^[0-9]{8}$/]|is_unique[profesor.DNI,id_profesor,{id_profesor}]'
        ]
    ];

    protected $validationMessages = [
        'nombre' => [
            'regex_match' => 'El nombre debe tener al menos un nombre y un apellido, con solo letras y mínimo 3 letras cada uno.'
        ],
        'contacto' => [
            'regex_match' => 'El contacto debe tener solo números y entre 10 y 15 dígitos.'
        ],
        'DNI' => [
            'regex_match' => 'El DNI debe tener exactamente 8 dígitos.',
            'is_unique' => 'Este DNI ya está registrado.'
        ],
        'email' => [
            'valid_email' => 'El email ingresado no es válido.'
        ]
    ];
}
