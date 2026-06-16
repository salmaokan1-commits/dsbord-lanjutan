<?php

use CodeIgniter\Router\RouteCollection;
use Dompdf\Dompdf;

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
$routes->get('/auth/daftar', 'Auth::daftar');
$routes->post('/auth/prosesDaftar', 'Auth::prosesDaftar');
$routes->get('/auth/google', 'Auth::googleLogin');
$routes->get('/auth/googleCallback', 'Auth::googleCallback');
$routes->get('/auth/googleRoleSelect', 'Auth::googleRoleSelect');
$routes->post('/auth/prosesgoogleRole', 'Auth::prosesgoogleRole');
$routes->get('/kuliner', 'Kuliner::index');
$routes->post('/kuliner/tambah', 'Kuliner::tambah');
$routes->post('/kuliner/update', 'Kuliner::update');
$routes->post('/kuliner/delete', 'Kuliner::delete');
$routes->post('/pesanan/simpan', 'Pesanan::simpan');
$routes->get('/pesanan/daftar', 'Pesanan::daftar');
$routes->get('/pesanan/hapus/(:num)', 'Pesanan::hapus/$1');
$routes->post('/pesanan/complete/(:num)', 'Pesanan::complete/$1');
$routes->post('/withdrawal/request', 'Pesanan::requestWithdrawal');
$routes->post('/withdrawal/approve/(:num)', 'Pesanan::approveWithdrawal/$1');
$routes->post('/withdrawal/reject/(:num)', 'Pesanan::rejectWithdrawal/$1');

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

//domppdf
$routes->get('download', 'Domppdf::index');
