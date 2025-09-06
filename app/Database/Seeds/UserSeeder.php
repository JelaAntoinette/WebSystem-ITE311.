<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

<<<<<<< HEAD
/**
 * UserSeeder - Seeds the users table with sample data
 */
=======
>>>>>>> 66ab1210812ed10f4233bf14cfcb48aa1710e1b2
class UserSeeder extends Seeder
{
    public function run()
    {
<<<<<<< HEAD
        // Insert sample users matching the users table structure
        $data = [
            [
                'name'       => 'System Administrator',
                'email'      => 'admin@lms.com',
                'password'   => password_hash('admin123', PASSWORD_DEFAULT),
                'role'       => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'John Smith',
                'email'      => 'john@lms.com',
                'password'   => password_hash('user123', PASSWORD_DEFAULT),
                'role'       => 'user',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'Jane Doe',
                'email'      => 'jane@lms.com',
                'password'   => password_hash('user123', PASSWORD_DEFAULT),
                'role'       => 'user',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'Bob Johnson',
                'email'      => 'bob@lms.com',
                'password'   => password_hash('user123', PASSWORD_DEFAULT),
                'role'       => 'user',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Insert data into users table
        $this->db->table('users')->insertBatch($data);
    }
}
=======
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
>>>>>>> 66ab1210812ed10f4233bf14cfcb48aa1710e1b2
