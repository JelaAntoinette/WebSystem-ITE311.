<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateUsersRole extends Migration
{
    public function up()
    {
        // Ensure role column has correct ENUM values
        $this->forge->modifyColumn('users', [
            'role' => [
                'name' => 'role',
                'type' => 'ENUM',
                'constraint' => ['admin','teacher','student'],
                'default' => 'student',
            ],
        ]);
    }

    public function down()
    {
        // Optional: revert to previous ENUM if needed
        $this->forge->modifyColumn('users', [
            'role' => [
                'name' => 'role',
                'type' => 'ENUM',
                'constraint' => ['admin','user'],
                'default' => 'user',
            ],
        ]);
    }
}
