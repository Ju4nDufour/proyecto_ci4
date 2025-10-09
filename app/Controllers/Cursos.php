<?php

namespace App\Controllers;

use App\Models\CursoModel;
use CodeIgniter\Controller;

class Cursos extends BaseController
{
    public function index()
    {
        $model = new CursoModel();

        $cursos = $model
            ->select('Curso.*, Carrera.nombre as nombre_carrera')
            ->join('Carrera', 'Carrera.id_carrera = Curso.id_carrera')
            ->orderBy('Curso.nombre')
            ->findAll();

        return view('cursos/index', ['cursos' => $cursos]);
    }
}
