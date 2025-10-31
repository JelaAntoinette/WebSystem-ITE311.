<?php
namespace App\Controllers;

use CodeIgniter\Controller;

class Course extends Controller
{
    public function index()
    {
        $session = session();
        
        if (!$session->get('isLoggedIn') || !$session->has('userID')) {
            return redirect()->to('/login');
        }
        
        $db = \Config\Database::connect();
        
        try {
            $userId = $session->get('userID');
            
            $allEnrollmentsQuery = $db->query("SELECT * FROM enrollments WHERE user_id = ?", [$userId]);
            $allEnrollments = $allEnrollmentsQuery->getResultArray();
            
            $userQuery = $db->query("SELECT * FROM users WHERE email = ?", [$session->get('email')]);
            $userData = $userQuery->getResultArray();
            
            $enrolledQuery = $db->query("
                SELECT c.id, c.name AS course_name, c.description, c.created_at, c.updated_at, 
                       e.enrollment_date, e.status, e.user_id, e.course_id
                FROM courses c
                INNER JOIN enrollments e ON c.id = e.course_id
                WHERE e.user_id = ?
                ORDER BY e.enrollment_date DESC
            ", [$userId]);
            
            $data['enrolled_courses'] = $enrolledQuery->getResultArray();
            $data['courses'] = [];
            $data['user'] = ['name' => $session->get('name') ?? 'Student'];
            
        } catch (\Exception $e) {
            $data['enrolled_courses'] = [];
            $data['courses'] = [];
            $data['user'] = ['name' => $session->get('name') ?? 'Student'];
        }

        return view('student/my_courses', $data);
    }

    public function enroll()
    {
        $session = session();

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

            // ✅ Enroll student
            $db->table('enrollments')->insert([
                'user_id' => $user_id,
                'course_id' => $course_id,
                'enrollment_date' => date('Y-m-d H:i:s'),
                'status' => 'active'
            ]);

            // ✅ Get course info
            $course = $db->table('courses')->where('id', $course_id)->get()->getRow();

            // ✅ Student notification
            $db->table('notifications')->insert([
                'user_id' => $user_id,
                'message' => "You have successfully enrolled in: " . $course->course_name,
                'is_read'    => 0,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            // ✅ Get ALL teachers and send notification to each
            $teachers = $db->table('users')->where('role', 'teacher')->get()->getResultArray();
            
            foreach ($teachers as $teacher) {
                $db->table('notifications')->insert([
                    'user_id' => $teacher['id'],
                    'message' => $session->get('name') . " enrolled in: " . $course->course_name,
                    'is_read' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }

            // ✅ Admin notification (get all admins)
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
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Database error: ' . $e->getMessage()
            ]);
        }
    }

    public function showCoursesForDashboard()
    {
        $db = \Config\Database::connect();
        
        try {
            $query = $db->query("SELECT id, name AS course_name, description, created_at, updated_at FROM courses ORDER BY name");
            $data['courses'] = $query->getResultArray();
        } catch (\Exception $e) {
            $data['courses'] = [];
        }

        return view('auth/dashboard', $data);
    }

    public function manage()
    {
        $session = session();

        if (!$session->get('isLoggedIn') || !$session->has('userID')) {
            return redirect()->to('/login');
        }

        $db = \Config\Database::connect();
        try {
            $query = $db->table('courses')->get();
            $data['courses'] = $query->getResultArray();
        } catch (\Exception $e) {
            $data['courses'] = [];
        }

        return view('courses/manage', $data);
    }
}