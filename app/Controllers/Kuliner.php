<?php

namespace App\Controllers;

use App\Models\KulinerModel; // Memanggil model untuk akses database
use CodeIgniter\Controller;

class Kuliner extends BaseController
{
    public function index()
    {
        // 1. Proteksi Halaman: Jika belum login, arahkan kembali ke halaman login
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $model = new KulinerModel();
        
        // 2. Mengambil semua data kuliner dari database
        $data['kuliner'] = $model->findAll(); 

        // 3. Mengirim data ke View 'v_peta_kuliner.php'
        // Variabel $nama_user akan muncul di navbar dashboard yang baru kita buat
        $data['nama_user'] = session()->get('nama');

        return view('v_peta_kuliner', $data);
    }

    /**
     * Fungsi opsional jika nanti kamu ingin menambah data dari form web
     * (Seperti yang kita bahas tadi)
     */
    public function simpan()
    {
        $model = new KulinerModel();
        
        // Mengambil file foto yang diupload
        $fileFoto = $this->request->getFile('foto');
        
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $namaFoto = $fileFoto->getRandomName();
            $fileFoto->move('img', $namaFoto);
        } else {
            $namaFoto = 'default.jpg'; // Gambar cadangan jika upload gagal
        }

        $model->insert([
            'nama_tempat'      => $this->request->getPost('nama_tempat'),
            'kategori'         => $this->request->getPost('kategori'),
            'rating'           => $this->request->getPost('rating'),
            'latitude'         => $this->request->getPost('latitude'),
            'longitude'        => $this->request->getPost('longitude'),
            'alamat_lengkap'   => $this->request->getPost('alamat_lengkap'),
            'foto'             => $namaFoto,
        ]);

        return redirect()->to('/kuliner');
    }
}