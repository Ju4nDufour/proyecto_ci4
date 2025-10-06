<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Inicio - Instituto'
        ];
        return view('dashboard/index', $data);
    }
}
