<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

//--------------------- Crear Admin --------------------
$routes->get('create-admin', 'CreateAdminController::index');

//--------------------- Setup Groups --------------------
$routes->get('setup-groups', 'SetupGroupsController::index');

// Página principal (HOME con estadísticas)
$routes->get('/', static function () {
    $db = \Config\Database::connect();
    return view('home', [
        'alumnosCount'       => $db->table('alumno')->countAllResults(),
        'carrerasCount'      => $db->table('carrera')->countAllResults(),
        'cursosCount'        => $db->table('curso')->countAllResults(),
        'profesoresCount'    => $db->table('profesor')->countAllResults(),
        'inscripcionesCount' => $db->table('inscripcion')->countAllResults(),
    ]);
});

// -------------------- AUTH PERSONALIZADO --------------------
$routes->group('auth', function($routes) {
    $routes->get('login', 'AuthController::login');
    $routes->post('attemptLogin', 'AuthController::attemptLogin');
    $routes->get('logout', 'AuthController::logout');
});

// -------------------- ALUMNOS --------------------
$routes->group('alumnos', function($routes) {
    $routes->get('/', 'Alumnos::index');
    $routes->post('/', 'Alumnos::store');
    $routes->put('(:num)', 'Alumnos::update/$1');
    $routes->delete('(:num)', 'Alumnos::delete/$1');
});

// -------------------- CARRERAS --------------------
$routes->group('carreras', function($routes) {
    $routes->get('/', 'CarrerasController::index');
    $routes->post('store', 'CarrerasController::store');
    $routes->get('edit/(:num)', 'CarrerasController::edit/$1');
    $routes->post('update/(:num)', 'CarrerasController::update/$1');
    $routes->post('delete/(:num)', 'CarrerasController::delete/$1');
});

// -------------------- CURSOS --------------------
$routes->group('cursos', function($routes) {
    $routes->get('/', 'Cursos::index');
    $routes->get('editar/(:num)', 'Cursos::editar/$1');
    $routes->post('actualizar/(:num)', 'Cursos::actualizar/$1');
});

// -------------------- PROFESORES --------------------
$routes->group('profesores', function($routes) {
    $routes->get('/', 'ProfesoresController::index');
    $routes->post('store', 'ProfesoresController::store');
    $routes->post('update/(:num)', 'ProfesoresController::update/$1');
    $routes->post('delete/(:num)', 'ProfesoresController::delete/$1');
});

// -------------------- DASHBOARD --------------------
$routes->get('dashboard', static function () {
    $db = \Config\Database::connect();
    return view('dashboard/index', [
        'alumnosCount'       => $db->table('alumno')->countAllResults(),
        'carrerasCount'      => $db->table('carrera')->countAllResults(),
        'cursosCount'        => $db->table('curso')->countAllResults(),
        'profesoresCount'    => $db->table('profesor')->countAllResults(),
        'inscripcionesCount' => $db->table('inscripcion')->countAllResults(),
    ]);
});