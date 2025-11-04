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

        // Validar que el usuario esté autenticado
        if (!$user) {
            return redirect()->to('login')->with('errors', ['Debes iniciar sesión para acceder.']);
        }

        // Verificar que el usuario tenga un grupo válido
        if (!$user->inGroup('admin') && !$user->inGroup('profesor') && !$user->inGroup('alumno')) {
            return redirect()->to('/')->with('errors', ['No tienes permisos para acceder a inscripciones.']);
        }

        // Redirigir según el rol
        if ($user->inGroup('admin')) {
            return $this->vistaAdmin();
        }

        if ($user->inGroup('profesor')) {
            return $this->vistaProfesor();
        }

        if ($user->inGroup('alumno')) {
            return $this->vistaAlumno();
        }

        // Si llega aquí, no tiene rol asignado
        return redirect()->to('/')->with('errors', ['Tu usuario no tiene un rol asignado. Contacta al administrador.']);
    }

    private function vistaProfesor()
    {
        $profesor = $this->getProfesorActual();
        if (!$profesor) {
            return redirect()->to('/')->with('errors', ['No se encontró el profesor vinculado a tu usuario. Contacta al administrador.']);
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
        if (!$alumno) {
            return redirect()->to('/')->with('errors', ['No se encontró el alumno vinculado a tu usuario. Contacta al administrador.']);
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
        // Verificar autenticación
        $user = auth()->user();
        if (!$user) {
            return redirect()->to('login')->with('errors', ['Debes iniciar sesión.']);
        }

        $tipo = $this->request->getPost('tipo');

        if ($tipo === 'profesor') {
            // Solo admin y profesores pueden inscribir profesores
            if (!$user->inGroup('admin') && !$user->inGroup('profesor')) {
                return redirect()->back()->with('errors', ['No tienes permisos para realizar esta acción.']);
            }
            return $this->storeProfesor();
        }

        // Para inscribir alumnos
        if (!$user->inGroup('admin') && !$user->inGroup('alumno')) {
            return redirect()->back()->with('errors', ['No tienes permisos para realizar esta acción.']);
        }

        return $this->storeAlumno();
    }

    private function storeProfesor()
    {
        $user = auth()->user();
        
        // Si es profesor, solo puede inscribirse a sí mismo
        if ($user->inGroup('profesor')) {
            $profesorActual = $this->getProfesorActual();
            if (!$profesorActual) {
                return redirect()->back()->with('errors', ['No se encontró tu perfil de profesor.']);
            }
            $profesorId = (int) $profesorActual['id_profesor'];
        } else {
            // Si es admin, puede elegir cualquier profesor
            $profesorId = (int) $this->request->getPost('id_profesor');
        }

        if (!$profesorId) {
            return redirect()->back()->with('errors', ['Debes seleccionar un profesor válido.']);
        }

        $m    = new ProfesorCursoModel();
        $data = [
            'id_profesor' => $profesorId,
            'id_curso'    => $this->request->getPost('id_curso'),
        ];

        if ($m->isProfesorInscrito($data['id_profesor'], $data['id_curso'])) {
            return redirect()->back()->with('errors', ['Ya estás asignado a ese curso.']);
        }

        if (!$m->insert($data)) {
            return redirect()->back()->with('errors', $m->errors())->withInput();
        }

        return redirect()->back()->with('ok', 'Profesor inscrito correctamente');
    }

    private function storeAlumno()
    {
        $user = auth()->user();
        
        // Si es alumno, solo puede inscribirse a sí mismo
        if ($user->inGroup('alumno')) {
            $alumnoActual = $this->getAlumnoActual();
            if (!$alumnoActual) {
                return redirect()->back()->with('errors', ['No se encontró tu perfil de alumno.']);
            }
            $alumnoId = (int) $alumnoActual['id_alumno'];
        } else {
            // Si es admin, puede elegir cualquier alumno
            $alumnoId = (int) $this->request->getPost('id_alumno');
        }

        if (!$alumnoId) {
            return redirect()->back()->with('errors', ['Debes seleccionar un alumno válido.']);
        }

        $alumnoModel = new AlumnoModel();
        $alumnoFila  = $alumnoModel->find($alumnoId);
        
        if (!$alumnoFila) {
            return redirect()->back()->with('errors', ['No se encontró el alumno seleccionado.']);
        }

        $cursoId = (int) $this->request->getPost('id_curso');
        $curso   = (new CursoModel())->find($cursoId);
        
        if (!$curso) {
            return redirect()->back()->with('errors', ['El curso seleccionado no existe.']);
        }

        if ((int) $curso['id_carrera'] !== (int) $alumnoFila['id_carrera']) {
            return redirect()->back()->with('errors', ['El curso elegido no pertenece a tu carrera.']);
        }

        $m    = new AlumnoCursoModel();
        $data = [
            'id_alumno' => $alumnoId,
            'id_curso'  => $cursoId,
            'modalidad' => $this->request->getPost('modalidad') ?: 'presencial',
            'turno'     => $this->request->getPost('turno') ?: 'mañana',
        ];

        if ($m->isAlumnoInscrito($data['id_alumno'], $data['id_curso'])) {
            return redirect()->back()->with('errors', ['Ya estás inscripto en ese curso.']);
        }

        if (!$m->insert($data)) {
            return redirect()->back()->with('errors', $m->errors())->withInput();
        }

        return redirect()->back()->with('ok', 'Alumno inscrito correctamente');
    }

    public function delete($id)
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->to('login')->with('errors', ['Debes iniciar sesión.']);
        }

        $tipo = $this->request->getPost('tipo') ?? $this->request->getGet('tipo') ?? 'alumno';

        if ($tipo === 'profesor') {
            // Verificar permisos
            if (!$user->inGroup('admin') && !$user->inGroup('profesor')) {
                return redirect()->back()->with('errors', ['No tienes permisos para eliminar esta asignación.']);
            }

            // Si es profesor, verificar que sea su propia inscripción
            if ($user->inGroup('profesor')) {
                $profesorActual = $this->getProfesorActual();
                if ($profesorActual) {
                    $profesorCurso = (new ProfesorCursoModel())->find($id);
                    if ($profesorCurso && (int)$profesorCurso['id_profesor'] !== (int)$profesorActual['id_profesor']) {
                        return redirect()->back()->with('errors', ['Solo puedes eliminar tus propias asignaciones.']);
                    }
                }
            }

            (new ProfesorCursoModel())->delete($id);
            return redirect()->back()->with('ok', 'Asignación de profesor eliminada');
        }

        // Para alumnos
        if (!$user->inGroup('admin') && !$user->inGroup('alumno')) {
            return redirect()->back()->with('errors', ['No tienes permisos para eliminar esta inscripción.']);
        }

        // Si es alumno, verificar que sea su propia inscripción
        if ($user->inGroup('alumno')) {
            $alumnoActual = $this->getAlumnoActual();
            if ($alumnoActual) {
                $alumnoCurso = (new AlumnoCursoModel())->find($id);
                if ($alumnoCurso && (int)$alumnoCurso['id_alumno'] !== (int)$alumnoActual['id_alumno']) {
                    return redirect()->back()->with('errors', ['Solo puedes eliminar tus propias inscripciones.']);
                }
            }
        }

        (new AlumnoCursoModel())->delete($id);
        return redirect()->back()->with('ok', 'Inscripción eliminada');
    }

    private function getAlumnoActual(): ?array
    {
        $user = auth()->user();
        if (!$user) {
            return null;
        }

        return (new AlumnoModel())
            ->where('user_id', $user->id)
            ->first();
    }

    private function getProfesorActual(): ?array
    {
        $user = auth()->user();
        if (!$user) {
            return null;
        }

        return (new ProfesorModel())
            ->where('user_id', $user->id)
            ->first();
    }
}