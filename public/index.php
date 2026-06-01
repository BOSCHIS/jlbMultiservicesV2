<?php

session_start();

require_once __DIR__ . '/../app/Core/Router.php';

$router = new Router();

$router->get('/', 'HomeController@index');

$router->get('/contact', 'ContactController@index');
$router->post('/contact/send', 'ContactController@send');

$router->get('/service', 'ServiceController@show');

$router->get('/nettoyage', 'ServiceController@nettoyage');
$router->get('/bricolage', 'ServiceController@bricolage');
$router->get('/jardinage', 'ServiceController@jardinage');
$router->get('/debarras', 'ServiceController@debarras');

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


$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
