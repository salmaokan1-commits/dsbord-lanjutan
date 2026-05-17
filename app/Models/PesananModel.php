<?php

namespace App\Models;

use CodeIgniter\Model;

class PesananModel extends Model
{
    protected $table = 'pesanan';
    protected $primaryKey = 'id_pesanan';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'id_tempat',
        'nama_tempat',
        'kategori',
        'nama_pemesan',
        'nomor_hp',
        'menu_pesanan',
        'jumlah',
        'harga_perkiraan',
        'catatan',
        'metode_pembayaran',
        'status_pesanan',
        'tanggal_pesan',
        'user_id'
    ];

    protected $useTimestamps = false;

    // Validasi
    protected $validationRules = [
        'nama_pemesan'      => 'required|min_length[3]',
        'nomor_hp'          => 'required|numeric|min_length[10]',
        'menu_pesanan'      => 'required|min_length[3]',
        'jumlah'            => 'required|numeric|greater_than[0]',
        'metode_pembayaran' => 'required',
    ];

    protected $validationMessages = [
        'nama_pemesan' => [
            'required' => 'Nama pemesan harus diisi',
            'min_length' => 'Nama pemesan minimal 3 karakter'
        ],
        'nomor_hp' => [
            'required' => 'Nomor HP harus diisi',
            'numeric' => 'Nomor HP hanya boleh angka',
            'min_length' => 'Nomor HP minimal 10 angka'
        ],
        'menu_pesanan' => [
            'required' => 'Menu pesanan harus diisi',
            'min_length' => 'Menu pesanan minimal 3 karakter'
        ],
        'jumlah' => [
            'required' => 'Jumlah harus diisi',
            'numeric' => 'Jumlah harus angka',
            'greater_than' => 'Jumlah minimal 1'
        ],
        'metode_pembayaran' => [
            'required' => 'Metode pembayaran harus dipilih'
        ]
    ];

    public function getPesananByUser($user_id)
    {
        return $this->where('user_id', $user_id)->orderBy('tanggal_pesan', 'DESC')->findAll();
    }

    public function getPesananByStatus($status)
    {
        return $this->where('status_pesanan', $status)->orderBy('tanggal_pesan', 'DESC')->findAll();
    }

    public function getPesananByKategori($kategori)
    {
        return $this->where('kategori', $kategori)->orderBy('tanggal_pesan', 'DESC')->findAll();
    }
}
