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
                'teacher_id' => 1
            ],
            [
                'course_name' => 'Database Systems',
                'description' => 'Understand SQL and relational database management',
                'teacher_id' => 1
            ],
            [
                'course_name' => 'Programming Fundamentals',
                'description' => 'Learn the basics of programming and algorithms',
                'teacher_id' => 1
            ],
            [
                'course_name' => 'Information Security',
                'description' => 'Explore cybersecurity concepts and data protection',
                'teacher_id' => 1
            ],
        ];

        $this->db->table('courses')->insertBatch($data);
    }
}
