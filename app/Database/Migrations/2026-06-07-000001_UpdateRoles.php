<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateRoles extends Migration
{
    public function up()
    {
        // Rename admin role to developer for existing users
        $this->db->query("UPDATE users SET role = 'developer' WHERE role = 'admin'");
    }

    public function down()
    {
        // Revert: rename developer role back to admin
        $this->db->query("UPDATE users SET role = 'admin' WHERE role = 'developer'");
    }
}
