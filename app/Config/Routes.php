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
$routes->post('logout', 'User::logout');
$routes->get('/logout', 'User::logout');

// Dashboard and Shop
$routes->get('/dashboard', 'Dashboard::index');
$routes->get('/shop/categories', 'Shop::categories');
$routes->get('/shop/products', 'Shop::products');
$routes->get('/shop/class-sessions', 'Shop::classSessions');

// CRUD User
$routes->get('user', 'User::index');
$routes->get('user/edit/(:num)', 'User::edit/$1');
$routes->post('user/update/(:num)', 'User::update/$1');
$routes->post('user/delete/(:num)', 'User::delete/$1');

// CRUD Category
$routes->get('category', 'Category::index');
$routes->get('category/show', 'Category::show');
$routes->get('category/create', 'Category::create');
$routes->post('category/store', 'Category::store');
$routes->get('category/edit/(:num)', 'Category::edit/$1');
$routes->post('category/update/(:num)', 'Category::update/$1');
$routes->get('category/delete/(:num)', 'Category::delete/$1');

// CRUD Product
$routes->get('products', 'Product::index');
$routes->get('products/create', 'Product::create');
$routes->post('products/store', 'Product::store');
$routes->get('products/edit/(:num)', 'Product::edit/$1');
$routes->post('products/update/(:num)', 'Product::update/$1');
$routes->get('products/delete/(:num)', 'Product::delete/$1');
$routes->get('products/show/(:num)', 'Product::show/$1');

// CRUD Classes
$routes->get('classes', 'Classes::index');
$routes->get('classes/create', 'Classes::create');
$routes->post('classes/store', 'Classes::store');
$routes->get('classes/edit/(:num)', 'Classes::edit/$1');
$routes->post('classes/update/(:num)', 'Classes::update/$1');
$routes->get('classes/show/(:num)', 'Classes::show/$1');
$routes->post('classes/delete/(:num)', 'Classes::delete/$1');

// CRUD Class Sessions
$routes->get('class-sessions', 'ClassSessions::index');
$routes->get('class-sessions/create', 'ClassSessions::create');
$routes->post('class-sessions/store', 'ClassSessions::store');
$routes->get('class-sessions/edit/(:num)', 'ClassSessions::edit/$1');
$routes->post('class-sessions/update/(:num)', 'ClassSessions::update/$1');
$routes->get('class-sessions/show/(:num)', 'ClassSessions::show/$1');
$routes->post('class-sessions/delete/(:num)', 'ClassSessions::delete/$1');

// Routes untuk Discounts
$routes->get('discounts', 'Discounts::index');
$routes->get('discounts/create', 'Discounts::create');
$routes->post('discounts/store', 'Discounts::store');
$routes->get('discounts/edit/(:num)', 'Discounts::edit/$1');
$routes->post('discounts/update/(:num)', 'Discounts::update/$1');
$routes->get('discounts/show/(:num)', 'Discounts::show/$1'); 
$routes->post('discounts/delete/(:num)', 'Discounts::delete/$1');

// Registration
// CRUD Registrations
$routes->get('registrations', 'Registration::index');               
$routes->get('registrations/create', 'Registration::create');
$routes->post('registrations/store', 'Registration::store');
$routes->get('registrations/show/(:num)', 'Registration::show/$1');
$routes->get('registrations/edit/(:num)', 'Registration::edit/$1');
$routes->post('registrations/update/(:num)', 'Registration::update/$1');
$routes->post('registrations/delete/(:num)', 'Registration::delete/$1');
$routes->get('registrations/success/(:num)', 'Registration::success/$1');

// Proposals
$routes->get('proposals', 'ClassProposals::index');
$routes->get('proposals/create', 'ClassProposals::create');
$routes->post('proposals', 'ClassProposals::store');
$routes->get('proposals/show/(:num)', 'ClassProposals::show/$1');
$routes->get('proposals/edit/(:num)', 'ClassProposals::edit/$1');
$routes->post('proposals/update/(:num)', 'ClassProposals::update/$1');
$routes->post('proposals/delete/(:num)', 'ClassProposals::delete/$1');
$routes->get('proposals/success/(:num)', 'ClassProposals::success/$1');