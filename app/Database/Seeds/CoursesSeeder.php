<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CoursesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'course_name' => 'Web Development',
                'description' => 'Learn to build and design modern websites',
            ],
            [
                'course_name' => 'Database Systems',
                'description' => 'Understand SQL and relational database management',
            ],
            [
                'course_name' => 'Programming Fundamentals',
                'description' => 'Learn the basics of programming and algorithms',
            ],
            [
                'course_name' => 'Information Security',
                'description' => 'Explore cybersecurity concepts and data protection',
            ],
        ];

        $this->db->table('courses')->insertBatch($data);
    }
}
