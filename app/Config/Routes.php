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
$routes->post('login', 'Auth::login');        // Process login
$routes->get('logout', 'Auth::logout');       // Logout user
$routes->get('dashboard', 'Auth::dashboard'); // User dashboard

// Course enrollment
$routes->post('course/enroll', 'Course::enroll');
$routes->get('student/dashboard', 'Student::dashboard');

// Admin routes
$routes->group('admin', function($routes) {
    $routes->get('/', 'AdminController::dashboard');  // Admin dashboard
    $routes->get('dashboard', 'AdminController::dashboard');  // Alternative dashboard route
    $routes->get('users', 'AdminController::index');
    $routes->get('users/create', 'AdminController::create');
    $routes->post('users/store', 'AdminController::store');
    $routes->get('users/edit/(:num)', 'AdminController::edit/$1');
    $routes->post('users/update/(:num)', 'AdminController::update/$1');
    $routes->get('users/delete/(:num)', 'AdminController::delete/$1');
    $routes->get('logout', 'AdminController::logout');
});

// Optional shortcut route
$routes->get('users', 'AdminController::index');

// Test route
$routes->get('test-db', 'TestController::testDb');
