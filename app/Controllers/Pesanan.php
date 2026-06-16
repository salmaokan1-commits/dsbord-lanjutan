<?php

namespace App\Controllers;

use App\Models\KulinerModel;
use App\Models\MenuModel;
use App\Models\PesananDetailModel;
use App\Models\PesananModel;
use App\Services\OrderService;

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
        $menuModel = new MenuModel();
        $detailModel = new PesananDetailModel();

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

            $price = (float) $item['price'];
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

            if ($pesanan_id) {
                $kulinerId = (int) $data['id_tempat'];

                foreach ($menuItems as $item) {
                    $menuId = 0;

                    if (isset($item['id']) && is_numeric($item['id'])) {
                        $menuId = (int) $item['id'];
                    } elseif (! empty($data['id_tempat']) && isset($item['name'])) {
                        $menu = $menuModel->findByNameAndKuliner($item['name'], $kulinerId);
                        $menuId = $menu ? (int) $menu['id'] : 0;
                    }

                    $detailModel->insert([
                        'pesanan_id' => $pesanan_id,
                        'menu_id'    => $menuId,
                        'qty'        => (int) $item['quantity'],
                        'subtotal'   => (float) ($item['price'] * $item['quantity']),
                    ]);
                }
            }

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

    /**
     * Menyelesaikan pesanan kuliner
     * @param int $id ID dari Pesanan
     */
    public function complete(int $id)
    {
        if (!session()->get('logged_in')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu'
            ]);
        }

        $model = new PesananModel();
        $service = new OrderService();

        $pesanan = $model->find($id);

        if (! $pesanan) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan.'
            ])->setStatusCode(404);
        }

        if (strtolower($pesanan['status_pesanan']) === 'completed') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Pesanan sudah selesai.'
            ]);
        }

        $statusUpdated = $model->update($id, ['status_pesanan' => 'completed']);
        $walletCredited = $service->processOrderCompletion($id);

        return $this->response->setJSON([
            'success' => (bool) $statusUpdated,
            'message' => $statusUpdated ? 'Pesanan telah diselesaikan.' : 'Gagal menyelesaikan pesanan.',
            'walletCredited' => $walletCredited,
        ]);
    }

    public function requestWithdrawal()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'merchant') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Akses ditolak. Hanya merchant yang dapat mengajukan penarikan.'
            ])->setStatusCode(403);
        }

        $merchantId = session()->get('id') ?? session()->get('id_user');
        $jumlahTarik = (float) $this->request->getPost('jumlah_tarik');
        $bankData = [
            'bank_tujuan' => $this->request->getPost('bank_tujuan'),
            'nomor_rekening' => $this->request->getPost('nomor_rekening'),
            'nama_rekening' => $this->request->getPost('nama_rekening'),
        ];

        $service = new OrderService();
        $withdrawalId = $service->createWithdrawalRequest((int) $merchantId, $jumlahTarik, $bankData);

        if (! $withdrawalId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Saldo tidak mencukupi atau data penarikan tidak valid.'
            ])->setStatusCode(400);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Permintaan penarikan berhasil diajukan.',
            'withdrawal_id' => $withdrawalId,
        ]);
    }

    /**
     * Menyetujui penarikan saldo merchant
     * @param int $id ID dari Withdrawal
     */
    public function approveWithdrawal(int $id)
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'developer') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Akses ditolak. Hanya developer yang bisa menyetujui penarikan.'
            ])->setStatusCode(403);
        }

        $service = new OrderService();
        $approved = $service->approveWithdrawal($id);

        return $this->response->setJSON([
            'success' => $approved,
            'message' => $approved ? 'Penarikan disetujui.' : 'Gagal menyetujui penarikan.'
        ]);
    }

    /**
     * Menolak penarikan saldo merchant
     * @param int $id ID dari Withdrawal
     */
    public function rejectWithdrawal(int $id)
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'developer') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Akses ditolak. Hanya developer yang bisa menolak penarikan.'
            ])->setStatusCode(403);
        }

        $service = new OrderService();
        $rejected = $service->rejectWithdrawal($id);

        return $this->response->setJSON([
            'success' => $rejected,
            'message' => $rejected ? 'Penarikan ditolak.' : 'Gagal menolak penarikan.'
        ]);
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

    /**
     * Menghapus data pesanan kuliner
     * @param int $id ID dari Pesanan
     */
    public function hapus(int $id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $model = new PesananModel();
        $model->delete($id);

        return redirect()->to('/pesanan/daftar')->with('success', 'Pesanan berhasil dihapus');
    }
}