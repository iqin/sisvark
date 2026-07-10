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
        $data['title'] = 'Register - Sistem Adaptif VARK';
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

        if ($user && password_verify($password, $user['kata_sandi'])) {
            $session->set([
                'user_id'    => $user['id'],
                'name'       => $user['nama'],
                'email'      => $user['email'],
                'role'       => $user['peran'],
                'isLoggedIn' => true,
            ]);

            if ($user['peran'] === 'guru') {
                return redirect()->to('/guru/dashboard');
            }
            return redirect()->to('/siswa/dashboard');
        }

        return redirect()->to('/login')->with('error', 'Email atau Password salah!');
    }

    // ======== PROSES REGISTER ========
    public function doRegister()
    {
        $model = new UserModel();

        $rules = [
            'nama'     => 'required|min_length[3]',
            'email'    => 'required|valid_email|is_unique[pengguna.email]',
            'kata_sandi' => 'required|min_length[6]',
            'peran'    => 'required|in_list[siswa,guru]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/register')
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        $model->save([
            'nama'       => $this->request->getPost('nama'),
            'email'      => $this->request->getPost('email'),
            'kata_sandi' => $this->request->getPost('kata_sandi'),
            'peran'      => $this->request->getPost('peran'),
            'sekolah'    => $this->request->getPost('sekolah'),
            'kelas'      => $this->request->getPost('kelas'),
        ]);

        return redirect()->to('/login')->with('success', 'Registrasi berhasil! Silakan login.');
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
        
        $userId = session()->get('user_id');
        
        // Ambil data VARK
        $varkModel = new \App\Models\VarkResultModel();
        $varkResult = $varkModel->where('pengguna_id', $userId)->first();
        
        // Ambil data ZPD (misal untuk modul 1)
        $zpdModel = new \App\Models\ZpdResultModel();
        $zpdMod1 = $zpdModel->where(['pengguna_id' => $userId, 'modul_id' => 1])->first();
        
        $data = [
            'title' => 'Dashboard Siswa',
            'varkResult' => $varkResult,
            'zpdMod1' => $zpdMod1,
        ];
        
        //return view('siswa/dashboard', $data);
        // Redirect ke halaman modul
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