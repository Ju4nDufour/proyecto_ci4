<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;

class LogoutController extends BaseController
{
    public function logoutAction()
    {
        // Ejecutar el logout de Shield
        if (auth()->loggedIn()) {
            auth()->logout();
        }

        // Redirigir al dashboard público
        return redirect()->to('/')->with('message', 'Sesión cerrada exitosamente');
    }
}