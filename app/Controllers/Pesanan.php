<?php

namespace App\Controllers;

use App\Models\PesananModel;
use CodeIgniter\Controller;

class Pesanan extends BaseController
{
    public function simpan()
    {
        // Cek apakah user sudah login
        if (!session()->get('logged_in')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu'
            ]);
        }

        $model = new PesananModel();

        $menuPesananRaw = $this->request->getPost('menu_pesanan');
        $menuItems = json_decode($menuPesananRaw, true);

        if (!is_array($menuItems) || empty($menuItems)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Menu pesanan tidak valid. Pastikan Anda menambahkan item menu terlebih dahulu.'
            ]);
        }

        $subtotal = 0;
        $totalQty = 0;

        foreach ($menuItems as $item) {
            if (!isset($item['name'], $item['price'], $item['quantity'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Data menu pesanan tidak lengkap.'
                ]);
            }

            $price = (int) $item['price'];
            $qty = (int) $item['quantity'];

            if ($qty <= 0 || $price < 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Jumlah dan harga item harus valid.'
                ]);
            }

            $subtotal += $price * $qty;
            $totalQty += $qty;
        }

        $serviceFee = (int) round($subtotal * 0.02);
        $tax = (int) round($subtotal * 0.10);
        $totalBayar = $subtotal + $serviceFee + $tax;

        // Ambil data dari form
        $data = [
            'id_tempat'          => $this->request->getPost('id_tempat'),
            'nama_tempat'        => $this->request->getPost('nama_tempat'),
            'kategori'           => $this->request->getPost('kategori'),
            'nama_pemesan'       => $this->request->getPost('nama_pemesan'),
            'nomor_hp'           => $this->request->getPost('nomor_hp'),
            'menu_pesanan'       => json_encode($menuItems, JSON_UNESCAPED_UNICODE),
            'jumlah'             => $totalQty,
            'harga_perkiraan'    => max(0, $totalBayar),
            'catatan'            => $this->request->getPost('catatan'),
            'metode_pembayaran'  => $this->request->getPost('metode_pembayaran'),
            'status_pesanan'     => 'Menunggu Konfirmasi',
            'tanggal_pesan'      => date('Y-m-d H:i:s'),
            'user_id'            => session()->get('id_user') ?? null,
        ];

        // Validasi input
        if (empty($data['nama_pemesan']) || empty($data['nomor_hp']) || empty($data['menu_pesanan']) || $totalQty <= 0 || $data['harga_perkiraan'] <= 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Nama pemesan, nomor HP, dan menu tidak boleh kosong. Pastikan ada item pesanan dan total sudah dihitung.'
            ]);
        }

        try {
            // Insert data ke database
            $pesanan_id = $model->insert($data);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat',
                'pesanan_id' => $pesanan_id
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menyimpan pesanan: ' . $e->getMessage()
            ]);
        }
    }

    public function daftar()
    {
        // Proteksi halaman
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $model = new PesananModel();
        $data['pesanan'] = $model->findAll();

        return view('v_daftar_pesanan', $data);
    }

    public function hapus($id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $model = new PesananModel();
        $model->delete($id);

        return redirect()->to('/pesanan/daftar')->with('success', 'Pesanan berhasil dihapus');
    }
}
