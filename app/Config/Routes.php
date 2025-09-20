<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('/register', 'User::register');
$routes->post('/user/save', 'User::save');

$routes->get('/login', 'User::login');
$routes->post('/login/auth', 'User::auth');
$routes->get('/logout', 'User::logout');

$routes->get('/dashboard', 'Dashboard::index');

// CRUD user
$routes->get('/user', 'User::index');
$routes->get('/user/edit/(:num)', 'User::edit/$1');
$routes->post('/user/update/(:num)', 'User::update/$1');
$routes->get('/user/delete/(:num)', 'User::delete/$1');

$routes->get('/category', 'Category::index');
$routes->get('/category/create', 'Category::create');
$routes->post('/category/store', 'Category::store');
$routes->get('/category/edit/(:num)', 'Category::edit/$1');
$routes->post('/category/update/(:num)', 'Category::update/$1');
$routes->get('/category/delete/(:num)', 'Category::delete/$1');
