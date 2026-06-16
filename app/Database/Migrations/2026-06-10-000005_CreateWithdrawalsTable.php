<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWithdrawalsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'auto_increment' => true,
            ],
            'merchant_id' => [
                'type'       => 'INT',
                'null'       => false,
            ],
            'jumlah_tarik' => [
                'type'       => 'DECIMAL',
                'constraint' => '14,2',
                'null'       => false,
            ],
            'bank_tujuan' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'nomor_rekening' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'nama_rekening' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'approved', 'rejected'],
                'default'    => 'pending',
            ],
            'approved_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('merchant_id');
        $this->forge->createTable('withdrawals');
    }

    public function down()
    {
        $this->forge->dropTable('withdrawals');
    }
}
