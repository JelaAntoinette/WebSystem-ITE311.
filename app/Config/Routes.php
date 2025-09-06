<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

<<<<<<< HEAD
// Default route - redirects to homepage
$routes->get('/', 'Home::index');

// Main application routes
=======
$routes->get('/', 'Home::index');
>>>>>>> 66ab1210812ed10f4233bf14cfcb48aa1710e1b2
$routes->get('/home', 'Home::index');
$routes->get('/about', 'Home::about');
$routes->get('/contact', 'Home::contact');

<<<<<<< HEAD
// Authentication Routes
$routes->get('/register', 'Auth::register');     // Show registration form
$routes->post('/register', 'Auth::register');    // Process registration
$routes->get('/login', 'Auth::login');           // Show login form
$routes->post('/login', 'Auth::login');          // Process login
$routes->get('/logout', 'Auth::logout');         // Logout user
$routes->get('/dashboard', 'Auth::dashboard');   // User dashboard
=======


>>>>>>> 66ab1210812ed10f4233bf14cfcb48aa1710e1b2
