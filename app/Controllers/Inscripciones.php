<?php
namespace App\Controllers;

use App\Models\AlumnoCursoModel;
use App\Models\ProfesorCursoModel;
use App\Models\CursoModel;
use App\Models\AlumnoModel;

class Inscripciones extends BaseController
{
    public function index()
    {
        $user = auth()->user();

        if ($user->inGroup('profesor')) return $this->vistaProfesor();
        if ($user->inGroup('alumno'))   return $this->vistaAlumno();
        return $this->vistaAdmin();
    }

    private function vistaProfesor()
    {
        $profesor_id = session('profesor_id'); // Ajustar cuando vincules usuarios con profesores
        $profesorCurso = new ProfesorCursoModel();
        $cursoModel = new CursoModel();

        $data['mis_cursos'] = $profesorCurso->getCursosByProfesor($profesor_id);
        $data['cursos_disponibles'] = $cursoModel->where('id_profesor', null)->findAll();

        return view('inscripciones/profesor', $data);
    }

    private function vistaAlumno()
    {
        $alumno_id = session('alumno_id'); // Ajustar cuando vincules usuarios con alumnos
        $alumnoModel = new AlumnoModel();
        $alumnoCurso = new AlumnoCursoModel();
        $cursoModel = new CursoModel();

        $alumno = $alumnoModel->find($alumno_id);
        $data['mis_inscripciones'] = $alumnoCurso->getInscripcionesByAlumno($alumno_id);
        $data['cursos_disponibles'] = $cursoModel->where('id_carrera', $alumno['id_carrera'])->findAll();

        return view('inscripciones/alumno', $data);
    }

    private function vistaAdmin()
    {
        $alumnoCurso = new AlumnoCursoModel();
        $profesorCurso = new ProfesorCursoModel();

        $data['inscripciones_alumnos'] = $alumnoCurso->findAll();
        $data['inscripciones_profesores'] = $profesorCurso->findAll();

        return view('inscripciones/admin', $data);
    }

    public function store()
    {
        $tipo = $this->request->getPost('tipo');
        if ($tipo === 'profesor') return $this->storeProfesor();
        return $this->storeAlumno();
    }

    private function storeProfesor()
    {
        $m = new ProfesorCursoModel();
        $data = $this->request->getPost(['id_profesor', 'id_curso']);
        if (!$m->insert($data)) return redirect()->back()->with('errors', $m->errors());
        return redirect()->back()->with('ok', 'Profesor inscrito correctamente');
    }

    private function storeAlumno()
    {
        $m = new AlumnoCursoModel();
        $data = $this->request->getPost(['id_alumno','id_curso','modalidad','turno']);
        if (!$m->insert($data)) return redirect()->back()->with('errors', $m->errors());
        return redirect()->back()->with('ok','Alumno inscrito correctamente');
    }

    public function delete($id)
    {
        (new AlumnoCursoModel())->delete($id);
        return redirect()->back()->with('ok','Inscripción eliminada');
    }
}
