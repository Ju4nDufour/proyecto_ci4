<?php
namespace App\Controllers;

use App\Models\CursoModel;
use App\Models\CarreraModel;

class CursosController extends BaseController
{
    public function index()
    {
        $cursoModel   = new CursoModel();
        $carreraModel = new CarreraModel();

        $data['cursos']   = $cursoModel->select('cursos.*, carreras.nombre as carrera')
                                       ->join('carreras','carreras.id = cursos.carrera_id')
                                       ->orderBy('carreras.nombre','ASC')->orderBy('cursos.nombre','ASC')
                                       ->findAll();
        $data['carreras'] = $carreraModel->orderBy('nombre','ASC')->findAll();
        return view('cursos/index', $data);
    }

    public function store()
    {
        $model = new CursoModel();
        $data  = $this->request->getPost();

        if(!$model->insert($data)){
            return redirect()->back()->with('errors', $model->errors())->withInput();
        }
        return redirect()->to('/cursos')->with('ok','Curso creado');
    }

    public function edit($id)
    {
        $cursoModel   = new CursoModel();
        $carreraModel = new CarreraModel();

        $data['curso']    = $cursoModel->find($id);
        $data['carreras'] = $carreraModel->orderBy('nombre','ASC')->findAll();
        return view('cursos/edit', $data);
    }

    public function update($id)
    {
        $model = new CursoModel();
        $data  = $this->request->getPost();

        if(!$model->update($id, $data)){
            return redirect()->back()->with('errors',$model->errors())->withInput();
        }
        return redirect()->to('/cursos')->with('ok','Curso actualizado');
    }

    public function delete($id)
    {
        $model = new CursoModel();
        $model->delete($id);
        return redirect()->to('/cursos')->with('ok','Curso eliminado');
    }
}
