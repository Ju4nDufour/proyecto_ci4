<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
# $routes->get('/', 'Home::index');

// $routes->get('/', 'Home::index');   // ← desactivar esta
$routes->get('/', static function () {
    // (opcional) conteos reales; si no querés DB, ponelos en 0
    $db = \Config\Database::connect();
    return view('dashboard', [
        'alumnosCount'      => $db->table('alumno')->countAllResults(),
        'carrerasCount'     => $db->table('carrera')->countAllResults(),
        'cursosCount'       => $db->table('curso')->countAllResults(),
        'profesoresCount'   => $db->table('profesor')->countAllResults(),
        'inscripcionesCount'=> $db->table('inscripcion')->countAllResults(),
    ]);
});

$routes->get('alumnos', 'Alumnos::index');
$routes->post('alumnos', 'Alumnos::store'); // crear
$routes->put('alumnos/(:num)', 'Alumnos::update/$1');  // actualizar (method spoofing)
$routes->delete('alumnos/(:num)', 'Alumnos::delete/$1'); // eliminar (method spoofing)

// Carreras
$routes->group('carreras', function($routes) {
    $routes->get('/', 'CarrerasController::index');
    // Crear (ya lo tenés si usás el modal del index)
    $routes->post('store', 'CarrerasController::store');
    // Editar (pantalla de edición)
    $routes->get('edit/(:num)', 'CarrerasController::edit/$1');
    $routes->post('update/(:num)', 'CarrerasController::update/$1');
    // Eliminar (seguro con POST + spoof DELETE)
    $routes->post('delete/(:num)', 'CarrerasController::delete/$1');
});

$routes->group('cursos', function($routes){
    $routes->get('/', 'CursosController::index');
    $routes->post('store', 'CursosController::store');
    $routes->get('edit/(:num)', 'CursosController::edit/$1');
    $routes->post('update/(:num)', 'CursosController::update/$1');
    $routes->get('delete/(:num)', 'CursosController::delete/$1');
});

$routes->group('profesores', function($routes) {
    $routes->get('/', 'ProfesoresController::index');
    $routes->post('store', 'ProfesoresController::store');
    $routes->post('delete/(:num)', 'ProfesoresController::delete/$1');
});



$routes->get('/', 'Dashboard::index'); // Página de inicio por defecto
$routes->get('dashboard', 'Dashboard::index'); // Ruta por si se accede por /dashboard
