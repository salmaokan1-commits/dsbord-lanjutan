<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ROOT → REDIRECT KE LOGIN
$routes->get('/', function() {
    return redirect()->to('/login');
});

// LOGIN
$routes->get('/login', 'Auth::login');
$routes->post('/login/process', 'Auth::processLogin');
$routes->get('/logout', 'Auth::logout');
$routes->get('/auth/google', 'Auth::googleLogin');
$routes->get('/auth/googleCallback', 'Auth::googleCallback');

// HOME (WAJIB LOGIN)
$routes->get('/home', 'Home::index', ['filter' => 'auth']);