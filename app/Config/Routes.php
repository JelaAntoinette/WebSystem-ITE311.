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
$routes->get('student/my-courses', 'StudentController::myCourses'); 
$routes->get('teacher/dashboard', 'TeacherController::dashboard');
$routes->get('admin/dashboard', 'AdminController::dashboard');

// Course Routes (Student)
$routes->get('courses', 'Course::index');
$routes->get('course/view/(:num)', 'Course::view/$1');
$routes->post('course/enroll', 'StudentController::enroll');
$routes->post('student/enroll', 'StudentController::enroll');

// Manage Courses
$routes->get('courses/manage', 'Course::manage');
$routes->post('courses/manage', 'Course::manage');

// Announcements
$routes->get('/announcements', 'Announcement::index');

// Material Management Routes - TEACHER
$routes->get('/teacher/course/(:num)/upload', 'Materials::upload/$1');
$routes->post('/teacher/course/(:num)/upload', 'Materials::upload/$1');

$routes->get('/materials/delete/(:num)', 'Materials::delete/$1');
$routes->get('/materials/download/(:num)', 'Materials::download/$1');
$routes->post('/materials/upload/(:num)', 'Materials::upload/$1');

// Optional student view
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

// Shortcuts
$routes->get('users', 'AdminController::index');

// Test DB
$routes->get('test-db', 'TestController::testDb');
$routes->get('test-notification', 'TestController::createTestNotification');

// Teacher / Materials
$routes->get('/teacher/course/(:num)/upload', 'Materials::upload/$1');
$routes->post('/teacher/course/(:num)/upload', 'Materials::upload/$1');
$routes->get('/materials/delete/(:num)', 'Materials::delete/$1');
$routes->get('/materials/download/(:num)', 'Materials::download/$1');

// Teacher Materials Page
$routes->get('teacher/materials', 'Materials::adminMaterialsPage');

// AJAX Routes
$routes->post('materials/ajax-upload', 'Materials::ajaxUpload');
$routes->get('materials/ajax/all', 'Materials::getAllMaterialsAjax');
$routes->get('materials/ajax/courses', 'Materials::getAllCourses');
$routes->get('materials/stats/dashboard', 'Materials::getDashboardStats');
$routes->get('materials/ajax/course/(:num)', 'Materials::getMaterialsByCourse/$1');

// Admin Materials Page
$routes->get('admin/materials', 'Materials::adminMaterialsPage');



// Notifications Routes
$routes->get('notifications', 'Notifications::get');
$routes->get('notifications/all', 'Notifications::all'); // ✅ View all page
$routes->get('notifications/fetch', 'Notifications::get'); // ✅ AJAX fetch
$routes->post('notifications/mark-read/(:num)', 'Notifications::mark_as_read/$1');
$routes->post('notifications/mark-all-read', 'Notifications::mark_all_read');



