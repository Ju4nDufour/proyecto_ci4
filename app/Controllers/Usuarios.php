<?php

namespace App\Controllers;
use App\Models\UserModel;

class Users extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // Listar todos los usuarios
    public function index()
    {
        $data['usuarios'] = $this->userModel->findAll();
        return view('usuarios/index', $data);
    }

    // Crear usuario
    public function store()
    {
        $data = $this->request->getPost();
        if ($this->userModel->insert($data)) {
            return redirect()->to('/usuarios')->with('ok', 'Usuario creado correctamente');
        }
        return redirect()->back()->withInput()->with('errors', $this->userModel->errors());
    }

    // Actualizar usuario
    public function update($id)
    {
        $data = $this->request->getPost();
        if ($this->userModel->update($id, $data)) {
            return redirect()->to('/usuarios')->with('ok', 'Usuario actualizado correctamente');
        }
        return redirect()->back()->withInput()->with('errors', $this->userModel->errors());
    }

    // Eliminar usuario
    public function delete($id)
    {
        $this->userModel->delete($id);
        return redirect()->to('/usuarios')->with('ok', 'Usuario eliminado correctamente');
    }
}
