<?php namespace App\Controllers;

use CodeIgniter\Controller;

class Student extends BaseController
{
    public function dashboard()
    {
        $session = session();

        // Ensure only students can access
        if ($session->get('role') !== 'student') {
            return redirect()->to('/login');
        }

        // Fetch courses directly from DB
        $db = \Config\Database::connect();
        $query = $db->query("SELECT * FROM courses");
        $data['courses'] = $query->getResultArray();

        return view('student/dashboard', $data);
    }

    public function enroll()
    {
        $session = session();

        // Check if logged in
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

        // Insert enrollment
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
