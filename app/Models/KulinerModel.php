<?php

namespace App\Models;

use CodeIgniter\Model;

class KulinerModel extends Model
{
    protected $table      = 'kuliner';
    protected $primaryKey = 'id';

    // Field yang boleh diisi (sesuai dengan kolom di phpMyAdmin tadi)
    protected $allowedFields = [
        'nama_tempat', 'foto', 'rating', 'alamat_lengkap',
        'jam_operasional', 'no_telp', 'kategori',
        'harga_rata_rata', 'latitude', 'longitude', 'created_by'
    ];

    // Otomatis mengelola created_at dan updated_at
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}