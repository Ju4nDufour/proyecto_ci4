<?php

namespace App\Controllers;

use App\Models\AlumnoModel;
use App\Models\ProfesorModel;
use CodeIgniter\HTTP\RedirectResponse;
use App\Models\UserModel;
use Config\Services;

class Usuarios extends BaseController
{
    protected UserModel $users;
    protected ProfesorModel $profesores;
    protected AlumnoModel $alumnos;
    protected $authorization;

    protected array $allowedGroups = ['admin', 'profesor', 'alumno'];

    public function __construct()
    {
        $this->users      = auth()->getProvider();
        $this->profesores = new ProfesorModel();
        $this->alumnos    = new AlumnoModel();
        
        try {
            $this->authorization = service('authorization');
            
            if (!$this->authorization) {
                $this->authorization = \Config\Services::authorization();
            }
            
            log_message('info', 'Authorization service cargado: ' . ($this->authorization ? 'OK' : 'FAIL'));
        } catch (\Exception $e) {
            log_message('error', 'Error cargando authorization: ' . $e->getMessage());
            $this->authorization = null;
        }
    }

    public function index()
    {
        $authService = $this->authorization;

        $usuarios = $this->users->orderBy('id', 'DESC')->findAll();
        $userIds = array_map(static fn ($user) => (int) $user->id, $usuarios);

        $profesoresVinculados = [];
        $alumnosVinculados    = [];

        if (! empty($userIds)) {
            $profesoresVinculados = $this->profesores->whereIn('user_id', $userIds)->findAll();
            $alumnosVinculados = $this->alumnos->whereIn('user_id', $userIds)->findAll();
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

        $profesoresSinUsuario = $this->profesores->where('user_id', null)->orderBy('nombre', 'ASC')->findAll();
        $alumnosSinUsuario = $this->alumnos->where('user_id', null)->orderBy('nombre', 'ASC')->findAll();

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
            'username'         => 'required|min_length[3]|max_length[30]',
            'email'            => 'required|valid_email|is_unique[users.email]',
            'password'         => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
            'group'            => 'permit_empty|in_list[admin,profesor,alumno]',
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

        try {
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

            $existingUser = $this->users->where('username', $username)->first();
            if ($existingUser) {
                $username = $username . '_' . time();
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

            $group = null;
            
            if ($personaTipo) {
                $group = $personaTipo;
            }
            
            if (!$group) {
                $groupFromPost = trim(strtolower((string) $this->request->getPost('group')));
                if ($groupFromPost && in_array($groupFromPost, $this->allowedGroups, true)) {
                    $group = $groupFromPost;
                }
            }
            
            if (!$group) {
                $group = 'alumno';
            }

            log_message('info', "Insertando grupo {$group} para usuario {$userId} con SQL directo");
            
            $db->table('auth_groups_users')->where('user_id', $userId)->delete();
            
            $insertResult = $db->table('auth_groups_users')->insert([
                'user_id'    => $userId,
                'group'      => $group,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            
            if (!$insertResult) {
                log_message('error', "ERROR: No se pudo insertar grupo en auth_groups_users");
                $db->transRollback();
                return redirect()->back()->withInput()->with('errors', ['grupo' => 'No se pudo asignar el grupo al usuario.']);
            }
            
            log_message('info', "Grupo {$group} asignado correctamente a usuario {$userId}");

            if ($error = $this->vincularPersona($userId, $personaTipo, $personaId)) {
                $db->transRollback();
                return redirect()->back()->withInput()->with('errors', ['persona' => $error]);
            }

            $db->transCommit();

            return redirect()->to(site_url('usuarios'))->with('ok', 'Usuario creado correctamente.');

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error al crear usuario: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('errors', ['general' => 'Error al crear el usuario: ' . $e->getMessage()]);
        }
    }

    public function update($id): RedirectResponse
    {
        $rules = [
            'username'     => 'required|min_length[3]|max_length[30]',
            'email'        => 'required|valid_email',
            'password'     => 'permit_empty|min_length[8]',
            'group'        => 'permit_empty|in_list[admin,profesor,alumno]',
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

        $email = trim((string) $this->request->getPost('email'));
        $existingUser = $this->users->where('email', $email)->where('id !=', $id)->first();
        if ($existingUser) {
            return redirect()->back()->withInput()->with('errors', ['email' => 'El email ya está en uso por otro usuario.']);
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

        try {
            $username = $this->request->getPost('username');
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

            $group = null;
            
            if ($rawPersonaTipo === 'ninguno') {
                $groupFromPost = trim(strtolower((string) $this->request->getPost('group')));
                if ($groupFromPost && in_array($groupFromPost, $this->allowedGroups, true)) {
                    $group = $groupFromPost;
                }
            } elseif ($personaTipo) {
                $group = $personaTipo;
            } else {
                $groupFromPost = trim(strtolower((string) $this->request->getPost('group')));
                if ($groupFromPost && in_array($groupFromPost, $this->allowedGroups, true)) {
                    $group = $groupFromPost;
                }
            }
            
            if (!$group) {
                $group = 'alumno';
            }

            $db->table('auth_groups_users')->where('user_id', $id)->delete();
            $db->table('auth_groups_users')->insert([
                'user_id'    => $id,
                'group'      => $group,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            log_message('info', "Usuario {$id} actualizado con grupo: {$group}");

            if ($rawPersonaTipo === 'ninguno') {
                $this->desvincularPersona($id);
            } elseif ($error = $this->vincularPersona($id, $personaTipo, $personaId)) {
                $db->transRollback();
                return redirect()->back()->withInput()->with('errors', ['persona' => $error]);
            }

            $db->transCommit();

            return redirect()->to(site_url('usuarios'))->with('ok', 'Usuario actualizado correctamente.');

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error al actualizar usuario: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('errors', ['general' => 'Error al actualizar el usuario: ' . $e->getMessage()]);
        }
    }

    public function delete($id): RedirectResponse
    {
        $user = $this->users->find($id);
        if (! $user) {
            return redirect()->back()->with('errors', ['user' => 'Usuario no encontrado.']);
        }

        $db = db_connect();
        $db->transBegin();

        try {
            $this->desvincularPersona($id);

            if (! $this->users->delete($id, true)) {
                $db->transRollback();
                return redirect()->back()->with('errors', $this->users->errors());
            }

            $db->transCommit();

            return redirect()->to(site_url('usuarios'))->with('ok', 'Usuario eliminado correctamente.');

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error al eliminar usuario: ' . $e->getMessage());
            return redirect()->back()->with('errors', ['general' => 'Error al eliminar el usuario: ' . $e->getMessage()]);
        }
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

            if (! empty($profesor['user_id']) && (int)$profesor['user_id'] !== $userId) {
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

            if (! empty($alumno['user_id']) && (int)$alumno['user_id'] !== $userId) {
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
        $this->profesores->where('user_id', $userId)->set(['user_id' => null])->update();
        $this->alumnos->where('user_id', $userId)->set(['user_id' => null])->update();
    }

    protected function upsertIdentity(int $userId, string $email, ?string $password): void
    {
        $user = $this->users->find($userId);

        if (! $user) {
            return;
        }

        if ($email !== '' && $user->email !== $email) {
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