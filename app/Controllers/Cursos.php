<?php
namespace App\Controllers;
use App\Models\CarreraModel;
use App\Models\CursoModel;

class Cursos extends BaseController
{
    public function index()
    {
        $carreraModel = new CarreraModel();
        $cursoModel = new CursoModel();

        $carreras = $carreraModel->orderBy('nombre')->findAll();
        $cursosPorCarrera = [];

        foreach ($carreras as $carrera) {
            $cursos = $cursoModel
                ->where('id_carrera', $carrera['id_carrera'])
                ->orderBy('nombre')
                ->findAll();
            $cursosPorCarrera[$carrera['nombre']] = $cursos;
        }

        return view('cursos/index', [
            'cursosPorCarrera' => $cursosPorCarrera
        ]);
    }
}
