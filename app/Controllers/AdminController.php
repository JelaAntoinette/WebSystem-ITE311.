<?php

namespace App\Controllers;

use CodeIgniter\Database\ConnectionInterface;

/**
 * Admin Controller
 * Handles all admin-related operations including user and course management
 */
class AdminController extends BaseController
{
    protected $db;
    protected $session;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }

    /**
     * Admin dashboard with statistics and overview
     */
    public function dashboard()
    {
        $this->checkAdminAccess();

        // Get all users for admin view
        $builder = $this->db->table('users');
        $allUsers = $builder->get()->getResultArray();
        
        // Get counts for dashboard statistics  
        $count_admin = $this->db->table('users')->where('role', 'admin')->countAllResults();
        $count_teacher = $this->db->table('users')->where('role', 'teacher')->countAllResults();
        $count_student = $this->db->table('users')->where('role', 'student')->countAllResults();

        // Get all uploaded materials with related course
        try {
            $materials = $this->db->query("
                SELECT m.*, c.course_name 
                FROM materials m
                LEFT JOIN courses c ON m.course_id = c.id
                ORDER BY m.created_at DESC
            ")->getResultArray();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching materials: ' . $e->getMessage());
            $materials = [];
        }

        // Prepare user data
        $data = [
            'user' => [
                'userID' => $this->session->get('userID'),
                'name'   => $this->session->get('name'),
                'email'  => $this->session->get('email'),
                'role'   => $this->session->get('role')
            ],
            'title' => 'Admin Dashboard',
            'admin_count' => $count_admin,
            'teacher_count' => $count_teacher,
            'student_count' => $count_student,
            'allUsers' => $allUsers,
            'materials' => $materials
        ];

        return view('auth/dashboard', $data);
    }

    /**
     * Logout admin user
     */
    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/login');
    }

    /**
     * Check if current user has admin access
     */
    private function checkAdminAccess()
    {
        if ($this->session->get('role') !== 'admin') {
            redirect()->to('/login')->send();
            exit;
        }
    }

    // ==================== USER MANAGEMENT METHODS ====================

    /**
     * Display all users for management
     */
    public function index()
    {
        $this->checkAdminAccess();

        try {
            // Get all users
            $builder = $this->db->table('users');
            $users = $builder->get()->getResultArray();

            if ($this->db->error()['code'] !== 0) {
                throw new \Exception("Failed to fetch users: " . $this->db->error()['message']);
            }

            // Count users by role
            $count_admin = $this->db->table('users')->where('role', 'admin')->countAllResults();
            $count_teacher = $this->db->table('users')->where('role', 'teacher')->countAllResults();
            $count_student = $this->db->table('users')->where('role', 'student')->countAllResults();
            $count_total = $count_admin + $count_teacher + $count_student;

            $data = [
                'title' => 'Manage Users',
                'users' => $users,
                'count_admin' => $count_admin,
                'count_teacher' => $count_teacher,
                'count_student' => $count_student,
                'count_total' => $count_total
            ];

        } catch (\Exception $e) {
            log_message('error', 'Error fetching users: ' . $e->getMessage());
            $data = [
                'title' => 'Manage Users',
                'users' => [],
                'count_admin' => 0,
                'count_teacher' => 0,
                'count_student' => 0,
                'count_total' => 0,
                'error' => $e->getMessage()
            ];
        }

        return view('admin/manage_users', $data);
    }

    /**
     * Store a new user
     */
    public function store()
    {
        $this->checkAdminAccess();
        
        $rules = [
            'name' => 'required|min_length[3]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'role' => 'required|in_list[admin,teacher,student]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/admin/users')->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => $this->request->getPost('role'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        try {
            $this->db->table('users')->insert($data);
            return redirect()->to('/admin/users')->with('message', 'User added successfully');
        } catch (\Exception $e) {
            log_message('error', 'Error adding user: ' . $e->getMessage());
            return redirect()->to('/admin/users')->with('error', 'Failed to add user: ' . $e->getMessage());
        }
    }

    /**
     * Update an existing user
     */
    public function update($id)
    {
        $this->checkAdminAccess();
        
        $rules = [
            'name' => 'required|min_length[3]',
            'email' => 'required|valid_email',
            'role' => 'required|in_list[admin,teacher,student]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/admin/users')->with('errors', $this->validator->getErrors());
        }

        // Get the current user being edited
        $currentUser = $this->db->table('users')->where('id', $id)->get()->getRowArray();
        if (!$currentUser) {
            return redirect()->to('/admin/users')->with('error', 'User not found');
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'role' => $this->request->getPost('role'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Protection: Prevent role change for admin users
        if ($currentUser['role'] === 'admin') {
            $data['role'] = $currentUser['role'];
        }

        // Update password if provided
        if ($password = $this->request->getPost('password')) {
            if (strlen($password) >= 6) {
                $data['password'] = password_hash($password, PASSWORD_DEFAULT);
            } else {
                return redirect()->to('/admin/users')->with('error', 'Password must be at least 6 characters long');
            }
        }

        try {
            $this->db->table('users')->where('id', $id)->update($data);
            return redirect()->to('/admin/users')->with('message', 'User updated successfully');
        } catch (\Exception $e) {
            log_message('error', 'Error updating user: ' . $e->getMessage());
            return redirect()->to('/admin/users')->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }

    /**
     * Delete a user
     */
    public function delete($id)
    {
        $this->checkAdminAccess();
        
        try {
            $user = $this->db->table('users')->where('id', $id)->get()->getRowArray();
            if (!$user) {
                throw new \Exception('User not found');
            }

            // Prevent deleting the last admin
            if ($user['role'] === 'admin') {
                $adminCount = $this->db->table('users')->where('role', 'admin')->countAllResults();
                if ($adminCount <= 1) {
                    throw new \Exception('Cannot delete the last admin user');
                }
            }
            
            $this->db->table('users')->where('id', $id)->delete();
            return redirect()->to('/admin/users')->with('message', 'User deleted successfully');
        } catch (\Exception $e) {
            log_message('error', 'Error deleting user: ' . $e->getMessage());
            return redirect()->to('/admin/users')->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }

    // ==================== COURSE MANAGEMENT METHODS ====================

    /**
     * Display all courses for admin management
     */
    public function courses()
    {
        $this->checkAdminAccess();

        try {
            $builder = $this->db->table('courses');
            $courses = $builder->orderBy('created_at', 'DESC')->get()->getResultArray();

            // Get all teachers for the dropdown
            $teachers = $this->db->table('users')
                                 ->where('role', 'teacher')
                                 ->orderBy('name', 'ASC')
                                 ->get()
                                 ->getResultArray();

            $data = [
                'title' => 'Manage Courses',
                'courses' => $courses,
                'teachers' => $teachers
            ];

            return view('admin/manage_courses', $data);
        } catch (\Exception $e) {
            return view('admin/manage_courses', [
                'title' => 'Manage Courses',
                'courses' => [],
                'teachers' => [],
                'error' => 'Failed to load courses: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Store a new course
     */
    public function storeCourse()
    {
        $this->checkAdminAccess();
        
        $rules = [
            'course_name' => 'required|min_length[3]',
            'subject_code' => 'permit_empty|max_length[20]',
            'description' => 'permit_empty',
            'instructor_name' => 'permit_empty|max_length[100]',
            'year_level' => 'permit_empty|max_length[50]',
            'status' => 'required|in_list[active,inactive,completed]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'course_name' => $this->request->getPost('course_name'),
            'subject_code' => $this->request->getPost('subject_code'),
            'description' => $this->request->getPost('description'),
            'instructor_name' => $this->request->getPost('instructor_name'),
            'year_level' => $this->request->getPost('year_level'),
            'date_started' => $this->request->getPost('date_started') ?: null,
            'date_ended' => $this->request->getPost('date_ended') ?: null,
            'year_started' => $this->request->getPost('year_started') ?: null,
            'year_ended' => $this->request->getPost('year_ended') ?: null,
            'status' => $this->request->getPost('status'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        try {
            $this->db->table('courses')->insert($data);
            return redirect()->to('/admin/courses')->with('message', 'Course added successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to add course: ' . $e->getMessage());
        }
    }

    /**
     * Update an existing course
     */
    public function updateCourse($id)
    {
        $this->checkAdminAccess();
        
        $rules = [
            'course_name' => 'required|min_length[3]',
            'subject_code' => 'permit_empty|max_length[20]',
            'description' => 'permit_empty',
            'instructor_name' => 'permit_empty|max_length[100]',
            'year_level' => 'permit_empty|max_length[50]',
            'status' => 'required|in_list[active,inactive,completed]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'course_name' => $this->request->getPost('course_name'),
            'subject_code' => $this->request->getPost('subject_code'),
            'description' => $this->request->getPost('description'),
            'instructor_name' => $this->request->getPost('instructor_name'),
            'year_level' => $this->request->getPost('year_level'),
            'date_started' => $this->request->getPost('date_started') ?: null,
            'date_ended' => $this->request->getPost('date_ended') ?: null,
            'year_started' => $this->request->getPost('year_started') ?: null,
            'year_ended' => $this->request->getPost('year_ended') ?: null,
            'status' => $this->request->getPost('status'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        try {
            $this->db->table('courses')->where('id', $id)->update($data);
            return redirect()->to('/admin/courses')->with('message', 'Course updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to update course: ' . $e->getMessage());
        }
    }

    /**
     * Delete a course
     */
    public function deleteCourse($id)
    {
        $this->checkAdminAccess();
        
        try {
            // Check if course exists
            $course = $this->db->table('courses')->where('id', $id)->get()->getRowArray();
            if (!$course) {
                throw new \Exception('Course not found');
            }

            // Check if course has enrollments
            $enrollmentCount = $this->db->table('enrollments')->where('course_id', $id)->countAllResults();
            if ($enrollmentCount > 0) {
                return redirect()->to('/admin/courses')->with('warning', 'Cannot delete course with active enrollments. Consider marking it as inactive instead.');
            }

            $this->db->table('courses')->where('id', $id)->delete();
            return redirect()->to('/admin/courses')->with('message', 'Course deleted successfully');
        } catch (\Exception $e) {
            return redirect()->to('/admin/courses')->with('error', 'Failed to delete course: ' . $e->getMessage());
        }
    }
}