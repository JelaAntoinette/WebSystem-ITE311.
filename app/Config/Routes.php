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

// 🔧 Change 'course/manage' → 'courses/manage'
$routes->get('courses/manage', 'Course::manage');
$routes->post('courses/manage', 'Course::manage');

//announcement route
$routes->get('/announcements', 'Announcement::index');

// Material Management Routes - TEACHER ROUTES (Changed from admin to teacher)
$routes->get('/teacher/course/(:num)/upload', 'Materials::upload/$1');
$routes->post('/teacher/course/(:num)/upload', 'Materials::upload/$1');

$routes->get('/materials/delete/(:num)', 'Materials::delete/$1');
$routes->get('/materials/download/(:num)', 'Materials::download/$1');
$routes->post('/materials/upload/(:num)', 'Materials::upload/$1');


// Optional: For student view
$routes->get('/materials/course/(:num)', 'Materials::viewCourseMaterials/$1');


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

$routes->get('materials/download/(:num)', 'Materials::download/$1');

// Optional shortcut route for managing users
$routes->get('users', 'AdminController::index');

// Test route
$routes->get('test-db', 'TestController::testDb');


//Lab seben - TEACHER ROUTES (Changed from admin to teacher)
$routes->get('/teacher/course/(:num)/upload', 'Materials::upload/$1');
$routes->post('/teacher/course/(:num)/upload', 'Materials::upload/$1');
$routes->get('/materials/delete/(:num)', 'Materials::delete/$1');
$routes->get('/materials/download/(:num)', 'Materials::download/$1');

// Teacher Materials Management (Changed from admin to teacher)
$routes->get('teacher/materials', 'Materials::adminMaterialsPage');

// AJAX Routes
$routes->post('materials/ajax-upload', 'Materials::ajaxUpload');
$routes->get('materials/ajax/all', 'Materials::getAllMaterialsAjax');
$routes->get('materials/ajax/courses', 'Materials::getAllCourses');
$routes->get('materials/stats/dashboard', 'Materials::getDashboardStats');

// Material Actions
$routes->get('materials/download/(:num)', 'Materials::download/$1');
$routes->get('materials/preview/(:num)', 'Materials::preview/$1');
$routes->post('materials/delete/(:num)', 'Materials::delete/$1');
$routes->get('materials/ajax/course/(:num)', 'Materials::getMaterialsByCourse/$1');

// Admin Materials Management
$routes->get('admin/materials', 'Materials::adminMaterialsPage');

// Teacher Materials Management  
$routes->get('teacher/materials', 'Materials::adminMaterialsPage');