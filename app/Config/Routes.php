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

// Fallback generic dashboard route (redirects to role-specific)
$routes->get('dashboard', 'Auth::dashboardRedirect');

// Role-specific Dashboard Routes
$routes->get('student/dashboard', 'StudentController::dashboard');
$routes->get('student/my-courses', 'StudentController::myCourses');  // ✅ NEW ROUTE
$routes->get('teacher/dashboard', 'TeacherController::dashboard');
$routes->get('admin/dashboard', 'AdminController::dashboard');

// Course Routes (Student)
$routes->get('courses', 'Course::index');
$routes->get('course/view/(:num)', 'Course::view/$1');
$routes->post('course/enroll', 'StudentController::enroll');  // ✅ Fixed route
$routes->post('student/enroll', 'StudentController::enroll');

// Admin Routes
$routes->group('admin', function($routes) {
    $routes->get('/', 'AdminController::dashboard');
    $routes->get('users', 'AdminController::index');
    $routes->get('users/create', 'AdminController::create');
    $routes->post('users/store', 'AdminController::store');
    $routes->get('users/edit/(:num)', 'AdminController::edit/$1');
    $routes->post('users/update/(:num)', 'AdminController::update/$1');
    $routes->get('users/delete/(:num)', 'AdminController::delete/$1');
    $routes->get('logout', 'AdminController::logout');
});

// Optional shortcut route for managing users
$routes->get('users', 'AdminController::index');

// Test route
$routes->get('test-db', 'TestController::testDb');