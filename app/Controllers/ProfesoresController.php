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
            'rol_id'   => $this->request->getPost('rol_id') ?? 2,
        ];

        // Validar con las reglas definidas en el modelo
        if (!$model->save($data)) {
            // Si falla, volvemos al formulario con los errores y los datos cargados
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $model->errors());
        }

        // Si todo está bien
        return redirect()
            ->to('/profesores')
            ->with('ok', 'Profesor agregado con éxito');
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

        // Validar con las reglas definidas en el modelo
        if (!$model->save($data)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $model->errors());
        }

        return redirect()
            ->to('/profesores')
            ->with('ok', 'Profesor actualizado con éxito');
    }

    public function delete($id)
    {
        $model = new ProfesorModel();
        $model->delete($id);
        return redirect()
            ->to('/profesores')
            ->with('ok', 'Profesor eliminado con éxito');
    }
}
