<?php

namespace App\Controllers;

use App\Models\AlumnoModel;
use App\Models\ProfesorModel;
use CodeIgniter\HTTP\RedirectResponse;
use App\Models\UserModel;
use CodeIgniter\Shield\Models\UserIdentityModel;
use Config\Services;

class Usuarios extends BaseController
{
    protected UserModel $users;
    protected ProfesorModel $profesores;
    protected AlumnoModel $alumnos;
    protected $authorization;

    /**
     * Grupos permitidos desde el panel.
     *
     * @var list<string>
     */
    protected array $allowedGroups = ['admin', 'profesor', 'alumno'];

    public function __construct()
    {
        $this->users         = auth()->getProvider();
        $this->profesores    = new ProfesorModel();
        $this->alumnos       = new AlumnoModel();
        $this->authorization = Services::authorization();
    }

    public function index()
    {
        $authService = $this->authorization;

        $usuarios = $this->users
            ->orderBy('id', 'DESC')
            ->findAll();

        $userIds = array_map(static fn ($user) => (int) $user->id, $usuarios);

        $profesoresVinculados = [];
        $alumnosVinculados    = [];

        if (! empty($userIds)) {
            $profesoresVinculados = $this->profesores
                ->whereIn('user_id', $userIds)
                ->findAll();

            $alumnosVinculados = $this->alumnos
                ->whereIn('user_id', $userIds)
                ->findAll();
        }

        $profesoresPorUsuario = [];
        foreach ($profesoresVinculados as $profesor) {
            $profesoresPorUsuario[$profesor['user_id']] = $profesor;
        }

        $alumnosPorUsuario = [];
        foreach ($alumnosVinculados as $alumno) {
            $alumnosPorUsuario[$alumno['user_id']] = $alumno;
        }

        $gruposDisponibles = $this->prepareGroupOptions($authService);

        $profesoresSinUsuario = $this->profesores
            ->where('user_id', null)
            ->orderBy('nombre', 'ASC')
            ->findAll();

        $alumnosSinUsuario = $this->alumnos
            ->where('user_id', null)
            ->orderBy('nombre', 'ASC')
            ->findAll();

        return view('usuarios/index', [
            'usuarios'             => $usuarios,
            'gruposDisponibles'    => $gruposDisponibles,
            'profesoresSinUsuario' => $profesoresSinUsuario,
            'alumnosSinUsuario'    => $alumnosSinUsuario,
            'authorization'        => $authService,
            'profesoresPorUsuario' => $profesoresPorUsuario,
            'alumnosPorUsuario'    => $alumnosPorUsuario,
        ]);
    }

    public function store(): RedirectResponse
    {
        $rules = [
            'username'         => 'required|min_length[3]',
            'email'            => 'required|valid_email',
            'password'         => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
            'group'            => 'permit_empty',
            'persona_tipo'     => 'permit_empty|in_list[profesor,alumno]',
            'persona_id'       => 'permit_empty|is_natural_no_zero',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $personaTipo = $this->sanitizePersonaTipo($this->request->getPost('persona_tipo'));
        $personaId   = $personaTipo ? (int) $this->request->getPost('persona_id') : 0;

        if ($personaTipo && $personaId <= 0) {
            return redirect()->back()->withInput()->with('errors', ['persona' => 'Debes seleccionar un registro válido para vincular.']);
        }

        $persona = $this->obtenerPersona($personaTipo, $personaId);

        if ($personaTipo && ! $persona) {
            return redirect()->back()->withInput()->with('errors', ['persona' => 'El registro seleccionado no existe.']);
        }

        if ($persona && ! empty($persona['user_id'])) {
            return redirect()->back()->withInput()->with('errors', ['persona' => 'El registro seleccionado ya está vinculado a otro usuario.']);
        }

        $db = db_connect();
        $db->transBegin();

        $username = $this->request->getPost('username');
        $email    = trim((string) $this->request->getPost('email'));
        $password = (string) $this->request->getPost('password');
        $active   = $this->request->getPost('active') ? 1 : 0;

        if ($persona) {
            $username = $this->normalizarUsername($persona['nombre'] ?? $persona['dni'] ?? $username);
            $personaEmail = $this->normalizeEmail($persona['email'] ?? null);
            if ($personaEmail !== null) {
                $email = $personaEmail;
            }
        } else {
            $username = $this->normalizarUsername($username ?? '');
        }

        if ($email === '') {
            $db->transRollback();
            return redirect()->back()->withInput()->with('errors', ['email' => 'Debes indicar un email válido para el usuario.']);
        }

        $userData = [
            'username' => $username,
            'email'    => $email,
            'active'   => $active,
        ];

        $userId = $this->users->insert($userData);

        if (! $userId) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('errors', $this->users->errors());
        }

        $this->upsertIdentity($userId, $email, $password);

        $group = $personaTipo ?? strtolower((string) $this->request->getPost('group'));
        $group = $group ?: null;
        if ($group && ! in_array($group, $this->allowedGroups, true)) {
            $group = null;
        }

        if ($group && $this->authorization) {
            $this->authorization->addUserToGroup($userId, $group);
        }

        if ($error = $this->vincularPersona($userId, $personaTipo, $personaId)) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('errors', ['persona' => $error]);
        }

