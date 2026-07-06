<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

$routes->get('/clientes', 'Clientes::index');
$routes->get('/clientes/cadastrar', 'Clientes::form');
$routes->get('/clientes/editar/(:num)', 'Clientes::form/$1');

$routes->get('api/v1/clientes', 'Clientes::list');
$routes->get('api/v1/clientes/(:num)', 'Clientes::details/$1');
$routes->post('api/v1/clientes', 'Clientes::save');
$routes->patch('api/v1/clientes/(:num)', 'Clientes::save/$1');