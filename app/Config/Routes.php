<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

//CLIENTES
$routes->get('/clientes', 'Clientes::index');
$routes->get('/clientes/cadastrar', 'Clientes::form');
$routes->get('/clientes/editar/(:num)', 'Clientes::form/$1');
//CLIENTES API
$routes->get('/api/v1/clientes', 'Clientes::list');
$routes->get('/api/v1/clientes/(:num)', 'Clientes::details/$1');
$routes->post('/api/v1/clientes', 'Clientes::save');
$routes->patch('/api/v1/clientes/(:num)', 'Clientes::save/$1');

//PROPOSTAS
$routes->get('/propostas', 'Propostas::index');
$routes->get('/propostas/cadastrar', 'Propostas::form');
$routes->get('/propostas/editar/(:num)', 'Propostas::form/$1');
//PROPOSTAS API
$routes->get('/api/v1/propostas', 'Propostas::list');
$routes->get('/api/v1/propostas/(:num)', 'Propostas::details/$1');
$routes->post('/api/v1/propostas', 'Propostas::save');
$routes->patch('/api/v1/propostas/(:num)', 'Propostas::save/$1');

//PROPOSTA AUDITORIA
$routes->get('/propostas/(:num)/auditoria', 'PropostasLog::index/$1');
$routes->get('/api/v1/propostas/(:num)/auditoria', 'PropostasLog::auditoria/$1');