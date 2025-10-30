<?php

namespace App\Controllers;

use App\Models\CarreraModel;

class CarrerasPublic extends BaseController
{
    public function index()
    {
        $model = new CarreraModel();
        $data['carreras'] = $model->orderBy('nombre', 'ASC')->findAll();

        return view('public/carreras_listado', $data);
    }
}
