<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * ============================================================
 * AuthFilter - Melindungi halaman dari user yang belum login
 * ============================================================
 * 
 * Middleware (atau Filter di CI4) berfungsi mengecek setiap request
 * sebelum sampai ke Controller.
 * 
 * Jika user belum login, kita cegah mereka masuk ke halaman
 * seperti /dashboard atau /document, lalu redirect ke halaman login.
 */
class AuthFilter implements FilterInterface
{
    /**
     * Dijalankan SEBELUM Controller dipanggil
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Cek apakah ada session 'is_logged_in'
        if (!session()->get('is_logged_in')) {
            // Jika belum login, tendang kembali ke halaman utama (login)
            // beserta pesan error
            return redirect()->to('/')->with('error', 'Silakan login terlebih dahulu untuk mengakses halaman ini.');
        }
    }

    /**
     * Dijalankan SETELAH Controller dipanggil (biasanya dibiarkan kosong)
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
