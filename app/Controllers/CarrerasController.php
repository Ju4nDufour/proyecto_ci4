<?php
namespace App\Controllers;

use App\Models\CarreraModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

class CarrerasController extends BaseController
{
    public function index()
    {
        $model = new CarreraModel();
        $q = $this->request->getGet('q');

        $builder = $model;
        if ($q) {
            $builder = $model->groupStart()
                             ->like('nombre', $q)
                             ->orLike('codigo', $q)
                             ->groupEnd();
        }

        $data['carreras'] = $builder->orderBy('id_carrera','ASC')->findAll();
        return view('carreras/index', $data);
    }

    public function store()
    {
        $model = new CarreraModel();
        $data  = $this->request->getPost(['nombre','codigo']);

        if (!$model->insert($data)) {
            return redirect()->back()->with('errors',$model->errors())->withInput();
        }
        return redirect()->to('/carreras')->with('ok','Carrera creada');
    }

    public function edit($id_carrera)
    {
        $model = new CarreraModel();
        $data['carrera'] = $model->find($id_carrera);
        if (!$data['carrera']) {
            return redirect()->to('/carreras')->with('errors',['Carrera no encontrada']);
        }
        return view('carreras/edit', $data);
    }

    public function update($id_carrera)
    {
        $model = new CarreraModel();
        $data  = $this->request->getPost(['nombre','codigo']);

        if (!$model->update($id_carrera, $data)) {
            return redirect()->back()->with('errors',$model->errors())->withInput();
        }
        return redirect()->to('/carreras')->with('ok','Carrera actualizada');
    }

public function delete($id_carrera)
{
    $carreraModel = new \App\Models\CarreraModel();
    $alumnoModel  = new \App\Models\AlumnoModel(); // asegúrate de tener este modelo

    // 1) Chequeo previo: ¿hay alumnos inscriptos?
    $total = $alumnoModel->where('id_carrera', $id_carrera)->countAllResults();

    if ($total > 0) {
        return redirect()->to('/carreras')->with('errors', [
            "La carrera no se puede eliminar porque hay $total alumno(s) inscriptos."
        ]);
    }

    // 2) Si no hay, intentar borrar
    try {
        $carreraModel->delete($id_carrera);
        return redirect()->to('/carreras')->with('ok', 'Carrera eliminada');
    } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
        // Por si hay otras FKs (p.ej., cursos)
        return redirect()->to('/carreras')->with('errors', [
            'La carrera no se puede eliminar porque tiene registros relacionados (alumnos/cursos).'
        ]);
    }
}

}