        $db->transCommit();

        return redirect()->to(site_url('usuarios'))->with('ok', 'Usuario creado correctamente.');
    }

    public function update($id): RedirectResponse
    {
        $rules = [
            'username'     => 'required|min_length[3]',
            'email'        => 'required|valid_email',
            'password'     => 'permit_empty|min_length[8]',
            'group'        => 'permit_empty',
            'persona_tipo' => 'permit_empty|in_list[profesor,alumno,ninguno]',
            'persona_id'   => 'permit_empty|is_natural_no_zero',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $user = $this->users->find($id);

        if (! $user) {
            return redirect()->back()->with('errors', ['user' => 'Usuario no encontrado.']);
        }

        $rawPersonaTipo = $this->request->getPost('persona_tipo');
        $personaTipo    = $this->sanitizePersonaTipo($rawPersonaTipo);
        $personaId      = $personaTipo ? (int) $this->request->getPost('persona_id') : 0;

        if ($personaTipo && $personaId <= 0) {
            return redirect()->back()->withInput()->with('errors', ['persona' => 'Debes seleccionar un registro válido para vincular.']);
        }

        $persona = $this->obtenerPersona($personaTipo, $personaId);

        if ($personaTipo && ! $persona) {
            return redirect()->back()->withInput()->with('errors', ['persona' => 'El registro seleccionado no existe.']);
        }

        if ($persona && ! empty($persona['user_id']) && (int) $persona['user_id'] !== (int) $id) {
            return redirect()->back()->withInput()->with('errors', ['persona' => 'El registro seleccionado está vinculado a otro usuario.']);
        }

        $db = db_connect();
        $db->transBegin();

        $username = $this->request->getPost('username');
        $email    = trim((string) $this->request->getPost('email'));
        $password = (string) $this->request->getPost('password');
        $active   = $this->request->getPost('active') ? 1 : 0;

        if ($persona) {
            $username = $this->normalizarUsername($persona['nombre'] ?? $persona['dni'] ?? $username);
            $personaEmail = $this->normalizeEmail($persona['email'] ?? null);
            if ($personaEmail !== null) {
                $email = $personaEmail;
            }
        } else {
            $username = $this->normalizarUsername($username ?? '');
        }

        if ($email === '') {
            $db->transRollback();
            return redirect()->back()->withInput()->with('errors', ['email' => 'Debes indicar un email válido para el usuario.']);
        }

        if (! $this->users->update($id, [
            'username' => $username,
            'email'    => $email,
            'active'   => $active,
        ])) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('errors', $this->users->errors());
        }

        $this->upsertIdentity($id, $email, $password !== '' ? $password : null);

        $group = $personaTipo ?? strtolower((string) $this->request->getPost('group'));
        $group = $rawPersonaTipo === 'ninguno' ? strtolower((string) $this->request->getPost('group')) : $group;
        $group = $group ?: null;

        if ($group && ! in_array($group, $this->allowedGroups, true)) {
            $group = null;
        }

        if ($this->authorization) {
            $this->authorization->removeUserFromAllGroups($id);
            if ($group) {
                $this->authorization->addUserToGroup($id, $group);
            }
        }

        if ($error = $this->vincularPersona($id, $personaTipo, $personaId)) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('errors', ['persona' => $error]);
        }

        $db->transCommit();

        return redirect()->to(site_url('usuarios'))->with('ok', 'Usuario actualizado correctamente.');
    }

    public function delete($id): RedirectResponse
    {
        $user = $this->users->find($id);
        if (! $user) {
            return redirect()->back()->with('errors', ['user' => 'Usuario no encontrado.']);
        }

        $db = db_connect();
        $db->transBegin();

        $this->desvincularPersona($id);

        if (! $this->users->delete($id, true)) {
            $db->transRollback();
            return redirect()->back()->with('errors', $this->users->errors());
        }

        $db->transCommit();

        return redirect()->to(site_url('usuarios'))->with('ok', 'Usuario eliminado correctamente.');
    }

    protected function vincularPersona(int $userId, ?string $tipo, int $id): ?string
    {
        if (! $tipo || $id <= 0) {
            $this->desvincularPersona($userId);
            return null;
        }

        $this->desvincularPersona($userId);

        if ($tipo === 'profesor') {
            $profesor = $this->profesores->find($id);
            if (! $profesor) {
                return 'Profesor seleccionado no existe.';
            }

            if (! empty($profesor['user_id'])) {
                return 'El profesor ya está vinculado a otro usuario.';
            }

            if (! $this->profesores->update($id, ['user_id' => $userId])) {
                return 'No se pudo vincular el profesor seleccionado.';
            }
        }

        if ($tipo === 'alumno') {
            $alumno = $this->alumnos->find($id);
            if (! $alumno) {
                return 'Alumno seleccionado no existe.';
            }

            if (! empty($alumno['user_id'])) {
                return 'El alumno ya está vinculado a otro usuario.';
            }

            if (! $this->alumnos->update($id, ['user_id' => $userId])) {
                return 'No se pudo vincular el alumno seleccionado.';
            }
        }

        return null;
    }

    protected function desvincularPersona(int $userId): void
    {
        $this->profesores
            ->where('user_id', $userId)
            ->set(['user_id' => null])
            ->update();

        $this->alumnos
            ->where('user_id', $userId)
            ->set(['user_id' => null])
            ->update();
    }

    protected function upsertIdentity(int $userId, string $email, ?string $password): void
    {
        /** @var \CodeIgniter\Shield\Entities\User|null $user */
        $user = $this->users->find($userId);

        if (! $user) {
            return;
        }

        if ($email !== '') {
            $user->email = $email;
        }

        if ($password !== null && $password !== '') {
            $user->password = $password;
        }

        $this->users->save($user);
    }

    protected function prepareGroupOptions($authService): array
    {
        $options = [];

        if ($authService) {
            foreach ($authService->groups()->findAll() as $group) {
                $name = strtolower($group->name);
                if (! in_array($name, $this->allowedGroups, true)) {
                    continue;
                }
                $options[] = (object) [
                    'name'       => $group->name,
                    'normalized' => $name,
                    'label'      => ucfirst($group->name),
                ];
            }
        }

        if ($options === []) {
            foreach ($this->allowedGroups as $name) {
                $options[] = (object) [
                    'name'       => $name,
                    'normalized' => $name,
                    'label'      => ucfirst($name),
                ];
            }
        }

        return $options;
    }

    protected function normalizeEmail(?string $email): ?string
    {
        $email = trim((string) ($email ?? ''));
        if ($email === '') {
            return null;
        }

        return filter_var($email, FILTER_VALIDATE_EMAIL) ?: null;
    }

    protected function sanitizePersonaTipo($tipo): ?string
    {
        $tipo = is_string($tipo) ? strtolower(trim($tipo)) : null;

        return in_array($tipo, ['profesor', 'alumno'], true) ? $tipo : null;
    }

    protected function obtenerPersona(?string $tipo, int $id): ?array
    {
        if (! $tipo || $id <= 0) {
            return null;
        }

        return match ($tipo) {
            'profesor' => $this->profesores->find($id),
            'alumno'   => $this->alumnos->find($id),
            default    => null,
        };
    }

    protected function normalizarUsername(?string $valor): string
    {
        $valor = trim((string) ($valor ?? ''));

        if ($valor === '') {
            return 'usuario_' . time();
        }

        $ascii = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $valor);
        if (! is_string($ascii) || $ascii === '') {
            $ascii = $valor;
        }

        $ascii = strtolower($ascii);
        $ascii = preg_replace('/[^a-z0-9]+/', '.', $ascii);
        $ascii = trim((string) $ascii, '.');

        if ($ascii !== '') {
            return $ascii;
        }

        $fallback = preg_replace('/\s+/', '.', strtolower($valor));
        $fallback = trim((string) $fallback, '.');

        return $fallback !== '' ? $fallback : 'usuario_' . time();
    }
}


