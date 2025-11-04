<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Página de inicio y dashboard
$routes->get('/', 'Dashboard::index');
$routes->get('dashboard', 'Dashboard::index');

// LOGOUT - Definido ANTES de los grupos con filtros
$routes->get('logout', 'Auth\LogoutController::logoutAction');

// Rutas protegidas por login (solo usuarios autenticados)
$routes->group('', ['filter' => 'session'], static function (RouteCollection $routes) {

    // ========== SOLO ADMIN ==========
    $routes->group('', ['filter' => 'group:admin'], static function (RouteCollection $routes) {
        // Usuarios
        $routes->get('usuarios', 'Usuarios::index');
        $routes->post('usuarios', 'Usuarios::store');
        $routes->put('usuarios/(:num)', 'Usuarios::update/$1');
        $routes->delete('usuarios/(:num)', 'Usuarios::delete/$1');
        $routes->get('usuarios/listado', 'Usuarios::list');

        // Alumnos
        $routes->group('alumnos', static function (RouteCollection $routes) {
            $routes->get('/', 'Alumnos::index');
            $routes->post('/', 'Alumnos::store');
            $routes->put('(:num)', 'Alumnos::update/$1');
            $routes->delete('(:num)', 'Alumnos::delete/$1');
        });

        // Carreras (CRUD) - CON PREFIJO DIFERENTE
        $routes->get('carreras/admin', 'CarrerasController::index');
        $routes->post('carreras/store', 'CarrerasController::store');
        $routes->get('carreras/edit/(:num)', 'CarrerasController::edit/$1');
        $routes->post('carreras/update/(:num)', 'CarrerasController::update/$1');
        $routes->post('carreras/delete/(:num)', 'CarrerasController::delete/$1');

        // Cursos
        $routes->group('cursos', static function (RouteCollection $routes) {
            $routes->get('/', 'Cursos::index');
            $routes->post('store', 'Cursos::store');
            $routes->put('update/(:num)', 'Cursos::update/$1');
            $routes->delete('delete/(:num)', 'Cursos::delete/$1');
        });

        // Profesores (SOLO ADMIN)
        $routes->group('profesores', static function (RouteCollection $routes) {
            $routes->get('/', 'ProfesoresController::index');
            $routes->post('store', 'ProfesoresController::store');
            $routes->post('update/(:num)', 'ProfesoresController::update/$1');
            $routes->post('delete/(:num)', 'ProfesoresController::delete/$1');
        });
    });

    // ========== ADMIN + PROFESOR + ALUMNO (TODOS) ==========
    $routes->group('', ['filter' => 'group:admin,profesor,alumno'], static function (RouteCollection $routes) {
        // Inscripciones
        $routes->group('inscripciones', static function (RouteCollection $routes) {
            $routes->get('/', 'Inscripciones::index');
            $routes->post('store', 'Inscripciones::store');
            $routes->post('update/(:num)', 'Inscripciones::update/$1');
            $routes->post('delete/(:num)', 'Inscripciones::delete/$1');
        });
    });
});

// ✅ RUTA PÚBLICA: visible sin login
$routes->get('carreras', 'CarrerasPublic::index');

// Rutas de autenticación de Shield (login, registro, etc.)
$routes->group('', ['namespace' => 'CodeIgniter\Shield\Controllers'], static function (RouteCollection $routes) {
    // Login clásico
    $routes->get('login', 'LoginController::loginView');
    $routes->post('login', 'LoginController::loginAction');

    // Registro
    $routes->get('register', 'RegisterController::registerView');
    $routes->post('register', 'RegisterController::registerAction');

    // Recuperación de contraseña
    $routes->get('forgot-password', 'ForgotPasswordController::forgotPasswordView');
    $routes->post('forgot-password', 'ForgotPasswordController::forgotPasswordAction');
    $routes->get('reset-password', 'ForgotPasswordController::resetPasswordView');
    $routes->post('reset-password', 'ForgotPasswordController::resetPasswordAction');

    // Magic Link
    $routes->get('magic-link', 'MagicLinkController::loginView');
    $routes->post('magic-link', 'MagicLinkController::loginAction');
    $routes->get('magic-link/verify', 'MagicLinkController::verify');
    $routes->post('magic-link/resend', 'MagicLinkController::resend');
});