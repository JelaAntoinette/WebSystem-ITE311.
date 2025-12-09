<?php

namespace App\Controllers;

class Auth extends BaseController
{
    protected $session;
    protected $db;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
    }

    // Register (GET + POST)
    public function register()
    {
        if ($this->request->getMethod() === 'POST') {
            $name     = $this->request->getPost('name');
            $email    = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            $confirm  = $this->request->getPost('password_confirm');

            if ($password !== $confirm) {
                $this->session->setFlashdata('error', 'Passwords do not match.');
                return redirect()->back()->withInput();
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $builder = $this->db->table('users');
            $userData = [
                'name'       => $name,
                'email'      => $email,
                'password'   => $hashedPassword,
                'role'       => 'student', // default role
                'created_at' => date('Y-m-d H:i:s')
            ];

            if ($builder->insert($userData)) {
                $this->session->setFlashdata('success', 'Registration successful! Please login.');
                return redirect()->to(base_url('login'));
            } else {
                $this->session->setFlashdata('error', 'Registration failed.');
            }
        }

        return view('auth/register');
    }

    // Login (GET + POST)
    public function login()
    {
        if ($this->request->getMethod() === 'POST') {
            $login    = $this->request->getPost('login');
            $password = $this->request->getPost('password');

            // Admin shortcut
            if ($login === 'admin' && $password === 'admin123') {
                $this->session->set([
                    'userID'     => 1,
                    'name'       => 'System Administrator',
                    'email'      => 'admin@lms.com',
                    'role'       => 'admin',
                    'isLoggedIn' => true
                ]);
                // CHANGED: Redirect to generic dashboard
                return redirect()->to(base_url('dashboard'));
            }

            // Check DB
            $builder = $this->db->table('users');
            $user = $builder->where('email', $login)
                            ->orWhere('name', $login)
                            ->get()
                            ->getRowArray();

            if ($user && password_verify($password, $user['password'])) {
                $this->session->set([
                    'userID'     => $user['id'],
                    'name'       => $user['name'],
                    'email'      => $user['email'],
                    'role'       => $user['role'],
                    'isLoggedIn' => true
                ]);
                
                // CHANGED: Everyone redirects to the same generic dashboard
                return redirect()->to(base_url('dashboard'));
                
                /* OLD CODE - Role-based redirects (now commented out)
                $role = $user['role'];
                if ($role === 'admin') {
                    return redirect()->to(base_url('admin/dashboard'));
                } elseif ($role === 'teacher') {
                    return redirect()->to(base_url('teacher/dashboard'));
                } else { // student
                    return redirect()->to(base_url('student/dashboard'));
                }
                */
            } else {
                $this->session->setFlashdata('error', 'Invalid login credentials.');
            }
        }

        return view('auth/login');
    }

    // Logout
    public function logout()
    {
        $this->session->destroy();
        return redirect()->to(base_url('login'));
    }

    // Fallback dashboard redirect (kept for compatibility)
    public function dashboardRedirect()
    {
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }

        // CHANGED: Everyone goes to generic dashboard now
        return redirect()->to(base_url('dashboard'));
        
        /* OLD CODE - Role-based redirects (now commented out)
        $role = $this->session->get('role');
        
        if ($role === 'admin') {
            return redirect()->to(base_url('admin/dashboard'));
        } elseif ($role === 'teacher') {
            return redirect()->to(base_url('teacher/dashboard'));
        } else {
            return redirect()->to(base_url('student/dashboard'));
        }
        */
    }
}