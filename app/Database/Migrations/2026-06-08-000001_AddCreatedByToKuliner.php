<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCreatedByToKuliner extends Migration
{
    public function up()
    {
        $this->forge->addColumn('kuliner', [
            'created_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'after'      => 'foto'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('kuliner', 'created_by');
    }
}
