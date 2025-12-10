<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Course extends Controller
{
    /**
     * Display student's enrolled courses
     */
    public function index()
    {
        $session = session();
        
        // Check if user is logged in
        if (!$session->get('isLoggedIn') || !$session->has('userID')) {
            return redirect()->to('/login');
        }
        
        $db = \Config\Database::connect();
        
        try {
            $userId = $session->get('userID');
            
            // Get enrolled courses with details
            $enrolledQuery = $db->query("
                SELECT c.id, c.course_name, c.subject_code, c.description, 
                       c.instructor_name, c.year_level, c.date_started, c.date_ended,
                       c.created_at, c.updated_at, 
                       e.enrollment_date, e.status, e.user_id, e.course_id
                FROM courses c
                INNER JOIN enrollments e ON c.id = e.course_id
                WHERE e.user_id = ?
                ORDER BY e.enrollment_date DESC
            ", [$userId]);
            
            $data = [
                'enrolled_courses' => $enrolledQuery->getResultArray(),
                'courses' => [],
                'user' => ['name' => $session->get('name') ?? 'Student']
            ];
            
        } catch (\Exception $e) {
            log_message('error', 'Error fetching courses: ' . $e->getMessage());
            $data = [
                'enrolled_courses' => [],
                'courses' => [],
                'user' => ['name' => $session->get('name') ?? 'Student']
            ];
        }

        return view('student/my_courses', $data);
    }

    /**
     * Enroll student in a course
     */
    public function enroll()
    {
        $session = session();

        // Check authentication
        if (!$session->get('isLoggedIn') || !$session->has('userID')) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'You must be logged in to enroll.'
            ]);
        }

        $db = \Config\Database::connect();
        $course_id = $this->request->getPost('course_id');
        $user_id = $session->get('userID');

        try {
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

            // Enroll student
            $db->table('enrollments')->insert([
                'user_id' => $user_id,
                'course_id' => $course_id,
                'enrollment_date' => date('Y-m-d H:i:s'),
                'status' => 'active'
            ]);

            // Get course info
            $course = $db->table('courses')->where('id', $course_id)->get()->getRow();

            // Create notification for student
            $db->table('notifications')->insert([
                'user_id' => $user_id,
                'message' => "You have successfully enrolled in: " . $course->course_name,
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            // Notify teachers
            $teachers = $db->table('users')->where('role', 'teacher')->get()->getResultArray();
            
            foreach ($teachers as $teacher) {
                $db->table('notifications')->insert([
                    'user_id' => $teacher['id'],
                    'message' => $session->get('name') . " enrolled in: " . $course->course_name,
                    'is_read' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }

            // Notify admins
            $admins = $db->table('users')->where('role', 'admin')->get()->getResultArray();
            
            foreach ($admins as $admin) {
                $db->table('notifications')->insert([
                    'user_id' => $admin['id'],
                    'message' => $session->get('name') . " has enrolled in: " . $course->course_name,
                    'is_read' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Enrollment successful!'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Enrollment error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Database error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Display courses for dashboard
     */
    public function showCoursesForDashboard()
    {
        $db = \Config\Database::connect();
        
        try {
            $query = $db->query("
                SELECT id, course_name, subject_code, description, 
                       instructor_name, year_level, status,
                       created_at, updated_at 
                FROM courses 
                WHERE status = 'active'
                ORDER BY course_name
            ");
            $data['courses'] = $query->getResultArray();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching dashboard courses: ' . $e->getMessage());
            $data['courses'] = [];
        }

        return view('auth/dashboard', $data);
    }

    /**
     * Manage courses (view all courses)
     */
    public function manage()
    {
        $session = session();

        // Check authentication
        if (!$session->get('isLoggedIn') || !$session->has('userID')) {
            return redirect()->to('/login');
        }

        $db = \Config\Database::connect();
        
        try {
            $query = $db->table('courses')
                        ->orderBy('course_name', 'ASC')
                        ->get();
            $data['courses'] = $query->getResultArray();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching courses for management: ' . $e->getMessage());
            $data['courses'] = [];
        }

        return view('courses/manage', $data);
    }

    /**
     * Search courses by keyword (AJAX and normal request)
     */
    public function search()
    {
        $db = \Config\Database::connect();
        $keyword = $this->request->getVar('keyword');

        $builder = $db->table('courses');

        if (!empty($keyword)) {
            $builder->like('course_name', $keyword);
            $builder->orLike('subject_code', $keyword);
            $builder->orLike('description', $keyword);
            $builder->orLike('instructor_name', $keyword);
        }

        $query = $builder->get();
        $results = $query->getResultArray();

        // Return JSON for AJAX requests
        if ($this->request->isAJAX()) {
            return $this->response->setJSON($results);
        }

        // Return view for normal requests
        return view('courses/search_results', ['courses' => $results]);
    }
}
