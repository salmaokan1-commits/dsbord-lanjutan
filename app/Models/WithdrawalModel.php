<?php

namespace App\Models;

use CodeIgniter\Model;

class WithdrawalModel extends Model
{
    protected $table      = 'withdrawals';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = [
        'merchant_id',
        'jumlah_tarik',
        'bank_tujuan',
        'nomor_rekening',
        'nama_rekening',
        'status',
        'approved_at',
    ];

    protected $validationRules = [
        'merchant_id' => 'required|integer',
        'jumlah_tarik' => 'required|decimal',
        'bank_tujuan' => 'required|max_length[150]',
        'nomor_rekening' => 'required|max_length[50]',
        'nama_rekening' => 'required|max_length[150]',
    ];

    public function getPendingByMerchant(int $merchantId)
    {
        return $this->where('merchant_id', $merchantId)
            ->where('status', 'pending')
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }
}
