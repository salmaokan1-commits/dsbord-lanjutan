<?php

namespace App\Models;

use CodeIgniter\Model;

class PesananDetailModel extends Model
{
    protected $table      = 'pesanan_detail';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'pesanan_id',
        'menu_id',
        'qty',
        'subtotal',
    ];

    protected $validationRules = [
        'pesanan_id' => 'required|integer',
        'menu_id'    => 'required|integer',
        'qty'        => 'required|integer|greater_than[0]',
        'subtotal'   => 'required|decimal',
    ];

    public function getByPesanan(int $pesananId)
    {
        return $this->where('pesanan_id', $pesananId)->findAll();
    }
}
