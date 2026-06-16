<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePesananDetailTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'auto_increment' => true,
            ],
            'pesanan_id' => [
                'type'       => 'INT',
                'null'       => false,
            ],
            'menu_id' => [
                'type'       => 'INT',
                'null'       => false,
            ],
            'qty' => [
                'type'       => 'INT',
                'default'    => 1,
            ],
            'subtotal' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => '0.00',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('pesanan_id');
        $this->forge->addKey('menu_id');
        $this->forge->createTable('pesanan_detail');
    }

    public function down()
    {
        $this->forge->dropTable('pesanan_detail');
    }
}
