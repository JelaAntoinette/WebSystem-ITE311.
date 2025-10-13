<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Default route
$routes->get('/', 'Home::index');

// Main application routes
$routes->get('about', 'Home::about');
$routes->get('contact', 'Home::contact');

// Authentication Routes
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::register');
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::login');
$routes->get('logout', 'Auth::logout');
$routes->get('dashboard', 'Auth::dashboard');

// Student Dashboard
$routes->get('student/dashboard', 'Student::dashboard'); 

// Course Routes
$routes->get('courses', 'Course::index');          // show all courses
$routes->get('course/view/(:num)', 'Course::view/$1'); 
$routes->post('course/enroll', 'Course::enroll');  // handle enrollments

// To make AJAX enroll work (since your JS uses 'student/enroll')
$routes->post('student/enroll', 'Course::enroll');

// Admin Routes
$routes->group('admin', function($routes) {
    $routes->get('/', 'AdminController::dashboard');
    $routes->get('dashboard', 'AdminController::dashboard');
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
