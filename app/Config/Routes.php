<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Default route - redirects to homepage
$routes->get('/', 'Home::index');

// Main application routes
$routes->get('about', 'Home::about');
$routes->get('contact', 'Home::contact');

// Authentication Routes
$routes->get('register', 'Auth::register');   // Show registration form
$routes->post('register', 'Auth::register');  // Process registration
$routes->get('login', 'Auth::login');         // Show login form
$routes->post('login', 'Auth::login');        // ✅ Process login
$routes->get('logout', 'Auth::logout');       // Logout user
$routes->get('dashboard', 'Auth::dashboard'); // User dashboard


// app/Config/Routes.php
$routes->post('course/enroll', 'Course::enroll');
$routes->get('/student/dashboard', 'Student::dashboard');

$routes->get('/admin/users', 'ManageUser::index');
$routes->get('/admin/users/delete/(:num)', 'ManageUser::delete/$1');
$routes->get('/admin/users/create', 'ManageUser::create');
$routes->post('/admin/users/store', 'ManageUser::store');
$routes->get('/admin/users/edit/(:num)', 'ManageUser::edit/$1');
$routes->post('/admin/users/update/(:num)', 'ManageUser::update/$1');
