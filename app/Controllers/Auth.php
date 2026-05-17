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

        // Melakukan verifikasi password yang diinput dengan hash di database
        if ($user && password_verify($password, $user['password'])) {
            $session->set([
                'id'        => $user['id'],
                'nama'      => $user['nama'],
                'role'      => $user['role'],
                'logged_in' => true
            ]);

            return redirect()->to('/kuliner'); // Berhasil login, masuk ke dashboard
        }

        // Jika gagal, kembali ke halaman login dengan pesan error
        return redirect()->back()->with('error', 'Email/Username atau password salah');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
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

        // Jika user Google belum ada di database, otomatis registrasi
        if (!$user) {
            $model->insert([
                'nama'      => $data->name,
                'username'  => explode('@', $data->email)[0],
                'email'     => $data->email,
                'password'  => password_hash(uniqid(), PASSWORD_DEFAULT),
                'role'      => 'user',
                'google_id' => $data->id 
            ]);
            $user = $model->where('email', $data->email)->first();
        }

        session()->set([
            'id'        => $user['id'],
            'nama'      => $user['nama'],
            'role'      => $user['role'],
            'logged_in' => true
        ]);

        return redirect()->to('/kuliner');
    }
}