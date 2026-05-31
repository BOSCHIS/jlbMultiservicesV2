<?php

session_start();

require_once __DIR__ . '/../app/Core/Router.php';

$router = new Router();

$router->get('/', 'HomeController@index');

$router->get('/contact', 'ContactController@index');

$router->get('/nettoyage', 'ServiceController@nettoyage');

$router->get('/bricolage', 'ServiceController@bricolage');

$router->get('/jardinage', 'ServiceController@jardinage');

$router->get('/debarras', 'ServiceController@debarras');

$router->get('/entreprise', 'HomeController@entreprise');

$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
