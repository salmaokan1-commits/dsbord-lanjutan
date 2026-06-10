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

    /**
     * Method untuk menambah tempat makan melalui AJAX (dari modal form)
     */
    public function tambah()
    {
        // Validasi: hanya developer dan merchant yang bisa menambah
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'developer' && $role !== 'merchant')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Akses ditolak. Hanya developer dan merchant yang bisa menambah tempat.'
            ])->setStatusCode(403);
        }

        $model = new KulinerModel();

        // Validasi file foto
        $fileFoto = $this->request->getFile('foto');
        $namaFoto = null;

        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            // Cek ukuran file (maksimal 5MB)
            if ($fileFoto->getSize() > 5 * 1024 * 1024) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Ukuran file terlalu besar. Maksimal 5MB.'
                ]);
            }

            $namaFoto = $fileFoto->getRandomName();
            $fileFoto->move(ROOTPATH . 'public/img', $namaFoto);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Foto harus diupload.'
            ]);
        }

        try {
            // Ambil dan validasi data dari form
            $data = [
                'nama_tempat'      => $this->request->getPost('nama_tempat'),
                'kategori'         => $this->request->getPost('kategori'),
                'rating'           => $this->request->getPost('rating'),
                'latitude'         => (float) $this->request->getPost('latitude'),
                'longitude'        => (float) $this->request->getPost('longitude'),
                'alamat_lengkap'   => $this->request->getPost('alamat_lengkap'),
                'jam_operasional'  => $this->request->getPost('jam_operasional'),
                'no_telp'          => $this->request->getPost('no_telp'),
                'harga_rata_rata'  => (int) $this->request->getPost('harga_rata_rata'),
                'foto'             => $namaFoto,
                'created_by'       => session()->get('id')
            ];

            // Insert ke database
            if ($model->insert($data)) {
                $lastId = $model->insertID();

                // Ambil data yang baru ditambahkan
                $tempatBaru = $model->find($lastId);

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Tempat makan berhasil ditambahkan!',
                    'tempat' => $tempatBaru
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menyimpan ke database.'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    /**
     * Method untuk update tempat makan melalui AJAX
     */
    public function update()
    {
        // Validasi: hanya developer dan merchant yang bisa update
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'developer' && $role !== 'merchant')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Akses ditolak. Hanya developer dan merchant yang bisa edit tempat.'
            ])->setStatusCode(403);
        }

        $model = new KulinerModel();
        $id = $this->request->getPost('id');

        // Validasi ID
        if (!$id || !$model->find($id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data tidak ditemukan.'
            ])->setStatusCode(404);
        }

        try {
            $dataLama = $model->find($id);

            // Validasi ownership untuk merchant
            if ($role === 'merchant' && (int)$dataLama['created_by'] !== (int)session()->get('id')) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Pilih tempat makan yang kamu kelolah'
                ])->setStatusCode(403);
            }

            $namaFoto = $dataLama['foto']; // Default: foto lama

            // Validasi dan proses file foto jika ada yang baru
            $fileFoto = $this->request->getFile('foto');
            if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
                // Cek ukuran file (maksimal 5MB)
                if ($fileFoto->getSize() > 5 * 1024 * 1024) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Ukuran file terlalu besar. Maksimal 5MB.'
                    ]);
                }

                // Hapus foto lama
                $fotoLamaPath = ROOTPATH . 'public/img/' . $dataLama['foto'];
                if (file_exists($fotoLamaPath)) {
                    unlink($fotoLamaPath);
                }

                // Upload foto baru
                $namaFoto = $fileFoto->getRandomName();
                $fileFoto->move(ROOTPATH . 'public/img', $namaFoto);
            }

            // Ambil data untuk update
            $data = [
                'nama_tempat'      => $this->request->getPost('nama_tempat'),
                'kategori'         => $this->request->getPost('kategori'),
                'rating'           => $this->request->getPost('rating'),
                'latitude'         => (float) $this->request->getPost('latitude'),
                'longitude'        => (float) $this->request->getPost('longitude'),
                'alamat_lengkap'   => $this->request->getPost('alamat_lengkap'),
                'jam_operasional'  => $this->request->getPost('jam_operasional'),
                'no_telp'          => $this->request->getPost('no_telp'),
                'harga_rata_rata'  => (int) $this->request->getPost('harga_rata_rata'),
                'foto'             => $namaFoto,
            ];

            // Update database
            if ($model->update($id, $data)) {
                $tempatUpdate = $model->find($id);

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Tempat makan berhasil diupdate!',
                    'tempat' => $tempatUpdate
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal mengupdate database.'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    /**
     * Method untuk delete tempat makan melalui AJAX
     */
    public function delete()
    {
        // Validasi: hanya developer dan merchant yang bisa delete
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'developer' && $role !== 'merchant')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Akses ditolak. Hanya developer dan merchant yang bisa hapus tempat.'
            ])->setStatusCode(403);
        }

        $model = new KulinerModel();
        $id = $this->request->getPost('id');

        // Validasi ID
        if (!$id || !$model->find($id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data tidak ditemukan.'
            ])->setStatusCode(404);
        }

        try {
            $data = $model->find($id);

            // Validasi ownership untuk merchant
            if ($role === 'merchant' && (int)$data['created_by'] !== (int)session()->get('id')) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Pilih tempat makan yang kamu kelolah'
                ])->setStatusCode(403);
            }

            // Hapus foto dari server
            $fotoPath = ROOTPATH . 'public/img/' . $data['foto'];
            if (file_exists($fotoPath)) {
                unlink($fotoPath);
            }

            // Hapus dari database
            if ($model->delete($id)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Tempat makan berhasil dihapus!',
                    'id' => $id,
                    'foto' => $data['foto'],
                    'latitude' => $data['latitude'],
                    'longitude' => $data['longitude']
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menghapus dari database.'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }
}