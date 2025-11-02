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
        // Procesar datos JSON o POST
        $jsonData = $this->request->getJSON(true);
        $postData = $this->request->getPost();

        // Priorizar JSON, luego POST
        $data = $jsonData ?? $postData;

        // Registrar datos recibidos para debugging
        log_message('debug', 'Datos recibidos en store: ' . json_encode($data));

        if (empty($data)) {
            return $this->failValidationErrors(['No se recibieron datos']);
        }

        $rules = [
            'username' => 'required|min_length[3]|max_length[30]|is_unique[users.username]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'group'    => 'required|in_list[admin,profesor,alumno]'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        try {
            // Usar UserProvider de Shield
            $userProvider = service('auth')->getProvider();

            $userData = [
                'username' => $data['username'],
                'email'    => $data['email'],
                'password' => $data['password'],
                'active'   => 1
            ];

            // Crear usuario con Shield
            $user = $userProvider->createUser($userData);

            if (!$user) {
                return $this->failServerError('Error al crear el usuario en Shield');
            }

            // Asignar grupo/rol
            $authGroupsUsers = new \App\Models\AuthGroupsUsersModel();
            $authGroupsUsers->insert([
                'user_id'    => $user->id,
                'group'      => $data['group'],
                'created_at' => date('Y-m-d H:i:s')
            ]);

            return $this->respondCreated(['message' => 'Usuario creado exitosamente']);

        } catch (\Exception $e) {
            log_message('error', 'Error al crear usuario: ' . $e->getMessage());
            return $this->failServerError('Error al crear el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Actualizar usuario
     */
    public function update($id = null)
    {
        $json = $this->request->getJSON(true);
        $post = $this->request->getPost();
        $data = !empty($json) ? $json : $post;

        $rules = [
            'username'  => "required|min_length[3]|max_length[30]|is_unique[users.username,id,{$id}]",
            'email'     => "required|valid_email|is_unique[users.email,id,{$id}]",
            'group'     => 'required|in_list[admin,profesor,alumno]'
        ];

        if (!empty($data['password'])) {
            $rules['password'] = 'min_length[8]';
        }

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'error' => true,
                'messages' => $this->validator->getErrors()
            ])->setStatusCode(400);
        }

        try {
            $users = auth()->getProvider();
            $user = $users->findById($id);

            if (!$user) {
                return $this->response->setJSON([
                    'error' => true,
                    'message' => 'Usuario no encontrado'
                ])->setStatusCode(404);
            }

            $user->username = $data['username'];
            $user->email = $data['email'];

            if (!empty($data['password'])) {
                $user->password = $data['password'];
            }

            $users->save($user);

            $authGroupsUsers = new \App\Models\AuthGroupsUsersModel();
            $authGroupsUsers->where('user_id', $id)->delete();
            $authGroupsUsers->insert([
                'user_id'   => $id,
                'group'     => $data['group'],
                'created_at' => date('Y-m-d H:i:s')
            ]);

            return $this->response->setJSON([
                'error' => false,
                'message' => 'Usuario actualizado exitosamente'
            ])->setStatusCode(200);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'error' => true,
                'message' => 'Error al actualizar el usuario: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    /**
     * Eliminar usuario
     */
    public function delete($id = null)
    {
        try {
            $authGroupsUsers = new \App\Models\AuthGroupsUsersModel();
            $authGroupsUsers->where('user_id', $id)->delete();

            if (!$this->model->delete($id)) {
                return $this->response->setJSON([
                    'error' => true,
                    'message' => 'Error al eliminar el usuario'
                ])->setStatusCode(400);
            }

            return $this->response->setJSON([
                'error' => false,
                'message' => 'Usuario eliminado exitosamente'
            ])->setStatusCode(200);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'error' => true,
                'message' => 'Error al eliminar el usuario: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }
}