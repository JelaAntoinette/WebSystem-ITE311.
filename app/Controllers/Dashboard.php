<?php 

namespace App\Controllers;

use CodeIgniter\Controller;

class Dashboard extends BaseController
{
    public function index()
    {
        $session = session();

        // Check if user is logged in
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // Route to appropriate dashboard based on role
        $role = $session->get('role');

        if ($role === 'student') {
            return redirect()->to('/student/dashboard');
        } elseif ($role === 'teacher') {
            return redirect()->to('/teacher/dashboard');
        } elseif ($role === 'admin') {
            return redirect()->to('/admin/dashboard');
        } else {
            // Unknown role, log out
            $session->destroy();
            return redirect()->to('/login')->with('error', 'Invalid user role');
        }
    }
}