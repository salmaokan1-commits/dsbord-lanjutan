<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateRoleEnum extends Migration
{
    public function up()
    {
        // Mengubah enum role dari ('admin','user') menjadi ('developer','user','merchant')
        // dan sekaligus mengubah 'admin' menjadi 'developer'
        $this->db->query("ALTER TABLE users MODIFY role ENUM('developer','user','merchant') DEFAULT 'user'");
        $this->db->query("UPDATE users SET role = 'developer' WHERE role = 'admin'");
    }

    public function down()
    {
        // Revert ke enum lama (jika diperlukan)
        $this->db->query("ALTER TABLE users MODIFY role ENUM('admin','user') DEFAULT 'user'");
        $this->db->query("UPDATE users SET role = 'admin' WHERE role = 'developer'");
    }
}
