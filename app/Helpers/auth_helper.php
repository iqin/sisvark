<?php

/**
 * Helper untuk otentikasi & otorisasi
 * Menyediakan fungsi cek role, cek admin, dan guard akses
 */

if (!function_exists('is_logged_in')) {
    /**
     * Cek apakah user sudah login
     */
    function is_logged_in(): bool
    {
        return (bool) session()->get('isLoggedIn');
    }
}

if (!function_exists('current_user_id')) {
    /**
     * Ambil ID user yang sedang login
     */
    function current_user_id(): ?int
    {
        $id = session()->get('user_id');
        return $id !== null ? (int) $id : null;
    }
}

if (!function_exists('current_role')) {
    /**
     * Ambil role user: 'siswa' | 'guru'
     */
    function current_role(): ?string
    {
        return session()->get('role');
    }
}

if (!function_exists('is_siswa')) {
    /**
     * Cek apakah user yang login adalah siswa
     */
    function is_siswa(): bool
    {
        return current_role() === 'siswa';
    }
}

if (!function_exists('is_guru')) {
    /**
     * Cek apakah user yang login adalah guru (admin atau guru biasa)
     */
    function is_guru(): bool
    {
        return current_role() === 'guru';
    }
}

if (!function_exists('is_admin')) {
    /**
     * Cek apakah user yang login adalah admin
     * Admin = guru dengan is_admin = 1
     */
    function is_admin(): bool
    {
        return is_guru() && (bool) session()->get('is_admin');
    }
}

if (!function_exists('is_guru_biasa')) {
    /**
     * Cek apakah user yang login adalah guru biasa (bukan admin)
     */
    function is_guru_biasa(): bool
    {
        return is_guru() && !is_admin();
    }
}

if (!function_exists('is_readonly_mode')) {
    /**
     * Cek apakah user sedang dalam mode read-only
     * Guru biasa = read-only untuk konten (Soal VARK, Soal ZPD, Materi Adaptif)
     */
    function is_readonly_mode(): bool
    {
        return is_guru_biasa();
    }
}

if (!function_exists('require_login')) {
    /**
     * Guard: harus login. Redirect ke /login jika belum.
     * Mengembalikan true jika OK, atau Response redirect jika tidak.
     */
    function require_login()
    {
        if (!is_logged_in()) {
            return redirect()->to('/login');
        }
        return true;
    }
}

if (!function_exists('require_role')) {
    /**
     * Guard: harus role tertentu. Redirect jika tidak sesuai.
     *
     * @param string $role 'siswa' | 'guru'
     */
    function require_role(string $role)
    {
        if (!is_logged_in()) {
            return redirect()->to('/login');
        }
        if (current_role() !== $role) {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak.');
        }
        return true;
    }
}

if (!function_exists('require_admin')) {
    /**
     * Guard: harus admin. Redirect jika bukan admin.
     */
    function require_admin()
    {
        if (!is_logged_in()) {
            return redirect()->to('/login');
        }
        if (!is_admin()) {
            return redirect()->to('/guru/dashboard')->with('error', 'Akses khusus admin.');
        }
        return true;
    }
}

if (!function_exists('require_guru')) {
    /**
     * Guard: harus guru (admin atau guru biasa).
     */
    function require_guru()
    {
        if (!is_logged_in()) {
            return redirect()->to('/login');
        }
        if (!is_guru()) {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak.');
        }
        return true;
    }
}

if (!function_exists('require_siswa')) {
    /**
     * Guard: harus siswa.
     */
    function require_siswa()
    {
        if (!is_logged_in()) {
            return redirect()->to('/login');
        }
        if (!is_siswa()) {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak.');
        }
        return true;
    }
}

if (!function_exists('role_label')) {
    /**
     * Label tampilan untuk role user
     */
    function role_label(): string
    {
        if (is_admin()) {
            return 'Admin';
        }
        if (is_guru()) {
            return 'Guru';
        }
        if (is_siswa()) {
            return 'Siswa';
        }
        return 'Guest';
    }
}

if (!function_exists('role_badge_class')) {
    /**
     * Class Bootstrap untuk badge role
     */
    function role_badge_class(): string
    {
        if (is_admin()) {
            return 'bg-danger';
        }
        if (is_guru()) {
            return 'bg-primary';
        }
        if (is_siswa()) {
            return 'bg-success';
        }
        return 'bg-secondary';
    }
}