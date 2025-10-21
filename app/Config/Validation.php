<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;

class Validation extends BaseConfig
{
    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var list<string>
     */
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public array $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------

    public $profesor = [
    'nombre' => [
        'label'  => 'Nombre',
        'rules'  => 'required|min_length[8]',
        'errors' => [
            'required'    => 'El nombre es obligatorio.',
            'min_length'  => 'El nombre debe tener al menos 8 caracteres.',
        ],
    ],
    'email' => [
        'label'  => 'Email',
        'rules'  => 'required|valid_email|min_length[4]',
        'errors' => [
            'required'     => 'El email es obligatorio.',
            'valid_email'  => 'Debe ser un email válido.',
            'min_length'   => 'El email es muy corto.',
        ],
    ],
    'contacto' => [
        'label'  => 'Contacto',
        'rules'  => 'required|numeric|min_length[10]|max_length[11]',
        'errors' => [
            'required'    => 'El contacto es obligatorio.',
            'numeric'     => 'El contacto debe ser numérico.',
            'min_length'  => 'El número debe tener al menos 10 dígitos.',
            'max_length'  => 'El número no puede tener más de 11 dígitos.',
        ],
    ],
    'DNI' => [
        'label'  => 'DNI',
        'rules'  => 'required|numeric|exact_length[8]',
        'errors' => [
            'required'      => 'El DNI es obligatorio.',
            'numeric'       => 'El DNI debe ser numérico.',
            'exact_length'  => 'El DNI debe tener exactamente 8 dígitos.',
        ],
    ],
];


}
