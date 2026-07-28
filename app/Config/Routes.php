<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'HomeController::index');

// Agrupamos las rutas que requieren autenticación
$routes->group('', ['filter' => 'session'], static function ($routes) {
    // Dashboards
    $routes->get('dashboard', 'DashboardController::index');
    
    // Perfil
    $routes->get('profile', 'ProfileController::index');
    $routes->post('profile/update', 'ProfileController::update');

    // Gestión de Archivos Compartidos (FileCrew)
    $routes->get('files', 'FileShareController::index');
    $routes->get('files/upload', 'FileShareController::upload');
    $routes->post('files/store', 'FileShareController::store');
    $routes->get('files/edit/(:num)', 'FileShareController::edit/$1');
    $routes->post('files/update/(:num)', 'FileShareController::update/$1');
    $routes->post('files/delete/(:num)', 'FileShareController::delete/$1');
    $routes->post('files/send-email/(:num)', 'FileShareController::sendEmail/$1');
});

// Rutas de administración (requieren permisos extra)
$routes->group('', ['filter' => 'session'], static function ($routes) {
    $routes->get('users', 'UsersController::index', ['filter' => 'permission:admin.users,users.create,users.edit,users.delete']);
    $routes->get('users/create', 'UsersController::create', ['filter' => 'permission:admin.users,users.create']);
    $routes->post('users/store', 'UsersController::store', ['filter' => 'permission:admin.users,users.create']);
    $routes->get('users/edit/(:num)', 'UsersController::edit/$1', ['filter' => 'permission:admin.users,users.edit']);
    $routes->post('users/update/(:num)', 'UsersController::update/$1', ['filter' => 'permission:admin.users,users.edit']);
    $routes->post('users/toggle-active/(:num)', 'UsersController::toggleActive/$1', ['filter' => 'permission:admin.users,users.edit']);
    $routes->post('users/delete/(:num)', 'UsersController::delete/$1', ['filter' => 'permission:admin.users,users.delete']);

    // Rutas de Roles
    $routes->get('roles', 'RolesController::index', ['filter' => 'permission:admin.roles']);
    $routes->get('roles/edit/(:segment)', 'RolesController::edit/$1', ['filter' => 'permission:admin.roles']);
    $routes->post('roles/update/(:segment)', 'RolesController::update/$1', ['filter' => 'permission:admin.roles']);
});

// Rutas de Superadmin (Configuración - requieren permisos)
$routes->group('settings', ['filter' => ['session', 'permission:admin.settings']], static function ($routes) {
    $routes->get('smtp', 'SmtpController::smtp');
    $routes->post('smtp/update', 'SmtpController::smtpUpdate');
    $routes->post('smtp/test', 'SmtpController::smtpTest');
    $routes->get('maintenance', 'MaintenanceController::maintenance');
    $routes->post('maintenance/clear-sessions', 'MaintenanceController::clearSessions');
    $routes->post('maintenance/clear-debugbar', 'MaintenanceController::clearDebugbar');
    $routes->post('maintenance/clear-logs', 'MaintenanceController::clearLogs');
    $routes->post('maintenance/optimize-db', 'MaintenanceController::optimizeDb');
    $routes->post('maintenance/clear-all', 'MaintenanceController::clearAll');
    $routes->get('maintenance/backup/download', 'MaintenanceController::downloadBackup');
    $routes->post('maintenance/backup/restore', 'MaintenanceController::restoreBackup');
});

// Ruta para cerrar sesión tras activar la cuenta
$routes->get('auth/logout-activated', static function () {
    auth()->logout();
    return redirect()->to('login')->with('message', 'Tu cuenta ha sido activada con éxito. Por favor, inicia sesión.');
});

// Rutas de CodeIgniter Shield
service('auth')->routes($routes);

// Rutas públicas de compartición de archivos (FileCrew)
$routes->get('s/(:segment)', 'FileShareController::showShare/$1');
$routes->post('s/(:segment)/verify', 'FileShareController::verifyPassword/$1');
$routes->get('s/(:segment)/download', 'FileShareController::download/$1');

