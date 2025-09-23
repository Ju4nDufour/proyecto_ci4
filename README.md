<<<<<<< HEAD
# Proyecto_BD
“Proyecto académico Registro de alumnos”
[README.md](https://github.com/user-attachments/files/21998226/README.md)
# 📚 Proyecto Registro Académico

Aplicación web académica desarrollada como parte de la materia **Práctica Profesionalizante / Administración y Gestión de Bases de Datos**.  
El proyecto consiste en la migración de un front-end simple (HTML/CSS/JS) hacia un entorno profesional con **Node.js/Express (API simulada)** y la futura integración a **CodeIgniter 4 + MySQL** siguiendo arquitectura **MVC**.

---

## 🚀 Funcionalidades principales
- **Gestión de Categorías:** crear, buscar, eliminar y listar categorías.
- **Gestión de Carreras:** CRUD básico con validaciones y asociación a categorías.
- **Gestión de Estudiantes:** registrar, buscar, eliminar y filtrar estudiantes por carrera.
- **Interfaz web amigable:** páginas en HTML con Bootstrap 5 + estilos propios (`styles.css`).
- **Backend simulado:** API REST con Express.js que persiste datos en archivos JSON.

---

## 🛠️ Tecnologías utilizadas
- **Frontend:** HTML5, CSS3, Bootstrap 5, JavaScript (fetch API).
- **Backend simulado:** Node.js + Express.js.
- **Persistencia:** Archivos JSON locales (`students.json`, `careers.json`, `categories.json`).
- **Futuro alcance:** Migración a CodeIgniter 4 (PHP) + MySQL con migraciones y seeders.

---

## 📂 Estructura del repositorio
```
├── /docs                  # Documentación (plan, guías, glosario)
├── /css/styles.css        # Estilos visuales
├── /js/app.js             # Lógica de frontend (consumo API)
├── index.html             # Página principal
├── students.html          # Gestión de estudiantes
├── careers.html           # Gestión de carreras
├── categories.html        # Gestión de categorías
├── index.js               # Servidor Express (API REST)
├── students.json          # Datos persistentes de estudiantes
├── careers.json           # Datos persistentes de carreras
├── categories.json        # Datos persistentes de categorías
├── package.json           # Dependencias Node.js
└── README.md              # Este archivo
```

---

## ⚙️ Instalación y ejecución

### 1. Clonar repositorio
```bash
git clone https://github.com/usuario/proyecto-registro-academico.git
cd proyecto-registro-academico
```

### 2. Instalar dependencias
```bash
npm install
```

### 3. Ejecutar servidor API
```bash
node index.js
```
El backend quedará disponible en:  
👉 `http://localhost:5001/api`

### 4. Abrir el frontend
Abrir en navegador los archivos HTML (`index.html`, `students.html`, etc.) desde la carpeta raíz.

---

## 🔑 Endpoints principales
- **Categorías:**  
  - `POST /api/categories` – Crear  
  - `GET /api/categories` – Listar  
  - `GET /api/categories/:id` – Buscar por ID  
  - `DELETE /api/categories/:id` – Eliminar  

- **Carreras:**  
  - `POST /api/careers` – Crear  
  - `GET /api/careers` – Listar  
  - `GET /api/careers/:id` – Buscar por ID  
  - `DELETE /api/careers/:id` – Eliminar (solo si no tiene estudiantes asociados)  

- **Estudiantes:**  
  - `POST /api/students` – Crear  
  - `GET /api/students/:id` – Buscar por ID  
  - `GET /api/students?career=XYZ` – Listar por carrera  
  - `DELETE /api/students/:id` – Eliminar  

---

## 👥 Equipo
Trabajo grupal (4 integrantes) — sin roles fijos.  
Cada estudiante participó en **frontend, backend, base de datos y documentación**, bajo metodología **ágil (Scrum/Kanban)** con versionado en **GitHub**.

---

## 📑 Documentación de apoyo
- 📄 *Plan de Práctica Profesionalizante CI4 + MySQL*  
- 📄 *Glosario de Migración CI4*  
- 📄 *Guía de Migración a CodeIgniter 4*  
- 📄 *Guía de Proyecto Real (Scrum/Kanban/Git)*  

---

## ✅ Estado del proyecto
- [x] Frontend con API simulada (Node/Express + JSON)  
- [x] CRUD básico de estudiantes, carreras y categorías  
- [ ] Migración completa a CodeIgniter 4 + MySQL  
- [ ] Pruebas unitarias y demo final  

---

✍️ *Este repositorio forma parte de la entrega académica para la materia “Administración y Gestión de Bases de Datos / Práctica Profesionalizante: Aproximación al campo laboral”.*
=======
# CodeIgniter 4 Application Starter

## What is CodeIgniter?

CodeIgniter is a PHP full-stack web framework that is light, fast, flexible and secure.
More information can be found at the [official site](https://codeigniter.com).

This repository holds a composer-installable app starter.
It has been built from the
[development repository](https://github.com/codeigniter4/CodeIgniter4).

More information about the plans for version 4 can be found in [CodeIgniter 4](https://forum.codeigniter.com/forumdisplay.php?fid=28) on the forums.

You can read the [user guide](https://codeigniter.com/user_guide/)
corresponding to the latest version of the framework.

## Installation & updates

`composer create-project codeigniter4/appstarter` then `composer update` whenever
there is a new release of the framework.

When updating, check the release notes to see if there are any changes you might need to apply
to your `app` folder. The affected files can be copied or merged from
`vendor/codeigniter4/framework/app`.

## Setup

Copy `env` to `.env` and tailor for your app, specifically the baseURL
and any database settings.

## Important Change with index.php

`index.php` is no longer in the root of the project! It has been moved inside the *public* folder,
for better security and separation of components.

This means that you should configure your web server to "point" to your project's *public* folder, and
not to the project root. A better practice would be to configure a virtual host to point there. A poor practice would be to point your web server to the project root and expect to enter *public/...*, as the rest of your logic and the
framework are exposed.

**Please** read the user guide for a better explanation of how CI4 works!

## Repository Management

We use GitHub issues, in our main repository, to track **BUGS** and to track approved **DEVELOPMENT** work packages.
We use our [forum](http://forum.codeigniter.com) to provide SUPPORT and to discuss
FEATURE REQUESTS.

This repository is a "distribution" one, built by our release preparation script.
Problems with it can be raised on our forum, or as issues in the main repository.

## Server Requirements

PHP version 8.1 or higher is required, with the following extensions installed:

- [intl](http://php.net/manual/en/intl.requirements.php)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)

> [!WARNING]
> - The end of life date for PHP 7.4 was November 28, 2022.
> - The end of life date for PHP 8.0 was November 26, 2023.
> - If you are still using PHP 7.4 or 8.0, you should upgrade immediately.
> - The end of life date for PHP 8.1 will be December 31, 2025.

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php) if you plan to use MySQL
- [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library
>>>>>>> 7e73ade (chore: proyecto CI4 inicial (migraciones, seeders, .gitignore y .env.example))
