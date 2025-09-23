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
        $model->save($this->request->getPost());
        return redirect()->to('/profesores')->with('ok', 'Profesor agregado con éxito');
    }

    public function delete($id)
    {
        $model = new ProfesorModel();
        $model->delete($id);
        return redirect()->to('/profesores')->with('ok', 'Profesor eliminado con éxito');
    }
}
