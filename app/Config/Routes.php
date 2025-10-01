<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Auth
$routes->get('/register', 'User::register');
$routes->post('/user/save', 'User::save');
$routes->get('/login', 'User::login');
$routes->post('/login/auth', 'User::auth');
$routes->get('/logout', 'User::logout');

$routes->get('/dashboard', 'Dashboard::index');
$routes->get('/shop/categories', 'Shop::categories');
$routes->get('/shop/products', 'Shop::products');

// CRUD User
$routes->group('user', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('/', 'User::index');
    $routes->get('edit/(:num)', 'User::edit/$1');
    $routes->post('update/(:num)', 'User::update/$1');
    $routes->get('delete/(:num)', 'User::delete/$1');
});

// CRUD Category
$routes->group('category', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('/', 'Category::index');
    $routes->get('show', 'Category::show');
    $routes->get('create', 'Category::create');
    $routes->post('store', 'Category::store');
    $routes->get('edit/(:num)', 'Category::edit/$1');
    $routes->post('update/(:num)', 'Category::update/$1');
    $routes->get('delete/(:num)', 'Category::delete/$1');
});

// CRUD Product
$routes->group('products', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('/', 'Product::index');
    $routes->get('create', 'Product::create');
    $routes->post('store', 'Product::store');
    $routes->get('edit/(:num)', 'Product::edit/$1');
    $routes->post('update/(:num)', 'Product::update/$1');
    $routes->get('delete/(:num)', 'Product::delete/$1');
    $routes->get('show/(:num)', 'Product::show/$1');
});

$routes->get('classes', 'Classes::index');
$routes->get('classes/create', 'Classes::create');
$routes->post('classes/store', 'Classes::store');
$routes->get('classes/edit/(:num)', 'Classes::edit/$1');
$routes->post('classes/update/(:num)', 'Classes::update/$1');
$routes->get('classes/show/(:num)', 'Classes::show/$1');
$routes->post('classes/delete/(:num)', 'Classes::delete/$1');
