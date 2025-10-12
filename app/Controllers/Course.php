<?php

namespace App\Controllers;

use App\Models\EnrollmentModel;
use CodeIgniter\Controller;

class Course extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $data['courses'] = $db->table('courses')->get()->getResult();

        return view('student_dashboard', $data);
    }

    public function view($id = null)
    {
        if ($id === null) {
            return redirect()->to('/student/dashboard');
        }

        $db = \Config\Database::connect();
        $course = $db->table('courses')->where('id', $id)->get()->getRow();
        
        if (!$course) {
            return redirect()->to('/student/dashboard');
        }

        // Get enrollment status
        $session = session();
        $enrollmentModel = new EnrollmentModel();
        $isEnrolled = false;
        
        if ($session->has('user_id')) {
            $isEnrolled = $enrollmentModel->isAlreadyEnrolled($session->get('user_id'), $id);
        }

        $data = [
            'course' => $course,
            'isEnrolled' => $isEnrolled
        ];

        return view('courses/view', $data);
    }

    public function enroll()
    {
        // Start session
        $session = session();

        // Check if user is logged in
        if (!$session->has('user_id')) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'You must be logged in to enroll.'
            ]);
        }

        // Get course_id from POST request
        $course_id = $this->request->getPost('course_id');
        $user_id = $session->get('user_id');

        $enrollmentModel = new EnrollmentModel();

        // Check if already enrolled
        if ($enrollmentModel->isAlreadyEnrolled($user_id, $course_id)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'You are already enrolled in this course.'
            ]);
        }

        // Enroll user
        $data = [
            'user_id' => $user_id,
            'course_id' => $course_id,
            'enrollment_date' => date('Y-m-d H:i:s')
        ];

        $enrollmentModel->enrollUser($data);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Enrollment successful!'
        ]);
    }
}
