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
        $nombre = (string) $this->request->getPost('nombre');
        $data   = [
            'nombre'      => $nombre,
            'descripcion' => $this->request->getPost('descripcion'),
        ];
        $data['codigo'] = $this->generarCodigo($nombre);

        if (!$model->insert($data)) {
            return redirect()->back()->with('errors',$model->errors())->withInput();
        }
        return redirect()->to('/carreras')->with('ok','Carrera creada');
    }

    

    public function update($id_carrera)
    {
        $model = new CarreraModel();
        $data  = [
            'nombre'      => $this->request->getPost('nombre'),
            'descripcion' => $this->request->getPost('descripcion'),
        ];

        if (!$model->update($id_carrera, $data)) {
            return redirect()->back()->with('errors',$model->errors())->withInput();
        }
        return redirect()->to('/carreras')->with('ok','Carrera actualizada');
    }

    public function delete($id_carrera)
    {
        $carreraModel = new \App\Models\CarreraModel();
        $alumnoModel  = new \App\Models\AlumnoModel();

        $total = $alumnoModel->where('id_carrera', $id_carrera)->countAllResults();

        if ($total > 0) {
            return redirect()->to('/carreras')->with('errors', [
                "La carrera no se puede eliminar porque hay $total alumno(s) inscriptos."
            ]);
        }

        try {
            $carreraModel->delete($id_carrera);
            return redirect()->to('/carreras')->with('ok', 'Carrera eliminada');
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            return redirect()->to('/carreras')->with('errors', [
                'La carrera no se puede eliminar porque tiene registros relacionados (alumnos/cursos).'
            ]);
        }
    }

    private function generarCodigo(string $nombre): string
    {
        $base = strtoupper(substr(preg_replace('/[^A-Z]/', '', $nombre), 0, 3));
        if ($base === '') {
            $base = 'CAR';
        }

        $codigo = $base;
        $sufijo = 1;
        $model  = new CarreraModel();

        while ($model->where('codigo', $codigo)->countAllResults() > 0) {
            $codigo = $base . $sufijo;
            $sufijo++;
            $model = new CarreraModel();
        }

        return $codigo;
    }
}
