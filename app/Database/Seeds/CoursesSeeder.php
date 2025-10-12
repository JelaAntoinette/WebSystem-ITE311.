<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CoursesSeeder extends Seeder
{
    public function run()
    {
        // First, disable foreign key checks and get the database connection
        $db = db_connect();
        $db->query('SET FOREIGN_KEY_CHECKS=0');
        
        // Clear the table
        $this->db->table('courses')->truncate();

        $data = [
            [
                'name' => 'Introduction to Web Development',
                'description' => 'Learn the basics of web development including HTML, CSS, and JavaScript',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Database Management',
                'description' => 'Understanding database design, SQL, and database administration',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Programming Fundamentals',
                'description' => 'Basic concepts of programming using PHP',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Network Security',
                'description' => 'Introduction to network security concepts and practices',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Mobile App Development',
                'description' => 'Building mobile applications for Android and iOS',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        try {
            // Insert the data
            $this->db->table('courses')->insertBatch($data);
            
            // Re-enable foreign key checks
            $db = db_connect();
            $db->query('SET FOREIGN_KEY_CHECKS=1');
            
            echo "Courses seeded successfully!\n";
        } catch (\Exception $e) {
            echo "Error seeding courses: " . $e->getMessage() . "\n";
        }
    }
}
