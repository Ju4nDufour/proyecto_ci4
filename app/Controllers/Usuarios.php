<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Usuarios extends ResourceController
{
    use ResponseTrait;

    protected $model;

    public function __construct()
    {
        $this->model = new UsuarioModel();
    }

    /**
     * Vista principal del CRUD de usuarios
     */
    public function index()
    {
        $data = [
            'title' => 'Gestión de Usuarios',
            'users' => $this->model->getUsersWithGroups()
        ];

        return view('usuarios/index', $data);
    }

    /**
     * Obtener un usuario específico (para edición)
     */
    public function show($id = null)
    {
        $user = $this->model->getUserWithGroup($id);
        
        if (!$user) {
            return $this->failNotFound('Usuario no encontrado');
        }

        return $this->respond($user);
    }

    /**
     * API para listar usuarios (usado por AJAX)
     */
    public function list()
    {
        $users = $this->model->getUsersWithGroups();
        return $this->respond($users);
    }

    /**
     * Crear nuevo usuario
     */
    public function store()
    {
        $rules = [
            'username'  => 'required|min_length[3]|max_length[30]|is_unique[users.username]',
            'email'     => 'required|valid_email|is_unique[users.email]',
            'password'  => 'required|min_length[8]',
            'first_name'=> 'permit_empty|max_length[100]',
            'last_name' => 'permit_empty|max_length[100]',
            'group'     => 'required|in_list[admin,profesor,alumno]'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $data = $this->request->getPost();
        
        try {
            // Crear usuario
            $userModel = new \App\Models\UsuarioModel();
            $userData = [
                'username'  => $data['username'],
                'email'     => $data['email'],
                'password'  => $data['password'],
                'first_name'=> $data['first_name'] ?? '',
                'last_name' => $data['last_name'] ?? '',
                'active'    => 1
            ];

            if (!$userModel->save($userData)) {
                return $this->fail($userModel->errors());
            }

            $userId = $userModel->getInsertID();

            // Asignar grupo/rol
            $authGroupsUsers = new \App\Models\AuthGroupsUsersModel();
            $authGroupsUsers->insert([
                'user_id'   => $userId,
                'group'     => $data['group'],
                'created_at' => date('Y-m-d H:i:s')
            ]);

            return $this->respondCreated(['message' => 'Usuario creado exitosamente']);

        } catch (\Exception $e) {
            return $this->failServerError('Error al crear el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Actualizar usuario
     */
    public function update($id = null)
    {
        $rules = [
            'username'  => "required|min_length[3]|max_length[30]|is_unique[users.username,id,{$id}]",
            'email'     => "required|valid_email|is_unique[users.email,id,{$id}]",
            'first_name'=> 'permit_empty|max_length[100]',
            'last_name' => 'permit_empty|max_length[100]',
            'group'     => 'required|in_list[admin,profesor,alumno]'
        ];

        // Solo validar password si se proporciona
        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[8]';
        }

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $data = $this->request->getPost();
        
        try {
            // Actualizar datos del usuario
            $userData = [
                'username'  => $data['username'],
                'email'     => $data['email'],
                'first_name'=> $data['first_name'] ?? '',
                'last_name' => $data['last_name'] ?? '',
            ];

            // Solo actualizar password si se proporciona
            if (!empty($data['password'])) {
                $userData['password'] = $data['password'];
            }

            if (!$this->model->update($id, $userData)) {
                return $this->fail($this->model->errors());
            }

            // Actualizar grupo/rol
            $authGroupsUsers = new \App\Models\AuthGroupsUsersModel();
            $authGroupsUsers->where('user_id', $id)->delete(); // Eliminar grupo actual
            $authGroupsUsers->insert([
                'user_id'   => $id,
                'group'     => $data['group'],
                'created_at' => date('Y-m-d H:i:s')
            ]);

            return $this->respond(['message' => 'Usuario actualizado exitosamente']);

        } catch (\Exception $e) {
            return $this->failServerError('Error al actualizar el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar usuario
     */
    public function delete($id = null)
    {
        try {
            // Primero eliminar de auth_groups_users
            $authGroupsUsers = new \App\Models\AuthGroupsUsersModel();
            $authGroupsUsers->where('user_id', $id)->delete();

            // Luego eliminar el usuario
            if (!$this->model->delete($id)) {
                return $this->fail('Error al eliminar el usuario');
            }

            return $this->respondDeleted(['message' => 'Usuario eliminado exitosamente']);

        } catch (\Exception $e) {
            return $this->failServerError('Error al eliminar el usuario: ' . $e->getMessage());
        }
    }
}