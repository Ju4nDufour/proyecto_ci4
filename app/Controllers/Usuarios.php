<?php

namespace App\Controllers;

use App\Models\UsuariosModel;

class Usuarios extends BaseController
{
    public function index()
    {
        $model = new UsuariosModel();
        $data['usuarios'] = $model->findAll();
        return view('usuarios/index', $data);
    }

    public function store()
    {
        $model = new UsuariosModel();
        $model->insert([
            'nombre' => $this->request->getPost('nombre'),
            'email' => $this->request->getPost('email'),
            'rol' => $this->request->getPost('rol'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
        ]);
        return redirect()->to('/usuarios');
    }

    public function edit($id)
    {
        $model = new UsuariosModel();
        $data['usuario'] = $model->find($id);
        return view('usuarios/editar', $data);
    }

    public function update($id)
    {
        $model = new UsuariosModel();
        $model->update($id, [
            'nombre' => $this->request->getPost('nombre'),
            'email' => $this->request->getPost('email'),
            'rol' => $this->request->getPost('rol')
        ]);
        return redirect()->to('/usuarios');
    }

    public function delete($id)
    {
        $model = new UsuariosModel();
        $model->delete($id);
        return redirect()->to('/usuarios');
    }

    public function buscar()
    {
        $texto = $this->request->getGet('texto');
        $model = new UsuariosModel();
        $data['usuarios'] = $model->like('nombre', $texto)
                                 ->orLike('email', $texto)
                                 ->findAll();
        return view('usuarios/index', $data);
    }
}
