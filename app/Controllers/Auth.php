<?php

namespace App\Controllers;

use App\Models\UserModel;
use Google\Client;
use Google\Service\Oauth2;

class Auth extends BaseController
{
    public function login()
    {
        return view('login'); // Memanggil tampilan login.php
    }

    public function processLogin()
    {
        $session = session();
        $model = new UserModel();

        $login = $this->request->getPost('login');
        $password = $this->request->getPost('password');

        // Mencari user berdasarkan email atau username
        $user = $model->where('email', $login)
                      ->orWhere('username', $login)
                      ->first();

        // Jika user tidak ditemukan
        if (!$user) {
            return redirect()->back()->with('error', 'Username atau email tidak ditemukan, silahkan klik DAFTAR AKUN BARU');
        }

        // Melakukan verifikasi password yang diinput dengan hash di database
        if (password_verify($password, $user['password'])) {
            $session->set([
                'id'        => $user['id'],
                'nama'      => $user['nama'],
                'role'      => $user['role'],
                'logged_in' => true
            ]);

            return redirect()->to('/kuliner'); // Berhasil login, masuk ke dashboard
        }

        // Jika password salah
        return redirect()->back()->with('error', 'Password salah');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    public function daftar()
    {
        return view('daftar_akun');
    }

    public function prosesDaftar()
    {
        $model = new UserModel();
        $email = $this->request->getPost('email');
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $role = $this->request->getPost('role');

        // Validasi password minimal 4 karakter
        if (strlen($password) < 4) {
            return redirect()->back()->with('error', 'Password minimal 4 karakter');
        }

        // Cek apakah email atau username sudah terdaftar
        $existingUser = $model->where('email', $email)->orWhere('username', $username)->first();
        if ($existingUser) {
            return redirect()->back()->with('error', 'Email atau username sudah terdaftar');
        }

        // Insert user baru
        $data = [
            'nama'     => $username,
            'username' => $username,
            'email'    => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role'     => $role
        ];

        if ($model->insert($data)) {
            return redirect()->to('/login')->with('success', 'Akun berhasil dibuat. Silahkan login');
        } else {
            return redirect()->back()->with('error', 'Gagal membuat akun');
        }
    }

    public function googleLogin()
    {
        $client = new \Google\Client();
        $client->setClientId('625128778616-3p6jkrmd8sjq3i5kjs74qcqvd3ti4715.apps.googleusercontent.com');
        $client->setClientSecret('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');
        $client->setRedirectUri('http://localhost:8080/auth/googleCallback');
        $client->addScope('email');
        $client->addScope('profile');

        return redirect()->to($client->createAuthUrl());
    }

    public function googleCallback()
    {
        $client = new \Google\Client();
        $client->setClientId('625128778616-3p6jkrmd8sjq3i5kjs74qcqvd3ti4715.apps.googleusercontent.com');
        $client->setClientSecret('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');
        $client->setRedirectUri('http://localhost:8080/auth/googleCallback');

        $code = $this->request->getVar('code');
        if (!$code) return redirect()->to('/login')->with('error', 'Code tidak ditemukan');

        $token = $client->fetchAccessTokenWithAuthCode($code);
        if (isset($token['error'])) return redirect()->to('/login')->with('error', 'Login Google gagal');

        $client->setAccessToken($token['access_token']);
        $google = new Oauth2($client);
        $data = $google->userinfo->get();

        $model = new UserModel();
        $user = $model->where('email', $data->email)->first();

        // Jika user Google belum ada di database, tampilkan form pilih role
        if (!$user) {
            session()->set([
                'google_data' => [
                    'name'      => $data->name,
                    'email'     => $data->email,
                    'username'  => explode('@', $data->email)[0],
                    'google_id' => $data->id
                ]
            ]);
            return redirect()->to('/auth/googleRoleSelect');
        }

        session()->set([
            'id'        => $user['id'],
            'nama'      => $user['nama'],
            'role'      => $user['role'],
            'logged_in' => true
        ]);

        return redirect()->to('/kuliner');
    }

    public function googleRoleSelect()
    {
        if (!session()->get('google_data')) {
            return redirect()->to('/login');
        }
        return view('google_role_select');
    }

    public function prosesgoogleRole()
    {
        $googleData = session()->get('google_data');
        if (!$googleData) {
            return redirect()->to('/login');
        }

        $role = $this->request->getPost('role');
        $model = new UserModel();

        $data = [
            'nama'      => $googleData['name'],
            'username'  => $googleData['username'],
            'email'     => $googleData['email'],
            'password'  => password_hash(uniqid(), PASSWORD_DEFAULT),
            'role'      => $role,
            'google_id' => $googleData['google_id']
        ];

        if ($model->insert($data)) {
            $user = $model->where('email', $googleData['email'])->first();
            session()->set([
                'id'        => $user['id'],
                'nama'      => $user['nama'],
                'role'      => $user['role'],
                'logged_in' => true
            ]);
            session()->remove('google_data');
            return redirect()->to('/kuliner');
        } else {
            return redirect()->to('/login')->with('error', 'Gagal membuat akun');
        }
    }
}