# 📚 Contexto del Proyecto - Sistema de Registro Académico

## 🎯 Descripción General

Aplicación web académica desarrollada como parte de la materia **Práctica Profesionalizante / Administración y Gestión de Bases de Datos**. El proyecto consiste en un sistema de gestión académica completo construido con **CodeIgniter 4 + MySQL** siguiendo arquitectura **MVC**.

---

## 🛠️ Stack Tecnológico

### Backend
- **Framework:** CodeIgniter 4 (PHP 8.1+)
- **Base de Datos:** MySQL/MariaDB
- **Autenticación:** CodeIgniter Shield v1.2
- **ORM:** Query Builder de CodeIgniter 4

### Frontend
- **HTML5, CSS3**
- **Bootstrap 5.3.3**
- **Bootstrap Icons 1.11.3**
- **JavaScript (Vanilla)**

### Dependencias
```json
{
  "php": "^8.1",
  "codeigniter4/framework": "^4.0",
  "codeigniter4/shield": "^1.2"
}
```

---

## 📂 Estructura del Proyecto

```
proyecto_ci4/
├── app/
│   ├── Config/
│   │   ├── Routes.php          # Definición de rutas
│   │   ├── Database.php        # Configuración de BD
│   │   ├── AuthGroups.php      # Roles y permisos
│   │   └── ...
│   ├── Controllers/
│   │   ├── Alumnos.php         # CRUD de estudiantes
│   │   ├── CarrerasController.php
│   │   ├── Cursos.php
│   │   ├── ProfesoresController.php
│   │   ├── Inscripciones.php
│   │   ├── Usuarios.php
│   │   ├── Dashboard.php
│   │   └── Auth/
│   │       └── LogoutController.php
│   ├── Models/
│   │   ├── AlumnoModel.php
│   │   ├── CarreraModel.php
│   │   ├── CursoModel.php
│   │   ├── ProfesorModel.php
│   │   ├── AlumnoCursoModel.php
│   │   ├── ProfesorCursoModel.php
│   │   └── UsuarioModel.php
│   ├── Views/
│   │   ├── layouts/
│   │   │   └── app.php         # Layout principal
│   │   ├── alumnos/
│   │   ├── carreras/
│   │   ├── cursos/
│   │   ├── profesores/
│   │   ├── inscripciones/
│   │   ├── usuarios/
│   │   └── dashboard/
│   ├── Database/
│   │   ├── Migrations/
│   │   │   ├── 1-CreateTablaCarrera.php
│   │   │   ├── 2-CreateTablaAlumno.php
│   │   │   ├── 3-CreateTablaProfesor.php
│   │   │   ├── 4-CreateTablaCurso.php
│   │   │   ├── 5-CreateTablaInscripcion.php
│   │   │   └── 2025-09-03-191215_CreateSchemaBase.php
│   │   └── Seeds/
│   │       ├── DatosBaseSeeder.php
│   │       └── AuthSeeder.php
│   └── ...
├── public/
│   ├── assets/
│   │   └── styles.css
│   ├── images/
│   └── index.php               # Punto de entrada
├── vendor/                     # Dependencias Composer
├── writable/                   # Logs, cache, sesiones
├── composer.json
├── env                         # Configuración de entorno
└── README.md
```

---

## 🗄️ Esquema de Base de Datos

### Tablas Principales

#### 1. **carrera**
```sql
- id_carrera (INT, PK, AUTO_INCREMENT)
- nombre (VARCHAR 120)
- codigo (VARCHAR 10)
- created_at (DATETIME, nullable)
- updated_at (DATETIME, nullable)
- deleted_at (DATETIME, nullable) -- Soft delete
```

#### 2. **alumno**
```sql
- id_alumno (BIGINT, PK, AUTO_INCREMENT)
- dni (CHAR 8, UNIQUE)
- nombre (VARCHAR 120)
- email (VARCHAR 120, nullable)
- fecha_nac (DATE, nullable)
- id_carrera (BIGINT, FK → carrera.id_carrera)
```
**Relaciones:**
- FK: `id_carrera` → `carrera.id_carrera` (RESTRICT/CASCADE)

#### 3. **profesor**
```sql
- id_profesor (BIGINT, PK, AUTO_INCREMENT)
- nombre (VARCHAR 120)
- email (VARCHAR 120, nullable)
```

#### 4. **curso**
```sql
- id (INT, PK, AUTO_INCREMENT)
- carrera_id (INT, FK → carrera.id)
- nombre (VARCHAR 120)
- anio (INT 2, nullable)
- descripcion (VARCHAR 255, nullable)
- created_at (DATETIME, nullable)
- updated_at (DATETIME, nullable)
- deleted_at (DATETIME, nullable)
```
**Relaciones:**
- FK: `carrera_id` → `carrera.id` (CASCADE/RESTRICT)

