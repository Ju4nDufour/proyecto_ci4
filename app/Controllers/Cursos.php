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
            'cursosPorCarrera' => $cursosPorCarrera,
            'carreras' => $carreras,
            'errors' => session('errors'),
            'ok' => session('ok'),
        ]);
    }
    public function update($id)
    {
        $cursoModel = new CursoModel();
        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'id_carrera' => $this->request->getPost('id_carrera'),
        ];
        $rules = [
            'nombre' => 'required|min_length[3]',
            'id_carrera' => 'required|is_natural_no_zero',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors())->withInput();
        }
        if (!$cursoModel->update($id, $data)) {
            return redirect()->back()->with('errors', $cursoModel->errors())->withInput();
        }
        return redirect()->to(site_url('cursos'))->with('ok', 'Curso actualizado');
    }

    public function delete($id)
    {
        $cursoModel = new CursoModel();
        try {
            if (!$cursoModel->delete($id)) {
                return redirect()->back()->with('errors', $cursoModel->errors());
            }
            return redirect()->to(site_url('cursos'))->with('ok', 'Curso eliminado');
        } catch (\Throwable $e) {
            return redirect()->back()->with('errors', ['No se puede eliminar: el curso tiene inscripciones asociadas.']);
        }
    }


public function store()
{
    $cursoModel = new CursoModel();
    $post = $this->request->getPost();

    // 1️⃣ Generar prefijo de 4 letras basado en el nombre
    $nombre = strtoupper($post['nombre']);
    $prefijo = substr(preg_replace('/[^A-Z]/', '', $nombre), 0, 4);
    $prefijo = str_pad($prefijo, 4, 'X'); // completa si el nombre es corto

    // 2️⃣ Buscar el último código existente con ese prefijo
    $ultimo = $cursoModel
        ->select('codigo')
        ->where('UPPER(LEFT(codigo, 4))', $prefijo)
        ->orderBy('codigo', 'DESC')
        ->first();

    // 3️⃣ Calcular el siguiente número
    $contador = 1;
    if ($ultimo && preg_match('/(\d+)$/', $ultimo['codigo'], $m)) {
        $contador = (int)$m[1] + 1;
    }

    // 4️⃣ Generar código final (ej. PROG1)
    $codigoFinal = $prefijo . $contador;

    // 5️⃣ Verificar si el código ya existe (seguridad extra)
    while ($cursoModel->where('codigo', $codigoFinal)->first()) {
        $contador++;
        $codigoFinal = $prefijo . $contador;
    }

    // 6️⃣ Insertar el curso
    $post['codigo'] = $codigoFinal;

    if (!$cursoModel->insert($post, true)) {
        return redirect()->back()->with('errors', $cursoModel->errors())->withInput();
    }

    return redirect()->to(site_url('cursos'))->with('ok', 'Nuevo curso agregado correctamente');
}


}

