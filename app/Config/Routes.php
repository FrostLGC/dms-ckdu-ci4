<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// ============================================================
// ROUTE KONFIGURASI - Sistem Manajemen Dokumen (DMS)
// ============================================================

// ============================================================
// HALAMAN PUBLIK (tanpa login)
// ============================================================

// Halaman utama = Landing Page / Login
$routes->get('/', 'Home::index');

// ============================================================
// ROUTE AUTENTIKASI (Login & Logout)
// ============================================================

// POST /auth/login -> Proses verifikasi login
$routes->post('auth/login', 'Auth::login');

// GET /auth/logout -> Logout (hapus session)
$routes->get('auth/logout', 'Auth::logout');

// Dashboard (setelah login)
$routes->get('dashboard', 'Home::dashboard');

// ============================================================
// ROUTE DOKUMEN (CRUD + Preview + Download)
// ============================================================

// GET /document -> Daftar semua dokumen
$routes->get('document', 'Document::index');

// GET /document/create -> Form upload dokumen baru
$routes->get('document/create', 'Document::create');

// GET /document/detail/(:num) -> Detail dokumen
$routes->get('document/detail/(:num)', 'Document::detail/$1');

// GET /document/preview/(:num) -> Preview file di browser (PDF/gambar)
$routes->get('document/preview/(:num)', 'Document::preview/$1');

// GET /document/download/(:num) -> Download file
$routes->get('document/download/(:num)', 'Document::download/$1');

// POST /document/upload -> Proses upload dokumen
$routes->post('document/upload', 'Document::upload');

// POST /document/delete/(:num) -> Hapus dokumen
$routes->post('document/delete/(:num)', 'Document::delete/$1');

// GET /document/edit/(:num) -> Form edit/revisi dokumen
$routes->get('document/edit/(:num)', 'Document::edit/$1');

// POST /document/update/(:num) -> Proses update dokumen
$routes->post('document/update/(:num)', 'Document::update/$1');

// ============================================================
// ROUTE KATEGORI (CRUD)
// ============================================================

// GET /category -> Daftar semua kategori
$routes->get('category', 'Category::index');

// GET /category/create -> Form tambah kategori baru
$routes->get('category/create', 'Category::create');

// POST /category/store -> Proses simpan kategori baru
$routes->post('category/store', 'Category::store');

// GET /category/edit/(:num) -> Form edit kategori
$routes->get('category/edit/(:num)', 'Category::edit/$1');

// POST /category/update/(:num) -> Proses update kategori
$routes->post('category/update/(:num)', 'Category::update/$1');

// POST /category/delete/(:num) -> Hapus kategori
$routes->post('category/delete/(:num)', 'Category::delete/$1');

// ============================================================
// ROUTE AUDIT LOG
// ============================================================

// GET /auditlog -> Riwayat aktivitas DMS
$routes->get('auditlog', 'AuditLog::index');

// ============================================================
// ROUTE PENGGUNA (CRUD)
// ============================================================

// GET /user -> Daftar semua pengguna
$routes->get('user', 'User::index');

// GET /user/create -> Form tambah pengguna baru
$routes->get('user/create', 'User::create');

// POST /user/store -> Proses simpan pengguna baru
$routes->post('user/store', 'User::store');

// GET /user/edit/(:num) -> Form edit pengguna
$routes->get('user/edit/(:num)', 'User::edit/$1');

// POST /user/update/(:num) -> Proses update pengguna
$routes->post('user/update/(:num)', 'User::update/$1');

// POST /user/delete/(:num) -> Hapus pengguna
$routes->post('user/delete/(:num)', 'User::delete/$1');

// ============================================================
// ROUTE INSTANSI (CRUD)
// ============================================================

// GET /instansi -> Daftar semua instansi
$routes->get('instansi', 'Instansi::index');

// GET /instansi/create -> Form tambah instansi baru
$routes->get('instansi/create', 'Instansi::create');

// POST /instansi/store -> Proses simpan instansi baru
$routes->post('instansi/store', 'Instansi::store');

// GET /instansi/edit/(:num) -> Form edit instansi
$routes->get('instansi/edit/(:num)', 'Instansi::edit/$1');

// POST /instansi/update/(:num) -> Proses update instansi
$routes->post('instansi/update/(:num)', 'Instansi::update/$1');

// POST /instansi/delete/(:num) -> Hapus instansi
$routes->post('instansi/delete/(:num)', 'Instansi::delete/$1');

// ============================================================
// ROUTE PROFIL
// ============================================================

// GET /profile -> Tampilkan halaman profil user yang login
$routes->get('profile', 'Profile::index');

// POST /profile/update -> Proses update profil
$routes->post('profile/update', 'Profile::update');

// ============================================================
// ROUTE CETAK LAPORAN
// ============================================================

// GET /report -> Tampilkan halaman form laporan
$routes->get('report', 'Report::index');

// GET /report/print -> Proses cetak laporan
$routes->get('report/print', 'Report::print');

// GET /report/download-package -> Download paket dokumen dalam ZIP sesuai filter laporan
$routes->get('report/download-package', 'Report::downloadPackage');
