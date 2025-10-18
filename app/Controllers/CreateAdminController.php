<?php

namespace App\Controllers;

use CodeIgniter\Shield\Entities\User;

class CreateAdminController extends BaseController
{
    /**
     * Crear usuario admin (solo para desarrollo)
     * Accede a: http://localhost:8080/create-admin
     */
    public function index()
    {
        // Verificar si ya existe un admin
        $userModel = model('UserModel');
        $admin = $userModel->where('email', 'admin@example.com')->first();

        if ($admin) {
            return "Admin ya existe: admin@example.com";
        }

        // Crear nuevo usuario admin
        $user = new User([
            'email'    => 'admin@example.com',
            'username' => 'admin',
            'password' => 'Admin123!',
        ]);

        $userModel->save($user);

        // Asignar rol de admin
        $user->addGroup('admin');

        return "✅ Usuario admin creado exitosamente<br>
                Email: admin@example.com<br>
                Contraseña: Admin123!<br>
                <a href='" . site_url('auth/login') . "'>Ir a Login</a>";
    }
}