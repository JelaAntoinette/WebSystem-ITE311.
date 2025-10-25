<?php
namespace App\Controllers;

use CodeIgniter\Controller;

class Course extends Controller
{
    public function index()
    {
        $session = session();
        
        // Check if user is logged in
        if (!$session->get('isLoggedIn') || !$session->has('userID')) {
            return redirect()->to('/login');
        }
        
        // Connect to DB directly
        $db = \Config\Database::connect();
        
        try {
            $userId = $session->get('userID');
            
            // ✅ Add debugging - let's see what we're working with
            log_message('debug', 'Current User ID from session: ' . $userId);
            log_message('debug', 'Session data: ' . json_encode($session->get()));
            
            // ✅ First, let's check what's in the enrollments table
            $allEnrollmentsQuery = $db->query("SELECT * FROM enrollments WHERE user_id = ?", [$userId]);
            $allEnrollments = $allEnrollmentsQuery->getResultArray();
            log_message('debug', 'All enrollments for user: ' . json_encode($allEnrollments));
            
            // ✅ Check the users table to see the actual user ID
            $userQuery = $db->query("SELECT * FROM users WHERE email = ?", [$session->get('email')]);
            $userData = $userQuery->getResultArray();
            log_message('debug', 'User data from database: ' . json_encode($userData));
            
            // ✅ Get enrolled courses with more detailed debugging
            $enrolledQuery = $db->query("
                SELECT c.id, c.name AS course_name, c.description, c.created_at, c.updated_at, 
                       e.enrollment_date, e.status, e.user_id, e.course_id
                FROM courses c
                INNER JOIN enrollments e ON c.id = e.course_id
                WHERE e.user_id = ?
                ORDER BY e.enrollment_date DESC
            ", [$userId]);
            
            $data['enrolled_courses'] = $enrolledQuery->getResultArray();
            log_message('debug', 'Enrolled courses found: ' . count($data['enrolled_courses']));
            log_message('debug', 'Enrolled courses data: ' . json_encode($data['enrolled_courses']));
            
            $data['courses'] = []; // Empty - no available courses on this page
            
            // Get user data from session
            $data['user'] = [
                'name' => $session->get('name') ?? 'Student'
            ];
            
            // ✅ Add debug info to the view
            $data['debug_info'] = [
                'user_id' => $userId,
                'total_enrollments' => count($allEnrollments),
                'enrolled_courses_count' => count($data['enrolled_courses'])
            ];
            
        } catch (\Exception $e) {
            log_message('error', 'Database error in Course controller: ' . $e->getMessage());
            $data['enrolled_courses'] = [];
            $data['courses'] = [];
            $data['user'] = ['name' => $session->get('name') ?? 'Student'];
            $data['debug_info'] = ['error' => $e->getMessage()];
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
                'enrollment_date' => date('Y-m-d H:i:s'),
                'status' => 'active'
            ]);

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
            log_message('error', 'Database error in Course showCoursesForDashboard: ' . $e->getMessage());
        }

        return view('auth/dashboard', $data);
    }

    public function manage()
{
    $session = session();

    // Check if user is logged in
    if (!$session->get('isLoggedIn') || !$session->has('userID')) {
        return redirect()->to('/login');
    }

    // Optional: force redirect if not coming from dashboard
    $referrer = $this->request->getHeader('Referer');
    if (!$referrer || strpos($referrer->getValue(), '/dashboard') === false) {
        return redirect()->to('/dashboard');
    }

    $db = \Config\Database::connect();
    try {
        $query = $db->table('courses')->get();
        $data['courses'] = $query->getResultArray();
    } catch (\Exception $e) {
        $data['courses'] = [];
        log_message('error', 'Course manage error: ' . $e->getMessage());
    }

    return view('courses/manage', $data);
}
}
