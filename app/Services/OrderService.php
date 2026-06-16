<?php

namespace App\Services;

use App\Models\KulinerModel;
use App\Models\PesananModel;
use App\Models\ReviewModel;
use App\Models\WalletModel;
use App\Models\WithdrawalModel;

class OrderService
{
    protected ReviewModel $reviewModel;
    protected KulinerModel $kulinerModel;
    protected WalletModel $walletModel;
    protected PesananModel $pesananModel;
    protected WithdrawalModel $withdrawalModel;

    public function __construct()
    {
        $this->reviewModel = new ReviewModel();
        $this->kulinerModel = new KulinerModel();
        $this->walletModel = new WalletModel();
        $this->pesananModel = new PesananModel();
        $this->withdrawalModel = new WithdrawalModel();
    }

    public function calculateAverageRatingForKuliner(int $kulinerId): float
    {
        $result = $this->reviewModel->getAverageRatingByKuliner($kulinerId);

        if (! $result || empty($result['avg_rating'])) {
            return 0.0;
        }

        return (float) number_format((float) $result['avg_rating'], 1, '.', '');
    }

    public function syncKulinerRating(int $kulinerId): float
    {
        $average = $this->calculateAverageRatingForKuliner($kulinerId);
        $ratingValue = $average > 0 ? $average : 0.0;

        $this->kulinerModel->update($kulinerId, [
            'rating' => $ratingValue > 0 ? $ratingValue : 'Belum Dirating',
        ]);

        return $ratingValue;
    }

    public function processOrderCompletion(int $pesananId): bool
    {
        $pesanan = $this->pesananModel->find($pesananId);

        if (! $pesanan) {
            return false;
        }

        $currentStatus = strtolower($pesanan['status_pesanan'] ?? '');
        $paymentMethod = strtolower($pesanan['metode_pembayaran'] ?? '');

        if ($currentStatus === 'completed' || $currentStatus === 'selesai') {
            return false;
        }

        if (! $this->isNonCashPayment($paymentMethod)) {
            return false;
        }

        $kulinerId = (int) ($pesanan['id_tempat'] ?? 0);
        $kuliner = $this->kulinerModel->find($kulinerId);

        if (! $kuliner || empty($kuliner['created_by'])) {
            return false;
        }

        $merchantId = (int) $kuliner['created_by'];
        $amount = (float) $pesanan['harga_perkiraan'];

        if ($amount <= 0) {
            return false;
        }

        return $this->walletModel->addBalance($merchantId, $amount);
    }

    protected function isNonCashPayment(string $method): bool
    {
        return str_contains($method, 'qris') || str_contains($method, 'debit') || str_contains($method, 'kredit');
    }

    public function createWithdrawalRequest(int $merchantId, float $jumlahTarik, array $bankData)
    {
        $wallet = $this->walletModel->findByMerchant($merchantId);
        $saldo = $wallet ? (float) $wallet['saldo'] : 0.0;

        if ($jumlahTarik <= 0 || $jumlahTarik > $saldo) {
            return false;
        }

        return $this->withdrawalModel->insert([
            'merchant_id' => $merchantId,
            'jumlah_tarik' => $jumlahTarik,
            'bank_tujuan' => $bankData['bank_tujuan'] ?? '',
            'nomor_rekening' => $bankData['nomor_rekening'] ?? '',
            'nama_rekening' => $bankData['nama_rekening'] ?? '',
            'status' => 'pending',
        ]);
    }

    public function approveWithdrawal(int $withdrawalId): bool
    {
        $withdrawal = $this->withdrawalModel->find($withdrawalId);

        if (! $withdrawal || $withdrawal['status'] !== 'pending') {
            return false;
        }

        $merchantId = (int) $withdrawal['merchant_id'];
        $amount = (float) $withdrawal['jumlah_tarik'];

        if (! $this->walletModel->subtractBalance($merchantId, $amount)) {
            return false;
        }

        return $this->withdrawalModel->update($withdrawalId, [
            'status' => 'approved',
            'approved_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function rejectWithdrawal(int $withdrawalId): bool
    {
        $withdrawal = $this->withdrawalModel->find($withdrawalId);

        if (! $withdrawal || $withdrawal['status'] !== 'pending') {
            return false;
        }

        return $this->withdrawalModel->update($withdrawalId, [
            'status' => 'rejected',
            'approved_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
