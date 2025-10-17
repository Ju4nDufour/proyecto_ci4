<?php

namespace App\Controllers;

use App\Models\ProfesorModel;

class ProfesoresController extends BaseController
{
    public function index()
    {
        $model = new ProfesorModel();
        $data['profesores'] = $model->findAll();
        return view('profesores/index', $data);
    }

    public function store()
    {
        $model = new ProfesorModel();

        $data = [
            'nombre'   => $this->request->getPost('nombre'),
            'email'    => $this->request->getPost('email'),
            'contacto' => $this->request->getPost('contacto'),
            'DNI'      => $this->request->getPost('DNI'),
        ];

        $model->save($data);
        return redirect()->to('/profesores')->with('ok', 'Profesor agregado con éxito');
    }

    public function update($id)
    {
        $model = new ProfesorModel();

        $data = [
            'id_profesor' => $id,
            'nombre'      => $this->request->getPost('nombre'),
            'email'       => $this->request->getPost('email'),
            'contacto'    => $this->request->getPost('contacto'),
            'DNI'         => $this->request->getPost('DNI'),
        ];

        $model->save($data);
        return redirect()->to('/profesores')->with('ok', 'Profesor actualizado con éxito');
    }

    public function delete($id)
    {
        $model = new ProfesorModel();
        $model->delete($id);
        return redirect()->to('/profesores')->with('ok', 'Profesor eliminado con éxito');
    }
}