#### 5. **inscripcion**
```sql
- id_inscripcion (BIGINT, PK, AUTO_INCREMENT)
- id_alumno (BIGINT, FK → alumno.id_alumno)
- id_curso (BIGINT, FK → curso.id_curso)
- fecha (DATE)
- nota_final (DECIMAL 4,2, nullable)
- UNIQUE KEY (id_alumno, id_curso) -- Evita doble inscripción
```
**Relaciones:**
- FK: `id_alumno` → `alumno.id_alumno` (RESTRICT/CASCADE)
- FK: `id_curso` → `curso.id_curso` (RESTRICT/CASCADE)

---

## 🔐 Sistema de Autenticación y Autorización

### Roles (CodeIgniter Shield)

| Rol | Título | Descripción | Permisos |
|-----|--------|-------------|----------|
| **admin** | Administrador | Acceso total al panel y gestión de usuarios | users.create, users.read, users.update, users.delete |
| **profesor** | Profesor | Acceso académico con permisos de lectura | users.read |
| **alumno** | Alumno | Usuarios finales sin privilegios administrativos | Ninguno |

**Rol por defecto:** `alumno`

### Filtros de Rutas
- **session:** Verifica que el usuario esté autenticado
- **group:admin:** Solo administradores
- **group:admin,profesor:** Administradores y profesores

---

## 🛣️ Rutas Principales

### Públicas
```php
GET  /                          → Dashboard::index
GET  /login                     → LoginController::loginView
POST /login                     → LoginController::loginAction
GET  /register                  → RegisterController::registerView
POST /register                  → RegisterController::registerAction
GET  /logout                    → LogoutController::logoutAction
```

### Protegidas (requieren autenticación)

#### Dashboard
```php
GET  /dashboard                 → Dashboard::index
```

#### Alumnos
```php
GET    /alumnos                 → Alumnos::index
POST   /alumnos                 → Alumnos::store
PUT    /alumnos/:id             → Alumnos::update
DELETE /alumnos/:id             → Alumnos::delete
```

#### Carreras
```php
GET  /carreras                  → CarrerasController::index
POST /carreras/store            → CarrerasController::store
GET  /carreras/edit/:id         → CarrerasController::edit
POST /carreras/update/:id       → CarrerasController::update
POST /carreras/delete/:id       → CarrerasController::delete
```

#### Cursos
```php
GET    /cursos                  → Cursos::index
POST   /cursos/store            → Cursos::store
PUT    /cursos/update/:id       → Cursos::update
DELETE /cursos/delete/:id       → Cursos::delete
```

#### Profesores
```php
GET  /profesores                → ProfesoresController::index
POST /profesores/store          → ProfesoresController::store
POST /profesores/update/:id     → ProfesoresController::update
POST /profesores/delete/:id     → ProfesoresController::delete
```

#### Inscripciones
```php
GET  /inscripciones             → Inscripciones::index
POST /inscripciones/store       → Inscripciones::store
POST /inscripciones/update/:id  → Inscripciones::update
POST /inscripciones/delete/:id  → Inscripciones::delete
```

#### Usuarios (solo admin)
```php
GET    /usuarios                → Usuarios::index
GET    /usuarios/:id            → Usuarios::show
POST   /usuarios                → Usuarios::store
PUT    /usuarios/:id            → Usuarios::update
DELETE /usuarios/:id            → Usuarios::delete
```

---

## 🎨 Frontend

### Layout Principal (`app/Views/layouts/app.php`)
- **Navbar:** Bootstrap 5 con navegación dinámica según rol
- **Autenticación:** Muestra/oculta opciones según estado de login
- **Menú Admin:** Solo visible para usuarios con rol `admin`
- **Responsive:** Mobile-first con Bootstrap
- **Iconos:** Bootstrap Icons
- **Footer:** Información institucional

### Vistas por Módulo
- **alumnos/index.php:** Listado con búsqueda y paginación
- **carreras/index.php:** CRUD de carreras
- **cursos/index.php:** Gestión de cursos por carrera
- **profesores/index.php:** Listado de profesores
- **inscripciones/index.php:** Gestión de inscripciones alumno-curso
- **dashboard/index.php:** Panel con estadísticas

---

## 📊 Lógica de Negocio

### Alumnos (app/Controllers/Alumnos.php)
- **index():** Listado con búsqueda por DNI/nombre + paginación (10 por página)
- **store():** Validación de DNI (8 dígitos), email, fecha de nacimiento
- **update():** Validación con `is_unique` ignorando el propio registro
- **delete():** Protección contra eliminación si tiene inscripciones (FK RESTRICT)

### Carreras (app/Controllers/CarrerasController.php)
- **index():** Búsqueda por nombre o código
- **store():** Validación de nombre y código
- **update():** Actualización con validaciones
- **delete():** Verifica que no tenga alumnos ni cursos asociados antes de eliminar

