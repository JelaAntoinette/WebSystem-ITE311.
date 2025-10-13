<?php
namespace App\Controllers;

use CodeIgniter\Controller;

class Course extends Controller
{
    public function index()
    {
        // Connect to DB directly
        $db = \Config\Database::connect();
        $query = $db->query("SELECT * FROM courses");
        $data['courses'] = $query->getResultArray();

        // Load a simple view to display them (for testing)
      return view('courses/index', $data);

    }

    public function enroll()
    {
        $session = session();

        // Check login
        if (!$session->has('user_id')) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'You must be logged in to enroll.'
            ]);
        }

        $db = \Config\Database::connect();

        // Check login
        if (!$session->has('user_id')) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'You must be logged in to enroll.'
            ]);
        }

        $db = \Config\Database::connect();
        $course_id = $this->request->getPost('course_id');
        $user_id = $session->get('user_id');

        // Check if already enrolled
        $exists = $db->table('enrollments')
                    ->where(['user_id' => $user_id, 'course_id' => $course_id])
                    ->get()
                    ->getRow();

        if ($exists) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'You are already enrolled in this course.'
            ]);
        }

        // Enroll user
        $db->table('enrollments')->insert([
            'user_id' => $user_id,
            'course_id' => $course_id,
            'enrollment_date' => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Enrollment successful!'
        ]);
    }
}
