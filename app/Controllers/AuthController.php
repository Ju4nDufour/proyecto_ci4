<?php

namespace App\Controllers;

class AuthController extends BaseController
{
    /**
     * Mostrar formulario de login
     */
    public function login()
    {
        return view('auth/login');
    }

    /**
     * Procesar login usando Shield
     */
    public function attemptLogin()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Usar Shield para autenticar
        $auth = auth();
        
        $result = $auth->attempt([
            'email'    => $email,
            'password' => $password,
        ]);

        if (!$result->isOK()) {
            return redirect()->back()->with('error', 'Email o contraseña incorrectos');
        }

        // Obtener el usuario autenticado
        $user = $auth->user();

        // Crear sesión personalizada
        $session = session();
        $session->set([
            'user_id'   => $user->id,
            'email'     => $user->email,
            'username'  => $user->username,
            'logged_in' => true,
        ]);

        return redirect()->to('dashboard');
    }

    /**
     * Logout
     */
    public function logout()
    {
        auth()->logout();
        session()->destroy();
        return redirect()->to('/');
    }
}