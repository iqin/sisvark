<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    // ======== HALAMAN LOGIN ========
    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }
        $data['title'] = 'Login - Sistem Adaptif VARK';
        return view('auth/login', $data);
    }

    // ======== HALAMAN REGISTER ========
    public function register()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        // Ambil daftar kelas yang aktif
        $kelasModel = new \App\Models\KelasModel();
        $kelasList = $kelasModel->where('is_active', 1)->orderBy('id', 'ASC')->findAll();

        $data = [
            'title'      => 'Register - Sistem Adaptif VARK',
            'kelas_list' => $kelasList,
        ];
        return view('auth/register', $data);
    }

    // ======== PROSES LOGIN ========
    public function doLogin()
    {
        $session = session();
        $model = new UserModel();

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        if (empty($email) || empty($password)) {
            return redirect()->to('/login')->with('error', 'Email dan Password wajib diisi!');
        }

        $user = $model->where('email', $email)->first();

        if (!$user || !password_verify($password, $user['kata_sandi'])) {
            return redirect()->to('/login')->with('error', 'Email atau Password salah!');
        }

        // =============================================================
        // CEK STATUS AKTIVASI (khusus siswa)
        // =============================================================
        if ($user['peran'] === 'siswa' && (int) ($user['is_active'] ?? 0) === 0) {
            return redirect()->to('/login')->with('error', 
                'Akun Anda belum diaktifkan oleh guru. Silakan tunggu konfirmasi dari guru kelas Anda.');
        }

        // Set session
        $session->set([
            'user_id'    => $user['id'],
            'name'       => $user['nama'],
            'email'      => $user['email'],
            'role'       => $user['peran'],
            'is_admin'   => (int) ($user['is_admin'] ?? 0) === 1,
            'is_active'  => (int) ($user['is_active'] ?? 1) === 1,
            'isLoggedIn' => true,
        ]);

        // Redirect sesuai role
        if ($user['peran'] === 'guru') {
            return redirect()->to('/guru/dashboard');
        }

        return redirect()->to('/siswa/dashboard');
    }

    // ======== PROSES REGISTER (SISWA ONLY) ========
    public function doRegister()
    {
        $userModel    = new UserModel();
        $kelasModel   = new \App\Models\KelasModel();
        $anggotaModel = new \App\Models\KelasAnggotaModel();
        $pesanModel   = new \App\Models\PesanModel();

        // Validasi
        $rules = [
            'nama'       => 'required|min_length[3]|max_length[100]',
            'email'      => 'required|valid_email|is_unique[pengguna.email]',
            'kata_sandi' => 'required|min_length[6]',
            'kelas_id'   => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/register')
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        // Cek kelas_id valid
        $kelasId = (int) $this->request->getPost('kelas_id');
        $kelas   = $kelasModel->find($kelasId);
        if (!$kelas) {
            return redirect()->to('/register')
                             ->withInput()
                             ->with('error', 'Kelas yang dipilih tidak valid.');
        }

        $namaSiswa  = $this->request->getPost('nama');
        $emailSiswa = $this->request->getPost('email');

        // Simpan user sebagai siswa dengan is_active = 0 (menunggu verifikasi)
        $userModel->save([
            'nama'       => $namaSiswa,
            'email'      => $emailSiswa,
            'kata_sandi' => $this->request->getPost('kata_sandi'),
            'peran'      => 'siswa',
            'is_admin'   => 0,
            'is_active'  => 0,
            'sekolah'    => $kelas['nama'],
            'kelas'      => '-',
        ]);

        // Ambil user_id yang baru dibuat
        $newUserId = $userModel->insertID();

        // Daftarkan siswa ke kelas_anggota
        $anggotaModel->save([
            'kelas_id'  => $kelasId,
            'siswa_id'  => $newUserId,
            'status'    => 'aktif',
            'joined_at' => date('Y-m-d H:i:s'),
        ]);

        // =============================================================
        // KIRIM NOTIFIKASI KE GURU KELAS
        // =============================================================
        $notifText = "⚠️ **Pendaftaran Siswa Baru**\n\n" .
                     "Nama: {$namaSiswa}\n" .
                     "Email: {$emailSiswa}\n" .
                     "Kelas: {$kelas['nama']}\n\n" .
                     "Siswa menunggu verifikasi akun. Silakan buka halaman kelas untuk mengaktifkan akun siswa ini.";

        $pesanModel->save([
            'siswa_id'     => $newUserId,
            'guru_id'      => $kelas['guru_id'],
            'kelas_id'     => $kelasId,
            'pesan'        => $notifText,
            'is_read'      => 0,
            'is_from_guru' => 0,
            'parent_id'    => null,
            'created_at'   => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/login')
                         ->with('success', 
                            'Registrasi berhasil! Akun Anda <strong>menunggu verifikasi guru</strong> kelas ' . 
                            esc($kelas['nama']) . '. Silakan hubungi guru kelas Anda untuk aktivasi.');
    }

    // ======== LOGOUT ========
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }

    // ======== DASHBOARD SISWA ========
    public function studentDashboard()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'siswa') {
            return redirect()->to('/login');
        }

        // Langsung ke halaman modul
        return redirect()->to('/siswa/modul');
    }

    // ======== DASHBOARD GURU ========
    public function teacherDashboard()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'guru') {
            return redirect()->to('/login');
        }
        $data['title'] = 'Dashboard Guru';
        return view('guru/dashboard', $data);
    }

    // ======== DASHBOARD UMUM (REDIRECT) ========
    public function dashboard()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        if (session()->get('role') === 'guru') {
            return redirect()->to('/guru/dashboard');
        }
        return redirect()->to('/siswa/modul');
    }

        // ======== FORM GANTI PASSWORD ========
    public function gantiPassword()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        return view('auth/ganti_password', [
            'title' => 'Ganti Password',
        ]);
    }

    // ======== PROSES GANTI PASSWORD ========
    public function doGantiPassword()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $rules = [
            'kata_sandi_lama' => 'required',
            'kata_sandi_baru' => 'required|min_length[6]',
            'konfirmasi'      => 'required|matches[kata_sandi_baru]',
        ];

        $messages = [
            'kata_sandi_lama' => [
                'required' => 'Password lama wajib diisi.',
            ],
            'kata_sandi_baru' => [
                'required'   => 'Password baru wajib diisi.',
                'min_length' => 'Password baru minimal 6 karakter.',
            ],
            'konfirmasi' => [
                'required' => 'Konfirmasi password wajib diisi.',
                'matches'  => 'Konfirmasi password tidak cocok dengan password baru.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        $userId    = session()->get('user_id');
        $userModel = new UserModel();
        $user      = $userModel->find($userId);

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Sesi tidak valid. Silakan login ulang.');
        }

        // Verifikasi password lama
        $passwordLama = $this->request->getPost('kata_sandi_lama');
        if (!password_verify($passwordLama, $user['kata_sandi'])) {
            return redirect()->back()->with('error', 'Password lama salah.');
        }

        // Cek password baru tidak sama dengan yang lama
        $passwordBaru = $this->request->getPost('kata_sandi_baru');
        if ($passwordLama === $passwordBaru) {
            return redirect()->back()->with('error', 'Password baru tidak boleh sama dengan password lama.');
        }

        // Update password
        // UserModel sudah otomatis hash via beforeUpdate
        $userModel->update($userId, ['kata_sandi' => $passwordBaru]);

        return redirect()->to('/ganti-password')
                         ->with('success', 'Password berhasil diubah. Gunakan password baru untuk login berikutnya.');
    }

    // ======== HALAMAN PROFIL SISWA ========
    public function profil()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'siswa') {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');

        // Ambil hasil VARK
        $varkModel = new \App\Models\VarkResultModel();
        $varkResult = $varkModel->where('pengguna_id', $userId)->orderBy('id', 'DESC')->first();

        // Ambil ZPD per modul
        $zpdModel = new \App\Models\ZpdResultModel();
        $zpdMod1 = $zpdModel->where(['pengguna_id' => $userId, 'modul_id' => 1])->first();
        $zpdMod2 = $zpdModel->where(['pengguna_id' => $userId, 'modul_id' => 2])->first();
        $zpdMod3 = $zpdModel->where(['pengguna_id' => $userId, 'modul_id' => 3])->first();

        $data = [
            'title'    => 'Profil Saya',
            'varkResult' => $varkResult,
            'zpdMod1'  => $zpdMod1,
            'zpdMod2'  => $zpdMod2,
            'zpdMod3'  => $zpdMod3,
        ];

        return view('siswa/profil', $data);
    }
}