### Validaciones Comunes
```php
// AlumnoModel
'dni'        => 'required|exact_length[8]|is_natural_no_zero'
'nombre'     => 'required|min_length[3]'
'email'      => 'permit_empty|valid_email'
'fecha_nac'  => 'permit_empty|valid_date'
'id_carrera' => 'required|is_natural_no_zero'
```

---

## ⚙️ Configuración del Entorno

### Archivo `env`
```ini
CI_ENVIRONMENT = development

# Base de datos
database.default.hostname = 127.0.0.1
database.default.port = 3306
database.default.database = universidad
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.charset = utf8mb4
database.default.DBCollat = utf8mb4_general_ci

# App
app.baseURL = 'http://localhost/proyecto_ci4/public/'
app.indexPage = ''
app.defaultLocale = es
app.sessionDriver = 'CodeIgniter\Session\Handlers\FileHandler'
```

---

## 🚀 Instalación y Ejecución

### 1. Requisitos
- PHP 8.1+
- MySQL/MariaDB
- Composer
- Apache/Nginx con mod_rewrite

### 2. Instalación
```bash
# Clonar repositorio
git clone <repo-url>
cd proyecto_ci4

# Instalar dependencias
composer install

# Configurar entorno
cp env .env
# Editar .env con tus credenciales de BD
```

### 3. Base de Datos
```bash
# Ejecutar migraciones
php spark migrate

# Ejecutar seeders
php spark db:seed DatosBaseSeeder
php spark db:seed AuthSeeder
```

### 4. Servidor
```bash
# Desarrollo
php spark serve

# Producción: configurar Apache/Nginx apuntando a /public
```

---

## 📝 Datos de Prueba (Seeders)

### DatosBaseSeeder
```php
// Carreras
- Ciencia de Datos (CD)
- Desarrollo de Software (DS)

// Profesores
- Ana Pérez (ana@uni.edu)

// Alumnos
- Juan Dufour (DNI: 40000001, juan@uni.edu)

// Cursos
- BD1 (Carrera: Ciencia de Datos, Profesor: Ana Pérez)

// Inscripciones
- Juan Dufour → BD1 (Nota: 8.5)
```

---

## 🔄 Estado del Proyecto

- [x] Estructura base CodeIgniter 4
- [x] Sistema de autenticación con Shield
- [x] Migraciones de base de datos
- [x] Seeders con datos de prueba
- [x] CRUD de Alumnos
- [x] CRUD de Carreras
- [x] CRUD de Cursos
- [x] CRUD de Profesores
- [x] CRUD de Inscripciones
- [x] CRUD de Usuarios (admin)
- [x] Dashboard con estadísticas
- [x] Sistema de roles y permisos
- [x] Validaciones y manejo de errores
- [x] Interfaz responsive con Bootstrap 5
- [ ] Pruebas unitarias
- [ ] Documentación API
- [ ] Deploy en producción

---

## 👥 Equipo

Trabajo grupal (4 integrantes) — sin roles fijos.  
Cada estudiante participó en **frontend, backend, base de datos y documentación**, bajo metodología **ágil (Scrum/Kanban)** con versionado en **GitHub**.

---

## 📌 Notas Importantes

### Integridad Referencial
- Las tablas usan **FOREIGN KEYS** con políticas `RESTRICT`/`CASCADE`
- No se puede eliminar una carrera si tiene alumnos o cursos asociados
- No se puede eliminar un alumno si tiene inscripciones
- La tabla `inscripcion` tiene UNIQUE KEY en `(id_alumno, id_curso)` para evitar duplicados

### Soft Delete
- Las tablas `carrera` y `curso` implementan soft delete con `deleted_at`
- Los modelos deben configurar `$useSoftDeletes = true` si se desea usar esta funcionalidad

### Seguridad
- CSRF protection habilitado
- Validación de inputs en todos los formularios
- Autenticación con Shield (bcrypt para passwords)
- Filtros de autorización por roles

### Convenciones
- Nombres de tablas en **singular** (carrera, alumno, profesor, curso, inscripcion)
- Primary keys con prefijo `id_` + nombre de tabla
- Foreign keys con prefijo `id_` + nombre de tabla referenciada
- Timestamps: `created_at`, `updated_at`, `deleted_at`

---

## 🔗 Referencias

- [CodeIgniter 4 Documentation](https://codeigniter.com/user_guide/)
- [CodeIgniter Shield](https://shield.codeigniter.com/)
- [Bootstrap 5 Documentation](https://getbootstrap.com/docs/5.3/)
- [Bootstrap Icons](https://icons.getbootstrap.com/)

---

✍️ *Este documento forma parte de la entrega académica para la materia "Administración y Gestión de Bases de Datos / Práctica Profesionalizante: Aproximación al campo laboral".*
