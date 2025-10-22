<?php

namespace App\Controllers;

use Config\Database;

class Dashboard extends BaseController
{
    public function index()
    {
        $db = Database::connect();

        $data = [
            'title'              => 'Home',
            'alumnosCount'       => $db->table('alumno')->countAllResults(),
            'carrerasCount'      => $db->table('carrera')->countAllResults(),
            'cursosCount'        => $db->table('curso')->countAllResults(),
            'profesoresCount'    => $db->table('profesor')->countAllResults(),
            'inscripcionesCount' => $db->table('inscripcion')->countAllResults(),
        ];

        return view('dashboard/index', $data);
    }
}