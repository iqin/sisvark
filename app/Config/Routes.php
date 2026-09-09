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
$routes->get('siswa/profil', 'AuthController::profil');

// ========== MATERI ADAPTIF ==========
$routes->get('materi/(:num)', 'MateriController::index/$1');


// ========== PLACEHOLDER UNTUK FITUR BELUM DIBUAT ==========
$routes->get('zpd/test/(:num)', 'ZpdController::test/$1');
$routes->get('materi/(:num)', 'MateriController::index/$1');

// ========== GURU ==========
$routes->get('guru/dashboard', 'GuruController::dashboard');

// ========== GURU KELOLA MATERI ADAPTIF ==========
$routes->get('guru/materi', 'GuruController::materiIndex');
$routes->get('guru/materi/edit/(:num)', 'GuruController::materiEdit/$1');
$routes->post('guru/materi/update/(:num)', 'GuruController::materiUpdate/$1');

// KELOLA SOAL VARK
$routes->get('guru/vark/soal', 'GuruController::varkSoal');
$routes->get('guru/vark/soal/tambah', 'GuruController::varkSoalTambah');
$routes->post('guru/vark/soal/simpan', 'GuruController::varkSoalSimpan');
$routes->get('guru/vark/soal/edit/(:num)', 'GuruController::varkSoalEdit/$1');
$routes->post('guru/vark/soal/update/(:num)', 'GuruController::varkSoalUpdate/$1');
$routes->get('guru/vark/soal/hapus/(:num)', 'GuruController::varkSoalHapus/$1');

// ========== KELOLA SOAL ZPD ==========
$routes->get('guru/zpd/soal', 'GuruController::zpdSoal');
$routes->get('guru/zpd/soal/tambah', 'GuruController::zpdSoalTambah');
$routes->post('guru/zpd/soal/simpan', 'GuruController::zpdSoalSimpan');
$routes->get('guru/zpd/soal/edit/(:num)', 'GuruController::zpdSoalEdit/$1');
$routes->post('guru/zpd/soal/update/(:num)', 'GuruController::zpdSoalUpdate/$1');
$routes->get('guru/zpd/soal/hapus/(:num)', 'GuruController::zpdSoalHapus/$1');

// Hasil VARK Siswa
$routes->get('guru/vark/hasil', 'GuruController::varkHasil');

// Test VARK
$routes->get('vark', 'VarkController::index');
$routes->get('vark/start', 'VarkController::start');
$routes->get('vark/soal', 'VarkController::soal');
$routes->post('vark/submit', 'VarkController::submit');
$routes->get('vark/hasil', 'VarkController::hasil');

// ========== ZPD ==========
$routes->get('zpd/test/(:num)', 'ZpdController::test/$1');
$routes->get('zpd/start/(:num)', 'ZpdController::start/$1');
$routes->get('zpd/soal/(:num)', 'ZpdController::soal/$1');
$routes->post('zpd/submit/(:num)', 'ZpdController::submit/$1');
$routes->get('zpd/hasil/(:num)', 'ZpdController::hasil/$1');

// ========== POST TEST ==========
$routes->get('posttest/intro/(:num)', 'PostTestController::intro/$1');
$routes->get('posttest/start/(:num)', 'PostTestController::start/$1');
$routes->get('posttest/soal/(:num)', 'PostTestController::soal/$1');
$routes->post('posttest/submit/(:num)', 'PostTestController::submit/$1');
$routes->get('posttest/hasil/(:num)', 'PostTestController::hasil/$1');

// ========== CHAT ==========
$routes->post('chat/send', 'ChatController::send');
$routes->get('chat/history', 'ChatController::history');
$routes->get('chat/history/json', 'ChatController::historyJson');
$routes->get('guru/pesan', 'ChatController::index');
$routes->get('chat/unread', 'ChatController::unreadCount');
$routes->post('chat/reply', 'ChatController::reply');
$routes->get('chat/history/siswa/(:num)', 'ChatController::historySiswa/$1');
$routes->get('chat/history/json/guru', 'ChatController::historyJsonGuru');
