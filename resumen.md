# Flujo de Registro de Usuario Alumno - Sistema Académico CI4

## Índice
1. [Visión General](#visión-general)
2. [Flujo Completo del Registro](#flujo-completo-del-registro)
3. [Arquitectura y Componentes](#arquitectura-y-componentes)
4. [Proceso Detallado Paso a Paso](#proceso-detallado-paso-a-paso)
5. [Flujo de Datos entre Controladores](#flujo-de-datos-entre-controladores)
6. [Validaciones y Seguridad](#validaciones-y-seguridad)
7. [Diagrama de Flujo Simplificado](#diagrama-de-flujo-simplificado)

---

## Visión General

El sistema implementa **dos formas de crear usuarios alumno**:

### 1. Registro Público (Shield)
- Usuario se auto-registra desde el formulario público `/register`
- Se crea automáticamente con grupo `alumno`
- **NO** se vincula automáticamente a un registro de alumno en la tabla `alumno`

### 2. Creación Administrativa (Desde Panel Admin)
- Un administrador crea el usuario desde `/usuarios`
- Permite vincular al usuario con un registro existente en la tabla `alumno`
- Control total sobre grupo, estado activo y vinculación

---

## Flujo Completo del Registro

```
OPCIÓN 1: Auto-registro Público (Shield)
┌─────────────┐
│   Usuario   │
└──────┬──────┘
       │
       ▼
[GET /register] → RegisterController::registerView()
       │
       │ (Muestra formulario de registro)
       │
       ▼
[POST /register] → RegisterController::registerAction()
       │
       ├─ Valida datos (email, username, password)
       ├─ Crea registro en tabla `users`
       ├─ Crea identidad en tabla `auth_identities`
       ├─ Asigna grupo por defecto: "alumno"
       ├─ Activa usuario
       └─ Login automático → Redirige a Dashboard
```

```
OPCIÓN 2: Creación Administrativa
┌─────────────┐
│    Admin    │
└──────┬──────┘
       │
       ▼
[GET /usuarios] → Usuarios::index()
       │
       │ (Muestra formulario y listados)
       │
       ▼
[POST /usuarios] → Usuarios::store()
       │
       ├─ Valida datos
       ├─ Crea usuario en tabla `users`
       ├─ Crea identidad en tabla `auth_identities`
       ├─ Asigna grupo seleccionado
       └─ VINCULA con registro de tabla `alumno`
           (actualiza campo `user_id` en alumno)
```

---

## Arquitectura y Componentes

### Tablas Involucradas

#### 1. **`users`** (Shield - Autenticación)
```
Campos principales:
- id (PK)
- username
- email
- status
- active (1 = activo, 0 = inactivo)
- last_active
- created_at
- updated_at
```

#### 2. **`auth_identities`** (Shield - Credenciales)
```
Campos principales:
- id (PK)
- user_id (FK → users.id)
- type ('email_password')
- secret (password hasheado)
- created_at
```

#### 3. **`auth_groups_users`** (Shield - Roles)
```
Campos principales:
- id (PK)
- user_id (FK → users.id)
- group ('admin', 'profesor', 'alumno')
- created_at
```

#### 4. **`alumno`** (Datos Académicos)
```
Campos principales:
- id_alumno (PK)
- dni (8 dígitos, único)
- nombre
- email
- fecha_nac
- id_carrera (FK → carrera.id_carrera)
- user_id (FK → users.id) ← VINCULACIÓN CLAVE
```

---

## Proceso Detallado Paso a Paso

### OPCIÓN 1: Auto-registro Público (Shield)

#### Paso 1: Usuario accede a `/register`
**Archivo:** `vendor/codeigniter4/shield/src/Controllers/RegisterController.php`
**Método:** `registerView()` (línea 56)

```php
public function registerView()
{
    // Verifica si ya está logueado
    if (auth()->loggedIn()) {
        return redirect()->to(config('Auth')->registerRedirect());
    }

    // Verifica si el registro está habilitado
    if (!setting('Auth.allowRegistration')) {
        return redirect()->back()->with('error', lang('Auth.registerDisabled'));
    }

    // Muestra la vista de registro
    return $this->view(setting('Auth.views')['register']);
}
```

**Configuración en:** `app/Config/Auth.php`
- `$allowRegistration = true` (línea 160)
- Vista: `\CodeIgniter\Shield\Views\register` (línea 50)

---

#### Paso 2: Usuario completa formulario
**Campos del formulario:**
- Email (requerido, validación de formato)
- Username (requerido, min 3 caracteres, max 30, alfanumérico + punto)
- Password (requerido, min 8 caracteres)
- Password Confirm (debe coincidir)

---

#### Paso 3: Usuario envía formulario [POST /register]
**Archivo:** `vendor/codeigniter4/shield/src/Controllers/RegisterController.php`
**Método:** `registerAction()` (línea 82)

**Flujo interno:**

```php
public function registerAction(): RedirectResponse
{
    // 1. VALIDACIÓN
    $rules = $this->getValidationRules();
    if (!$this->validateData($this->request->getPost(), $rules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    // 2. OBTIENE EL USER PROVIDER
    $users = $this->getUserProvider(); // App\Models\UserModel

    // 3. CREA NUEVO USUARIO (entidad)
    $user = $users->createNewUser($this->request->getPost($allowedPostFields));

    // 4. GUARDA EN BASE DE DATOS
    try {
        $users->save($user); // Inserta en tabla `users`
    } catch (ValidationException) {
        return redirect()->back()->withInput()->with('errors', $users->errors());
    }

    // 5. OBTIENE EL ID INSERTADO
    $user = $users->findById($users->getInsertID());

    // 6. ASIGNA GRUPO POR DEFECTO
    $users->addToDefaultGroup($user); // 'alumno' según AuthGroups.php

    // 7. DISPARA EVENTO
    Events::trigger('register', $user);

    // 8. INICIA SESIÓN AUTOMÁTICAMENTE
    $authenticator = auth('session')->getAuthenticator();
    $authenticator->startLogin($user);

    // 9. ACTIVA USUARIO
    $user->activate();

    // 10. COMPLETA LOGIN
    $authenticator->completeLogin($user);

    // 11. REDIRIGE AL DASHBOARD
    return redirect()->to(config('Auth')->registerRedirect())
        ->with('message', lang('Auth.registerSuccess'));
}
```

---

#### Paso 4: Asignación de Grupo por Defecto
**Archivo:** `app/Config/AuthGroups.php`
**Configuración:**

```php
public string $defaultGroup = 'alumno'; // línea 14
```

**Resultado:**
- Se inserta registro en tabla `auth_groups_users`
- `user_id` = ID del usuario creado
- `group` = 'alumno'

---

#### Paso 5: Redirección al Dashboard
**Archivo:** `app/Config/Auth.php` (línea 77)
```php
public array $redirects = [
    'register' => '/', // Dashboard
];
```

---

### OPCIÓN 2: Creación Administrativa

#### Paso 1: Admin accede a `/usuarios`
**Archivo:** `app/Controllers/Usuarios.php`
**Método:** `index()` (línea 34)

**Ruta protegida:**
```php
// app/Config/Routes.php (línea 23)
$routes->get('usuarios', 'Usuarios::index');
// Filtro: 'group:admin' (solo administradores)
```

**Lo que hace el método:**

```php
public function index()
{
    // 1. Obtiene todos los usuarios
    $usuarios = $this->users->orderBy('id', 'DESC')->findAll();

    // 2. Busca profesores y alumnos vinculados
    $profesoresVinculados = $this->profesores->whereIn('user_id', $userIds)->findAll();
    $alumnosVinculados = $this->alumnos->whereIn('user_id', $userIds)->findAll();

    // 3. Busca profesores y alumnos SIN usuario
    $profesoresSinUsuario = $this->profesores->where('user_id', null)->findAll();
    $alumnosSinUsuario = $this->alumnos->where('user_id', null)->findAll();

    // 4. Prepara grupos disponibles (admin, profesor, alumno)
    $gruposDisponibles = $this->prepareGroupOptions($authService);

    // 5. Renderiza la vista
    return view('usuarios/index', [
        'usuarios' => $usuarios,
        'gruposDisponibles' => $gruposDisponibles,
        'profesoresSinUsuario' => $profesoresSinUsuario,
        'alumnosSinUsuario' => $alumnosSinUsuario,
        // ... más datos
    ]);
}
```

---

#### Paso 2: Admin completa el formulario
**Archivo:** `app/Views/usuarios/index.php` (línea 33)

**Campos del formulario:**

1. **Username** (readonly, se autocompleta desde JavaScript)
2. **Email** (readonly, se autocompleta desde JavaScript)
3. **Password** (requerido, min 8 caracteres)
4. **Password Confirm** (requerido)
5. **Grupo** (select: admin, profesor, alumno)
6. **Activo** (checkbox)
7. **Vincular con registro:**
   - Tipo (select: Profesor, Alumno, No vincular)
   - Registro (select dinámico según tipo)

**JavaScript Automático (línea 302-431):**
- Al seleccionar un alumno de la lista:
  - **Auto-completa username** con el nombre normalizado (sin tildes, minúsculas, puntos)
  - **Auto-completa email** desde el registro del alumno
  - **Asigna automáticamente grupo "alumno"**
  - **Bloquea el campo grupo** para evitar cambios

```javascript
// Ejemplo de auto-completado:
// Alumno seleccionado: "María José González"
// Username generado: "maria.jose.gonzalez"
// Email: desde alumno.email
// Grupo: "alumno" (bloqueado)
```

---

#### Paso 3: Admin envía formulario [POST /usuarios]
**Archivo:** `app/Controllers/Usuarios.php`
**Método:** `store()` (línea 90)

**Flujo detallado:**

```php
public function store(): RedirectResponse
{
    // ====== 1. VALIDACIÓN DE DATOS ======
    $rules = [
        'username'         => 'required|min_length[3]',
        'email'            => 'required|valid_email',
        'password'         => 'required|min_length[8]',
        'password_confirm' => 'required|matches[password]',
        'group'            => 'permit_empty',
        'persona_tipo'     => 'permit_empty|in_list[profesor,alumno]',
        'persona_id'       => 'permit_empty|is_natural_no_zero',
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()
            ->with('errors', $this->validator->getErrors());
    }

    // ====== 2. PROCESA VINCULACIÓN ======
    $personaTipo = $this->request->getPost('persona_tipo'); // 'alumno'
    $personaId   = (int) $this->request->getPost('persona_id'); // ID del alumno

    // Obtiene el registro del alumno
    $persona = $this->obtenerPersona($personaTipo, $personaId);

    // Valida que no esté ya vinculado
    if ($persona && !empty($persona['user_id'])) {
        return redirect()->back()->with('errors',
            ['persona' => 'El registro seleccionado ya está vinculado a otro usuario.']);
    }

    // ====== 3. INICIA TRANSACCIÓN ======
    $db = db_connect();
    $db->transBegin();

    // ====== 4. NORMALIZA USERNAME Y EMAIL ======
    if ($persona) {
        // Genera username desde el nombre del alumno
        $username = $this->normalizarUsername($persona['nombre']);
        // Ejemplo: "Juan Pérez" → "juan.perez"

        // Usa el email del alumno
        $email = $this->normalizeEmail($persona['email']);
    }

    // ====== 5. CREA USUARIO EN TABLA `users` ======
    $userData = [
        'username' => $username,
        'email'    => $email,
        'active'   => $this->request->getPost('active') ? 1 : 0,
    ];

    $userId = $this->users->insert($userData);

    if (!$userId) {
        $db->transRollback();
        return redirect()->back()->with('errors', $this->users->errors());
    }

    // ====== 6. CREA IDENTIDAD (PASSWORD) ======
    $this->upsertIdentity($userId, $email, $password);
    // Inserta en tabla `auth_identities` con password hasheado

    // ====== 7. ASIGNA GRUPO ======
    $group = $personaTipo ?? $this->request->getPost('group');
    // Si vinculó con alumno, grupo = 'alumno' automáticamente

    if ($group && $this->authorization) {
        $this->authorization->addUserToGroup($userId, $group);
        // Inserta en tabla `auth_groups_users`
    }

    // ====== 8. VINCULA CON TABLA ALUMNO ======
    if ($error = $this->vincularPersona($userId, $personaTipo, $personaId)) {
        $db->transRollback();
        return redirect()->back()->with('errors', ['persona' => $error]);
    }
    // Actualiza alumno.user_id = $userId

    // ====== 9. CONFIRMA TRANSACCIÓN ======
    $db->transCommit();

    // ====== 10. REDIRIGE CON MENSAJE ======
    return redirect()->to(site_url('usuarios'))
        ->with('ok', 'Usuario creado correctamente.');
}
```

---

#### Paso 4: Método de Vinculación
**Archivo:** `app/Controllers/Usuarios.php`
**Método:** `vincularPersona()` (línea 300)

```php
protected function vincularPersona(int $userId, ?string $tipo, int $id): ?string
{
    if (!$tipo || $id <= 0) {
        $this->desvincularPersona($userId);
        return null;
    }

    // Primero desvincula cualquier relación previa
    $this->desvincularPersona($userId);

    if ($tipo === 'alumno') {
        // 1. Busca el alumno
        $alumno = $this->alumnos->find($id);

        if (!$alumno) {
            return 'Alumno seleccionado no existe.';
        }

        // 2. Verifica que no esté vinculado
        if (!empty($alumno['user_id'])) {
            return 'El alumno ya está vinculado a otro usuario.';
        }

        // 3. ACTUALIZA EL CAMPO user_id EN LA TABLA alumno
        if (!$this->alumnos->update($id, ['user_id' => $userId])) {
            return 'No se pudo vincular el alumno seleccionado.';
        }
    }

    return null; // Éxito
}
```

**Resultado:**
```sql
UPDATE alumno
SET user_id = [ID_DEL_USUARIO_CREADO]
WHERE id_alumno = [ID_DEL_ALUMNO_SELECCIONADO];
```

---

## Flujo de Datos entre Controladores

### Controladores Involucrados

#### 1. **RegisterController** (Shield - Vendor)
- **Ubicación:** `vendor/codeigniter4/shield/src/Controllers/RegisterController.php`
- **Responsabilidad:** Registro público de usuarios
- **Métodos principales:**
  - `registerView()` - Muestra formulario
  - `registerAction()` - Procesa registro

#### 2. **Usuarios** (Aplicación)
- **Ubicación:** `app/Controllers/Usuarios.php`
- **Responsabilidad:** Gestión administrativa de usuarios
- **Métodos principales:**
  - `index()` - Listado y formulario de creación
  - `store()` - Crea usuario con vinculación
  - `update()` - Actualiza usuario
  - `delete()` - Elimina usuario
  - `vincularPersona()` - Vincula usuario con alumno/profesor
  - `desvincularPersona()` - Remueve vinculación

### Modelos Involucrados

#### 1. **UserModel** (Shield extendido)
- **Ubicación:** `app/Models/UserModel.php`
- **Tabla:** `users`
- **Funciones:**
  - Crear usuarios
  - Actualizar usuarios
  - Gestionar identidades (passwords)
  - Asignar grupos

#### 2. **AlumnoModel**
- **Ubicación:** `app/Models/AlumnoModel.php`
- **Tabla:** `alumno`
- **Funciones:**
  - CRUD de alumnos
  - Validación de DNI (8 dígitos)
  - Validación de email
  - Gestión de campo `user_id`

---

## Validaciones y Seguridad

### Validaciones en Registro Público (Shield)

#### Username (Config: `app/Config/Auth.php`, línea 225)
```php
public array $usernameValidationRules = [
    'label' => 'Auth.username',
    'rules' => [
        'required',
        'max_length[30]',
        'min_length[3]',
        'regex_match[/\A[a-zA-Z0-9\.]+\z/]', // Solo letras, números y punto
    ],
];
```

#### Email (línea 244)
```php
public array $emailValidationRules = [
    'label' => 'Auth.email',
    'rules' => [
        'required',
        'max_length[254]',
        'valid_email',
    ],
];
```

#### Password (línea 260)
```php
public int $minimumPasswordLength = 8;
```

**Validadores adicionales:**
- `CompositionValidator` - Verifica complejidad
- `NothingPersonalValidator` - Evita datos personales
- `DictionaryValidator` - Evita palabras comunes

---

### Validaciones en Creación Administrativa

#### Usuario (Usuarios.php, línea 92)
```php
$rules = [
    'username'         => 'required|min_length[3]',
    'email'            => 'required|valid_email',
    'password'         => 'required|min_length[8]',
    'password_confirm' => 'required|matches[password]',
    'group'            => 'permit_empty',
    'persona_tipo'     => 'permit_empty|in_list[profesor,alumno]',
    'persona_id'       => 'permit_empty|is_natural_no_zero',
];
```

#### Alumno (AlumnoModel.php, línea 13)
```php
protected $validationRules = [
    'dni'        => 'required|exact_length[8]|is_natural_no_zero',
    'nombre'     => 'required|min_length[3]',
    'email'      => 'permit_empty|valid_email',
    'fecha_nac'  => 'permit_empty|valid_date',
    'id_carrera' => 'required|is_natural_no_zero',
];
```

---

### Seguridad Implementada

#### 1. **Protección CSRF**
```php
<?= csrf_field() ?> // En todos los formularios
```

#### 2. **Password Hashing**
- Automático por Shield
- Algoritmo: `PASSWORD_DEFAULT` (bcrypt)
- Cost: 12 (Auth.php, línea 379)

#### 3. **Filtros de Rutas**
```php
// Routes.php (línea 21)
$routes->group('', ['filter' => 'group:admin'], function() {
    $routes->get('usuarios', 'Usuarios::index');
});
```

#### 4. **Transacciones de Base de Datos**
```php
$db->transBegin();
// ... operaciones ...
if (error) {
    $db->transRollback();
} else {
    $db->transCommit();
}
```

#### 5. **Validación de Vinculación**
- Verifica que el alumno exista
- Verifica que NO esté vinculado a otro usuario
- Previene vinculaciones duplicadas

---

## Diagrama de Flujo Simplificado

### OPCIÓN 1: Auto-registro (Shield)

```
┌────────────────────────────────────────────────────────────────┐
│                        REGISTRO PÚBLICO                        │
└────────────────────────────────────────────────────────────────┘

    Usuario sin cuenta
           ↓
    [GET /register] → RegisterController::registerView()
           ↓
    Muestra formulario de registro
    (email, username, password)
           ↓
    Usuario completa formulario
           ↓
    [POST /register] → RegisterController::registerAction()
           ↓
    ┌─────────────────────────────────────┐
    │ 1. Valida datos de entrada          │
    └─────────────────────────────────────┘
           ↓
    ┌─────────────────────────────────────┐
    │ 2. Crea usuario en tabla `users`    │
    │    - username                        │
    │    - email                           │
    │    - active = 1                      │
    └─────────────────────────────────────┘
           ↓
    ┌──────────────────────────────────────┐
    │ 3. Crea identidad en                 │
    │    `auth_identities`                 │
    │    - type: 'email_password'          │
    │    - secret: password hasheado       │
    └──────────────────────────────────────┘
           ↓
    ┌──────────────────────────────────────┐
    │ 4. Asigna grupo en                   │
    │    `auth_groups_users`               │
    │    - group: 'alumno' (por defecto)   │
    └──────────────────────────────────────┘
           ↓
    ┌─────────────────────────────────────┐
    │ 5. Login automático                  │
    └─────────────────────────────────────┘
           ↓
    Redirige a Dashboard (/)
           ↓
    ┌─────────────────────────────────────┐
    │ RESULTADO:                           │
    │ - Usuario creado ✓                   │
    │ - Grupo asignado: alumno ✓           │
    │ - Sesión iniciada ✓                  │
    │ - NO vinculado a tabla alumno ✗      │
    └─────────────────────────────────────┘
```

---

### OPCIÓN 2: Creación Administrativa

```
┌────────────────────────────────────────────────────────────────┐
│                    CREACIÓN ADMINISTRATIVA                     │
└────────────────────────────────────────────────────────────────┘

    Admin logueado (grupo: admin)
           ↓
    [GET /usuarios] → Usuarios::index()
           ↓
    Carga datos:
    - Todos los usuarios existentes
    - Alumnos SIN usuario vinculado
    - Profesores SIN usuario vinculado
    - Grupos disponibles
           ↓
    Renderiza vista con formulario
           ↓
    ┌──────────────────────────────────────┐
    │ Admin selecciona alumno de la lista  │
    │ → JavaScript auto-completa:          │
    │   • username (normalizado)           │
    │   • email (desde alumno.email)       │
    │   • grupo → "alumno" (bloqueado)     │
    └──────────────────────────────────────┘
           ↓
    Admin ingresa password y envía
           ↓
    [POST /usuarios] → Usuarios::store()
           ↓
    ┌─────────────────────────────────────┐
    │ 1. Valida datos del formulario      │
    └─────────────────────────────────────┘
           ↓
    ┌─────────────────────────────────────┐
    │ 2. Valida vinculación:               │
    │    - Alumno existe?                  │
    │    - Ya está vinculado?              │
    └─────────────────────────────────────┘
           ↓ SI es válido
    ┌─────────────────────────────────────┐
    │ 3. INICIA TRANSACCIÓN                │
    └─────────────────────────────────────┘
           ↓
    ┌─────────────────────────────────────┐
    │ 4. Normaliza username desde nombre  │
    │    "María José" → "maria.jose"      │
    └─────────────────────────────────────┘
           ↓
    ┌─────────────────────────────────────┐
    │ 5. Inserta en tabla `users`:        │
    │    - username: "maria.jose"         │
    │    - email: "maria@example.com"     │
    │    - active: 1                       │
    │    → Retorna $userId                 │
    └─────────────────────────────────────┘
           ↓
    ┌─────────────────────────────────────┐
    │ 6. Crea identidad (password):       │
    │    INSERT INTO auth_identities      │
    │    - user_id: $userId                │
    │    - type: 'email_password'          │
    │    - secret: hash(password)          │
    └─────────────────────────────────────┘
           ↓
    ┌─────────────────────────────────────┐
    │ 7. Asigna grupo:                     │
    │    INSERT INTO auth_groups_users    │
    │    - user_id: $userId                │
    │    - group: 'alumno'                 │
    └─────────────────────────────────────┘
           ↓
    ┌─────────────────────────────────────┐
    │ 8. VINCULA CON ALUMNO:               │
    │    UPDATE alumno                     │
    │    SET user_id = $userId             │
    │    WHERE id_alumno = [ID_ALUMNO]     │
    └─────────────────────────────────────┘
           ↓ Error?
         /   \
      SI /     \ NO
        ↓       ↓
    ROLLBACK  COMMIT
        ↓       ↓
    Error    Éxito
               ↓
    Redirige a /usuarios con mensaje de éxito
               ↓
    ┌─────────────────────────────────────┐
    │ RESULTADO:                           │
    │ - Usuario creado ✓                   │
    │ - Grupo asignado: alumno ✓           │
    │ - Vinculado a tabla alumno ✓         │
    │ - admin puede ver la relación en UI  │
    └─────────────────────────────────────┘
```

---

## Relación entre Tablas (Resultado Final)

```
┌────────────────────┐         ┌──────────────────────┐
│      users         │         │   auth_identities    │
├────────────────────┤         ├──────────────────────┤
│ id (PK)            │←───────│ user_id (FK)         │
│ username           │         │ type                 │
│ email              │         │ secret (password)    │
│ active             │         └──────────────────────┘
│ created_at         │
└────────────────────┘
         ↑                      ┌──────────────────────┐
         │                      │  auth_groups_users   │
         │                      ├──────────────────────┤
         └──────────────────────│ user_id (FK)         │
                                │ group ('alumno')     │
                                └──────────────────────┘
         ↑
         │
         │ (VINCULACIÓN)
         │
┌────────────────────┐
│      alumno        │
├────────────────────┤
│ id_alumno (PK)     │
│ dni                │
│ nombre             │
│ email              │
│ fecha_nac          │
│ id_carrera (FK)    │
│ user_id (FK) ─────┘
└────────────────────┘
```

---

## Resumen de Diferencias Clave

| Aspecto | Registro Público (Shield) | Creación Admin |
|---------|--------------------------|----------------|
| **Ruta** | `/register` | `/usuarios` |
| **Controlador** | `RegisterController` (vendor) | `Usuarios` (app) |
| **Acceso** | Público (sin login) | Protegido (solo admin) |
| **Grupo asignado** | `alumno` (por defecto) | Seleccionable (admin, profesor, alumno) |
| **Vinculación** | NO se vincula automáticamente | SÍ se vincula con tabla `alumno` |
| **Auto-login** | Sí, automático | No, admin no inicia sesión como el usuario |
| **Username** | Ingresado manualmente | Auto-generado desde nombre |
| **Email** | Ingresado manualmente | Auto-completado desde `alumno.email` |
| **Transacción DB** | Maneja Shield internamente | Explícita con rollback |
| **Campo user_id en alumno** | Queda NULL | Se actualiza con el ID del usuario |

---

## Consideraciones Importantes para la Exposición

### 1. **Dos flujos completamente distintos**
- Shield maneja el registro público de forma independiente
- El sistema admin permite control total y vinculación

### 2. **Ventaja de la creación administrativa**
- Usuario queda inmediatamente vinculado al alumno
- Permite identificar qué usuario corresponde a qué alumno
- Facilita auditoría y gestión

### 3. **Campo clave: `user_id` en tabla `alumno`**
- Es la FK que vincula autenticación con datos académicos
- Permite consultas JOIN entre usuarios y alumnos
- NULL = alumno sin usuario, valor = alumno vinculado

### 4. **Transacciones garantizan integridad**
- Si falla cualquier paso, se hace rollback completo
- No quedan usuarios huérfanos o alumnos mal vinculados

### 5. **JavaScript mejora UX**
- Auto-completado reduce errores
- Bloqueo de grupo asegura consistencia
- Normalización de username automática

### 6. **Seguridad en múltiples capas**
- Filtros de ruta (solo admin puede crear)
- Validaciones de datos (backend)
- CSRF protection
- Password hashing automático
- Validación de vinculación única

---

## Conclusión

El sistema implementa un flujo robusto de registro de usuarios alumno con dos opciones:

1. **Auto-registro público:** Simple, rápido, para usuarios finales
2. **Creación administrativa:** Completo, con vinculación, para gestión institucional

Ambos flujos utilizan CodeIgniter Shield para autenticación, pero el flujo administrativo agrega una capa de vinculación con los datos académicos, permitiendo una gestión integrada entre usuarios y alumnos.
