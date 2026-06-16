<?php

namespace App\Models;

use CodeIgniter\Model;

class WalletModel extends Model
{
    protected $table      = 'merchant_wallets';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = [
        'merchant_id',
        'saldo',
    ];

    protected $validationRules = [
        'merchant_id' => 'required|integer',
        'saldo'       => 'required|decimal',
    ];

    public function findByMerchant(int $merchantId)
    {
        return $this->where('merchant_id', $merchantId)->first();
    }

    public function addBalance(int $merchantId, float $amount): bool
    {
        $wallet = $this->findByMerchant($merchantId);

        if (! $wallet) {
            $wallet = [
                'merchant_id' => $merchantId,
                'saldo' => 0.00,
            ];
            $this->insert($wallet);
            $wallet = $this->findByMerchant($merchantId);
        }

        $newSaldo = (float) $wallet['saldo'] + $amount;

        return $this->update($wallet['id'], ['saldo' => $newSaldo]);
    }

    public function subtractBalance(int $merchantId, float $amount): bool
    {
        $wallet = $this->findByMerchant($merchantId);

        if (! $wallet || (float) $wallet['saldo'] < $amount) {
            return false;
        }

        $newSaldo = (float) $wallet['saldo'] - $amount;

        return $this->update($wallet['id'], ['saldo' => $newSaldo]);
    }
}
