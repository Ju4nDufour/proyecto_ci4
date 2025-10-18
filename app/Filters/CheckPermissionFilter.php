<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class CheckPermissionFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        // Verificar si está logueado
        if (!$session->get('logged_in')) {
            return redirect()->to('auth/login');
        }

        // Si no hay permisos especificados, permitir
        if (empty($arguments)) {
            return;
        }

        // Obtener el usuario
        $userId = $session->get('user_id');
        $userModel = model('UserModel');
        $user = $userModel->find($userId);

        if (!$user) {
            return redirect()->to('auth/login');
        }

        // Verificar permisos
        $permission = $arguments[0] ?? null;
        if ($permission && !$user->can($permission)) {
            return redirect()->to('/')->with('error', 'No tienes permiso para acceder a esta sección');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}