<?php

namespace Config;

$routes = Services::routes();

// ========== LANDING ==========
$routes->get('/', 'LandingController::index');

// ========== AUTH ==========
$routes->get('login', 'AuthController::login');
$routes->post('login/process', 'AuthController::doLogin');
$routes->get('register', 'AuthController::register');
$routes->post('register/process', 'AuthController::doRegister');
$routes->get('logout', 'AuthController::logout');
$routes->get('dashboard', 'AuthController::dashboard');

// ========== DASHBOARD SISWA & GURU ==========
$routes->get('siswa/dashboard', 'AuthController::studentDashboard');
//$routes->get('guru/dashboard', 'AuthController::teacherDashboard');

// ========== MODUL SISWA ==========
$routes->get('siswa/modul', 'ModulController::index');

// ========== PLACEHOLDER UNTUK FITUR BELUM DIBUAT ==========
$routes->get('vark', 'VarkController::index');
$routes->get('zpd/test/(:num)', 'ZpdController::test/$1');
$routes->get('materi/(:num)', 'MateriController::index/$1');

// ========== GURU ==========
$routes->get('guru/dashboard', 'GuruController::dashboard');

// KELOLA SOAL VARK
$routes->get('guru/vark/soal', 'GuruController::varkSoal');
$routes->get('guru/vark/soal/tambah', 'GuruController::varkSoalTambah');
$routes->post('guru/vark/soal/simpan', 'GuruController::varkSoalSimpan');
$routes->get('guru/vark/soal/edit/(:num)', 'GuruController::varkSoalEdit/$1');
$routes->post('guru/vark/soal/update/(:num)', 'GuruController::varkSoalUpdate/$1');
$routes->get('guru/vark/soal/hapus/(:num)', 'GuruController::varkSoalHapus/$1');

// Hasil VARK Siswa
$routes->get('guru/vark/hasil', 'GuruController::varkHasil');

// Test VARK
$routes->get('vark', 'VarkController::index');
$routes->get('vark/start', 'VarkController::start');
$routes->get('vark/soal', 'VarkController::soal');
$routes->post('vark/submit', 'VarkController::submit');
$routes->get('vark/hasil', 'VarkController::hasil');

