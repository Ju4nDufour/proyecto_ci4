<?php namespace App\Controllers;

use App\Models\AlumnoModel;
use App\Models\CarreraModel;

class Alumnos extends BaseController
{
    protected $helpers = ['url','form'];

    public function index()
    {
        $m = new AlumnoModel();
        $q = trim((string) $this->request->getGet('q'));
        $perPage = 10;

        // Traigo también id_carrera para poder editar
        $builder = $m->select('alumno.id_alumno, alumno.dni, alumno.nombre, alumno.email, alumno.fecha_nac, alumno.id_carrera, carrera.nombre AS carrera')
                     ->join('carrera', 'carrera.id_carrera = alumno.id_carrera', 'left');

        if ($q !== '') {
            $builder = $builder->groupStart()
                               ->like('alumno.dni', $q)
                               ->orLike('alumno.nombre', $q)
                               ->groupEnd();
        }

        $alumnos = $builder->orderBy('alumno.id_alumno','ASC')->paginate($perPage);
        $pager   = $m->pager;

        $carreras = (new CarreraModel())->orderBy('nombre','ASC')->findAll();

        return view('alumnos/index', compact('alumnos','pager','q','carreras'));
    }

    public function store()
    {
        $m = new AlumnoModel();
        $data = [
            'dni'        => $this->request->getPost('dni'),
            'nombre'     => $this->request->getPost('nombre'),
            'email'      => $this->request->getPost('email'),
            'fecha_nac'  => $this->request->getPost('fecha_nac'),
            'id_carrera' => $this->request->getPost('id_carrera'),
        ];
        if (!$m->insert($data, true)) {
            return redirect()->back()->with('errors', $m->errors())->withInput();
        }
        return redirect()->to(site_url('alumnos'))->with('ok','Alumno creado correctamente');
    }

    public function update($id)
    {
        $m = new AlumnoModel();
        $data = [
            'dni'        => $this->request->getPost('dni'),
            'nombre'     => $this->request->getPost('nombre'),
            'email'      => $this->request->getPost('email'),
            'fecha_nac'  => $this->request->getPost('fecha_nac'),
            'id_carrera' => $this->request->getPost('id_carrera'),
        ];

        // Reglas para UPDATE: DNI único ignorando el propio registro
        $rules = [
            'dni'        => "required|exact_length[8]|is_natural_no_zero|is_unique[alumno.dni,id_alumno,{$id}]",
            'nombre'     => 'required|min_length[3]',
            'email'      => 'permit_empty|valid_email',
            'fecha_nac'  => 'permit_empty|valid_date',
            'id_carrera' => 'required|is_natural_no_zero',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors())->withInput();
        }

        if (!$m->update($id, $data)) {
            return redirect()->back()->with('errors', $m->errors())->withInput();
        }
        return redirect()->to(site_url('alumnos'))->with('ok','Alumno actualizado');
    }

    public function delete($id)
    {
        $m = new AlumnoModel();
        try {
            // OJO: si tiene inscripciones (FK RESTRICT) MySQL lo va a impedir
            if (!$m->delete($id)) {
                return redirect()->back()->with('errors', $m->errors());
            }
            return redirect()->to(site_url('alumnos'))->with('ok','Alumno eliminado');
        } catch (\Throwable $e) {
            return redirect()->back()->with('errors', ['No se puede eliminar: el alumno tiene inscripciones asociadas.']);
        }
    }
}
