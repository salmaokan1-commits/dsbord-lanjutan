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
$routes->post('/login/processLogin', 'Auth::processLogin');
$routes->get('/logout', 'Auth::logout');
$routes->get('/auth/google', 'Auth::googleLogin');
$routes->get('/auth/googleCallback', 'Auth::googleCallback');
$routes->get('/kuliner', 'Kuliner::index');
$routes->post('/pesanan/simpan', 'Pesanan::simpan');
$routes->get('/pesanan/daftar', 'Pesanan::daftar');
$routes->get('/pesanan/hapus/(:num)', 'Pesanan::hapus/$1');

// HOME (WAJIB LOGIN)
$routes->get('/home', 'Home::index', ['filter' => 'auth']);
// Route untuk Login Manual
$routes->post('auth/processLogin', 'Auth::processLogin');

// Route untuk Login Google
$routes->get('auth/googleLogin', 'Auth::googleLogin');
$routes->get('auth/googleCallback', 'Auth::googleCallback');

// Route tambahan agar rapi
$routes->get('login', 'Auth::login');
$routes->get('logout', 'Auth::logout');
