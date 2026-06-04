<?php

$isLocal = in_array($_SERVER['SERVER_NAME'] ?? '', [
    '127.0.0.1',
    'localhost'
], true);

if ($isLocal) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    error_reporting(E_ALL);
    ini_set('log_errors', '1');
}

$isHttps = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => $isHttps,
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();

require_once __DIR__ . '/../app/Core/Router.php';

$router = new Router();

$router->get('/', 'HomeController@index');

$router->get('/contact', 'ContactController@index');
$router->post('/contact/send', 'ContactController@send');

$router->get('/service', 'ServiceController@show');

$router->get('/entreprise', 'HomeController@entreprise');

$router->get('/admin/login', 'AdminAuthController@login');
$router->post('/admin/login', 'AdminAuthController@authenticate');
$router->get('/admin/dashboard', 'AdminDashboardController@index');
$router->get('/admin/logout', 'AdminAuthController@logout');

$router->get('/admin/contacts', 'AdminContactController@index');
$router->post('/admin/contact/delete', 'AdminContactController@delete');
$router->post('/admin/service/delete', 'AdminServiceController@delete');

$router->get('/admin/services', 'AdminServiceController@index');
$router->get('/admin/service/create', 'AdminServiceController@create');
$router->post('/admin/service/create', 'AdminServiceController@store');
$router->get('/admin/service/edit', 'AdminServiceController@edit');
$router->post('/admin/service/edit', 'AdminServiceController@update');

$router->get('/admin/categories', 'AdminCategoryController@index');
$router->get('/admin/category/create', 'AdminCategoryController@create');
$router->post('/admin/category/create', 'AdminCategoryController@store');
$router->get('/admin/category/edit', 'AdminCategoryController@edit');
$router->post('/admin/category/edit', 'AdminCategoryController@update');
$router->post('/admin/category/delete', 'AdminCategoryController@delete');

$router->get('/admin/entreprise', 'AdminCompanyController@index');
$router->get('/admin/entreprise/create', 'AdminCompanyController@create');
$router->post('/admin/entreprise/create', 'AdminCompanyController@store');
$router->get('/admin/entreprise/edit', 'AdminCompanyController@edit');
$router->post('/admin/entreprise/edit', 'AdminCompanyController@update');
$router->post('/admin/entreprise/delete', 'AdminCompanyController@delete');

$router->get('/mentions-legales', 'LegalController@mentions');
$router->get('/conditions-generales-utilisation', 'LegalController@terms');
$router->get('/politique-confidentialite', 'LegalController@privacy');

$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
