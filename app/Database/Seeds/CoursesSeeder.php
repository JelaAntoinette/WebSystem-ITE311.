<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CoursesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'course_name'      => 'Web Development',
                'subject_code'     => 'ITE311',
                'description'      => 'Learn to build and design modern websites using HTML, CSS, JavaScript, and PHP frameworks',
                'instructor_name'  => 'John Smith',
                'year_level'       => '3rd Year',
                'date_started'     => '2024-08-15',
                'date_ended'       => '2024-12-15',
                'year_started'     => 2024,
                'year_ended'       => 2024,
                'status'           => 'active',
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'course_name'      => 'Database Systems',
                'subject_code'     => 'CS201',
                'description'      => 'Understand SQL, NoSQL, and relational database management concepts',
                'instructor_name'  => 'Jane Doe',
                'year_level'       => '2nd Year',
                'date_started'     => '2024-08-20',
                'date_ended'       => '2024-12-20',
                'year_started'     => 2024,
                'year_ended'       => 2024,
                'status'           => 'active',
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'course_name'      => 'Programming Fundamentals',
                'subject_code'     => 'CS101',
                'description'      => 'Learn the basics of programming, algorithms, and problem-solving techniques',
                'instructor_name'  => 'John Smith',
                'year_level'       => '1st Year',
                'date_started'     => '2024-08-10',
                'date_ended'       => '2024-12-10',
                'year_started'     => 2024,
                'year_ended'       => 2024,
                'status'           => 'active',
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'course_name'      => 'Information Security',
                'subject_code'     => 'IT401',
                'description'      => 'Explore cybersecurity concepts, encryption, and data protection strategies',
                'instructor_name'  => 'Jane Doe',
                'year_level'       => '4th Year',
                'date_started'     => '2024-09-01',
                'date_ended'       => '2025-01-15',
                'year_started'     => 2024,
                'year_ended'       => 2025,
                'status'           => 'active',
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'course_name'      => 'Mobile Application Development',
                'subject_code'     => 'ITE412',
                'description'      => 'Build native and cross-platform mobile applications for iOS and Android',
                'instructor_name'  => 'John Smith',
                'year_level'       => '4th Year',
                'date_started'     => '2025-01-10',
                'date_ended'       => '2025-05-30',
                'year_started'     => 2025,
                'year_ended'       => 2025,
                'status'           => 'active',
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('courses')->insertBatch($data);
    }
}
