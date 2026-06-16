<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMerchantWalletsTable extends Migration
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
            'saldo' => [
                'type'       => 'DECIMAL',
                'constraint' => '14,2',
                'default'    => '0.00',
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
        $this->forge->createTable('merchant_wallets');
    }

    public function down()
    {
        $this->forge->dropTable('merchant_wallets');
    }
}
