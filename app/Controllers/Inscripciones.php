<?php

namespace App\Controllers;

use App\Models\AlumnoCursoModel;
use App\Models\ProfesorCursoModel;
use App\Models\CursoModel;
use App\Models\AlumnoModel;
use App\Models\ProfesorModel;

class Inscripciones extends BaseController
{
    public function index()
    {
        $user = auth()->user();

        if ($user?->inGroup('profesor')) {
            return $this->vistaProfesor();
        }

        if ($user?->inGroup('alumno')) {
            return $this->vistaAlumno();
        }

        return $this->vistaAdmin();
    }

    private function vistaProfesor()
    {
        $profesor = $this->getProfesorActual();
        if (! $profesor) {
            return redirect()->to('/')->with('errors', ['No se encontro el profesor vinculado al usuario actual.']);
        }

        $profesorCurso = new ProfesorCursoModel();
        $cursoModel    = new CursoModel();

        $data['mis_cursos'] = $profesorCurso->getCursosByProfesor($profesor['id_profesor']);

        $yaInscriptoIds = array_column($data['mis_cursos'], 'id_curso');
        if ($yaInscriptoIds === []) {
            $yaInscriptoIds = [-1];
        }

        $data['cursos_disponibles'] = $cursoModel
            ->whereNotIn('id_curso', $yaInscriptoIds)
            ->orderBy('nombre', 'ASC')
            ->findAll();

        return view('inscripciones/profesor', $data);
    }

    private function vistaAlumno()
    {
        $alumno = $this->getAlumnoActual();
        if (! $alumno) {
            return redirect()->to('/')->with('errors', ['No se encontro el alumno vinculado al usuario actual.']);
        }

        $alumnoCurso = new AlumnoCursoModel();
        $cursoModel  = new CursoModel();

        $data['mis_inscripciones'] = $alumnoCurso->getInscripcionesByAlumno($alumno['id_alumno']);

        $yaInscriptoIds = array_column($data['mis_inscripciones'], 'id_curso');
        if ($yaInscriptoIds === []) {
            $yaInscriptoIds = [-1];
        }

        $data['cursos_disponibles'] = $cursoModel
            ->where('id_carrera', $alumno['id_carrera'])
            ->whereNotIn('id_curso', $yaInscriptoIds)
            ->orderBy('nombre', 'ASC')
            ->findAll();

        return view('inscripciones/alumno', $data);
    }

    private function vistaAdmin()
    {
        $alumnoCurso   = new AlumnoCursoModel();
        $profesorCurso = new ProfesorCursoModel();
        $cursoModel    = new CursoModel();
        $alumnoModel   = new AlumnoModel();
        $profesorModel = new ProfesorModel();

        $data = [
            'inscripciones_alumnos'    => $alumnoCurso->getAllWithDetalles(),
            'inscripciones_profesores' => $profesorCurso->getAllWithDetalles(),
            'cursos'                   => $cursoModel->orderBy('nombre', 'ASC')->findAll(),
            'alumnos'                  => $alumnoModel->orderBy('nombre', 'ASC')->findAll(),
            'profesores'               => $profesorModel->orderBy('nombre', 'ASC')->findAll(),
        ];

        return view('inscripciones/admin', $data);
    }

    public function store()
    {
        $tipo = $this->request->getPost('tipo');

        if ($tipo === 'profesor') {
            return $this->storeProfesor();
        }

        return $this->storeAlumno();
    }

    private function storeProfesor()
    {
        $profesorActual = $this->getProfesorActual();
        $profesorId     = $profesorActual ? (int) $profesorActual['id_profesor'] : (int) $this->request->getPost('id_profesor');

        if (! $profesorId) {
            return redirect()->back()->with('errors', ['Debes seleccionar un profesor valido.']);
        }

        $m    = new ProfesorCursoModel();
        $data = [
            'id_profesor' => $profesorId,
            'id_curso'    => $this->request->getPost('id_curso'),
        ];

        if ($m->isProfesorInscrito($data['id_profesor'], $data['id_curso'])) {
            return redirect()->back()->with('errors', ['Ya estas asignado a ese curso.']);
        }

        if (! $m->insert($data)) {
            return redirect()->back()->with('errors', $m->errors())->withInput();
        }

        return redirect()->back()->with('ok', 'Profesor inscrito correctamente');
    }

    private function storeAlumno()
    {
        $alumnoActual = $this->getAlumnoActual();
        $alumnoId     = $alumnoActual ? (int) $alumnoActual['id_alumno'] : (int) $this->request->getPost('id_alumno');

        if (! $alumnoId) {
            return redirect()->back()->with('errors', ['Debes seleccionar un alumno valido.']);
        }

        $alumnoFila = $alumnoActual ?? (new AlumnoModel())->find($alumnoId);
        if (! $alumnoFila) {
            return redirect()->back()->with('errors', ['No se encontro el alumno seleccionado.']);
        }

        $cursoId = (int) $this->request->getPost('id_curso');
        $curso   = (new CursoModel())->find($cursoId);
        if (! $curso || (int) $curso['id_carrera'] !== (int) $alumnoFila['id_carrera']) {
            return redirect()->back()->with('errors', ['El curso elegido no pertenece a la carrera del alumno.']);
        }

        $m    = new AlumnoCursoModel();
        $data = [
            'id_alumno' => $alumnoId,
            'id_curso'  => $cursoId,
            'modalidad' => $this->request->getPost('modalidad'),
            'turno'     => $this->request->getPost('turno'),
        ];

        if ($m->isAlumnoInscrito($data['id_alumno'], $data['id_curso'])) {
            return redirect()->back()->with('errors', ['Ya estas inscripto en ese curso.']);
        }

        if (! $m->insert($data)) {
            return redirect()->back()->with('errors', $m->errors())->withInput();
        }

        return redirect()->back()->with('ok', 'Alumno inscrito correctamente');
    }

    public function delete($id)
    {
        $tipo = $this->request->getPost('tipo') ?? $this->request->getGet('tipo') ?? 'alumno';

        if ($tipo === 'profesor') {
            (new ProfesorCursoModel())->delete($id);
            return redirect()->back()->with('ok', 'Asignacion de profesor eliminada');
        }

        (new AlumnoCursoModel())->delete($id);

        return redirect()->back()->with('ok', 'Inscripcion eliminada');
    }

    private function getAlumnoActual(): ?array
    {
        $user = auth()->user();
        if (! $user) {
            return null;
        }

        return (new AlumnoModel())
            ->where('user_id', $user->id)
            ->first();
    }

    private function getProfesorActual(): ?array
    {
        $user = auth()->user();
        if (! $user) {
            return null;
        }

        return (new ProfesorModel())
            ->where('user_id', $user->id)
            ->first();
    }
}
