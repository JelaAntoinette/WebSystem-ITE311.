<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name'     => 'Admin nigaret',
                'email'    => 'Angrt@gmail.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role'     => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'     => 'Prof. kumalala',
                'email'    => 'kumalala01@gmail.com',
                'password' => password_hash('instructor123', PASSWORD_DEFAULT),
                'role'     => 'instructor',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'     => 'Queen yasmin',
                'email'    => 'Qyasmin00@gmail.com',
                'password' => password_hash('student123', PASSWORD_DEFAULT),
                'role'     => 'student',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            
        ];

        // Insert all at once
        $this->db->table('users')->insertBatch($data);
    }
}
