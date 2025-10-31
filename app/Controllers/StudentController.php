<?php 

namespace App\Controllers;

use CodeIgniter\Controller;

class StudentController extends BaseController
{
    public function dashboard()
    {
        $session = session();

        // Ensure only students can access
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'student') {
            return redirect()->to('/login');
        }

        // Fetch all courses
        $db = \Config\Database::connect();
        
        try {
            $userId = $session->get('userID');
            
            // ✅ Check if user has any notifications, if not create a welcome notification (student only)
            $notifCount = $db->table('notifications')->where('user_id', $userId)->countAllResults();
            if ($notifCount == 0 && $session->get('role') === 'student') {
                $db->table('notifications')->insert([
                    'user_id' => $userId,
                    'message' => 'Welcome to the Learning Management System! Start by enrolling in courses below.',
                    'is_read' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
            
            // Get all courses
            $query = $db->query("SELECT * FROM courses ORDER BY course_name");
            $data['courses'] = $query->getResultArray();

            // Get user's enrolled courses
            $enrolledQuery = $db->query("
                SELECT c.* 
                FROM courses c
                INNER JOIN enrollments e ON c.id = e.course_id
                WHERE e.user_id = ?
                ORDER BY c.course_name
            ", [$userId]);
            $data['enrolled'] = $enrolledQuery->getResultArray();

            // ✅ Fetch uploaded materials for user's enrolled courses
            $materialsQuery = $db->query("
                SELECT m.*, c.course_name
                FROM materials m
                INNER JOIN courses c ON m.course_id = c.id
                WHERE m.course_id IN (
                    SELECT course_id FROM enrollments WHERE user_id = ?
                )
                ORDER BY m.created_at DESC
            ", [$userId]);
            $data['materials'] = $materialsQuery->getResultArray();

        } catch (\Exception $e) {
            // Fallbacks in case of database errors
            $data['courses'] = [];
            $data['enrolled'] = [];
            $data['materials'] = [];
            log_message('error', 'Database error in StudentController: ' . $e->getMessage());
        }

        // User info
        $data['user'] = [
            'userID' => $session->get('userID'),
            'name'   => $session->get('name'),
            'email'  => $session->get('email'),
            'role'   => $session->get('role')
        ];

        $data['title'] = 'Student Dashboard';

        return view('auth/dashboard', $data);
    }

    // ✅ My Courses Page (with materials)
    public function myCourses()
    {
        $session = session();

        // Ensure only students can access
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'student') {
            return redirect()->to('/login');
        }

        $db = \Config\Database::connect();
        
        try {
            // Fetch user's enrolled courses with enrollment details
            $userId = $session->get('userID');
            $enrolledQuery = $db->query("
                SELECT c.*, e.enrollment_date, e.status
                FROM courses c
                INNER JOIN enrollments e ON c.id = e.course_id
                WHERE e.user_id = ?
                ORDER BY e.enrollment_date DESC
            ", [$userId]);
            $data['enrolled_courses'] = $enrolledQuery->getResultArray();

            // Fetch uploaded materials for enrolled courses
            $materialsQuery = $db->query("
                SELECT m.*, c.course_name
                FROM materials m
                INNER JOIN courses c ON m.course_id = c.id
                WHERE m.course_id IN (
                    SELECT course_id FROM enrollments WHERE user_id = ?
                )
                ORDER BY m.created_at DESC
            ", [$userId]);

            $data['materials'] = $materialsQuery->getResultArray();

        } catch (\Exception $e) {
            $data['enrolled_courses'] = [];
            $data['materials'] = [];
            $data['error'] = 'Unable to load your courses or materials. Please try again later.';
            log_message('error', 'Database error in StudentController::myCourses: ' . $e->getMessage());
        }

        // User data
        $data['user'] = [
            'userID' => $session->get('userID'),
            'name'   => $session->get('name'),
            'email'  => $session->get('email'),
            'role'   => $session->get('role')
        ];

        $data['title'] = 'My Courses - Student Dashboard';

        return view('student/my_courses', $data);
    }

    public function enroll()
    {
        $session = session();

        // Check if logged in
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

            // Insert new enrollment
            $db->table('enrollments')->insert([
                'user_id' => $user_id,
                'course_id' => $course_id,
                'enrollment_date' => date('Y-m-d H:i:s'),
                'status' => 'active'
            ]);

            // ✅ Get course details and student name
            $course = $db->table('courses')->where('id', $course_id)->get()->getRowArray();
            $student = $db->table('users')->where('id', $user_id)->get()->getRowArray();
            
            if ($course && $student) {
                $studentName = $student['name'];
                $courseName = $course['course_name'];
                
                // ✅ Create notification for student
                $db->table('notifications')->insert([
                    'user_id' => $user_id,
                    'message' => 'You have successfully enrolled in ' . $courseName,
                    'is_read' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                
                // ✅ Create notification for all teachers (role-based)
                $teachers = $db->table('users')->where('role', 'teacher')->get()->getResultArray();
                foreach ($teachers as $teacher) {
                    $db->table('notifications')->insert([
                        'user_id' => $teacher['id'],
                        'message' => $studentName . ' has enrolled in ' . $courseName,
                        'is_read' => 0,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }
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
}
