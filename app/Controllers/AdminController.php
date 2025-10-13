<?php namespace App\Controllers;

use CodeIgniter\Database\ConnectionInterface;

class AdminController extends BaseController
{
    protected $db;
    protected $session;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }

    public function dashboard()
    {
        $this->checkAdminAccess();

        try {
            // Get counts for dashboard statistics
            $count_admin = $this->db->table('users')->where('role', 'admin')->countAllResults();
            $count_teacher = $this->db->table('users')->where('role', 'teacher')->countAllResults();
            $count_student = $this->db->table('users')->where('role', 'student')->countAllResults();
            
            // Get all users for admin view
            $builder = $this->db->table('users');
            $allUsers = $builder->get()->getResultArray();

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
                'allUsers' => $allUsers
            ];

            return view('auth/dashboard', $data);
        } catch (\Exception $e) {
            $data = [
                'user' => [
                    'userID' => $this->session->get('userID'),
                    'name'   => $this->session->get('name'),
                    'email'  => $this->session->get('email'),
                    'role'   => $this->session->get('role')
                ],
                'title' => 'Admin Dashboard',
                'error' => $e->getMessage(),
                'allUsers' => []
            ];
            return view('auth/dashboard', $data);
        }
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/login');
    }

    private function checkAdminAccess()
    {
        if ($this->session->get('role') !== 'admin') {
            return redirect()->to('/login');
        }
    }

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

            // Count users by role with error checking
            $count_admin = $this->db->table('users')->where('role', 'admin')->countAllResults();
            if ($this->db->error()['code'] !== 0) {
                throw new \Exception("Error counting admin users: " . $this->db->error()['message']);
            }
            
            $count_teacher = $this->db->table('users')->where('role', 'teacher')->countAllResults();
            if ($this->db->error()['code'] !== 0) {
                throw new \Exception("Error counting teacher users: " . $this->db->error()['message']);
            }
            
            $count_student = $this->db->table('users')->where('role', 'student')->countAllResults();
            if ($this->db->error()['code'] !== 0) {
                throw new \Exception("Error counting student users: " . $this->db->error()['message']);
            }
            
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
            return redirect()->to('/admin/users')->with('error', 'Failed to add user: ' . $e->getMessage());
        }
    }

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

        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'role' => $this->request->getPost('role'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Only update password if a new one is provided
        if ($password = $this->request->getPost('password')) {
            if (strlen($password) >= 6) {
                $data['password'] = password_hash($password, PASSWORD_DEFAULT);
            } else {
                return redirect()->to('/admin/users')->with('error', 'Password must be at least 6 characters long');
            }
        }

        try {
            // Check if user exists
            $user = $this->db->table('users')->where('id', $id)->get()->getRowArray();
            if (!$user) {
                throw new \Exception('User not found');
            }

            $this->db->table('users')->where('id', $id)->update($data);
            return redirect()->to('/admin/users')->with('message', 'User updated successfully');
        } catch (\Exception $e) {
            return redirect()->to('/admin/users')->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        $this->checkAdminAccess();
        
        try {
            // Check if user exists and get their role
            $user = $this->db->table('users')->where('id', $id)->get()->getRowArray();
            if (!$user) {
                throw new \Exception('User not found');
            }

            // Don't allow deleting the last admin
            if ($user['role'] === 'admin') {
                $adminCount = $this->db->table('users')->where('role', 'admin')->countAllResults();
                if ($adminCount <= 1) {
                    throw new \Exception('Cannot delete the last admin user');
                }
            }
            
            $this->db->table('users')->where('id', $id)->delete();
            return redirect()->to('/admin/users')->with('message', 'User deleted successfully');
        } catch (\Exception $e) {
            return redirect()->to('/admin/users')->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }
}