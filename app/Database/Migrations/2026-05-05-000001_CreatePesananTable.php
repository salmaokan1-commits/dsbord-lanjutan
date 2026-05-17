<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePesananTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_pesanan' => [
                'type'           => 'INT',
                'auto_increment'  => true,
            ],
            'id_tempat' => [
                'type'       => 'INT',
                'null'       => true,
            ],
            'nama_tempat' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'kategori' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'nama_pemesan' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'nomor_hp' => [
                'type'       => 'VARCHAR',
                'constraint' => '15',
            ],
            'menu_pesanan' => [
                'type' => 'TEXT',
            ],
            'jumlah' => [
                'type'       => 'INT',
                'default'    => 1,
            ],
            'harga_perkiraan' => [
                'type'       => 'INT',
                'default'    => 0,
            ],
            'catatan' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'metode_pembayaran' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'status_pesanan' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => 'Menunggu Konfirmasi',
            ],
            'tanggal_pesan' => [
                'type'       => 'DATETIME',
                'default'    => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
            'user_id' => [
                'type'       => 'INT',
                'null'       => true,
            ],
        ]);

        $this->forge->addKey('id_pesanan', true);
        $this->forge->addKey('id_tempat');
        $this->forge->addKey('user_id');
        $this->forge->createTable('pesanan');
    }

    public function down()
    {
        $this->forge->dropTable('pesanan');
    }
}
