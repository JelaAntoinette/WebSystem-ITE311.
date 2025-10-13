<?php 

namespace App\Controllers;

class TeacherController extends BaseController
{
    public function dashboard()
    {
        $session = session();
        
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'teacher') {
            return redirect()->to('/login');
        }

        // Prepare user data
        $data['user'] = [
            'userID' => $session->get('userID'),
            'name'   => $session->get('name'),
            'email'  => $session->get('email'),
            'role'   => $session->get('role')
        ];

        // Example: fetch teacher's classes (you can customize this)
        $data['classes'] = []; // Replace with actual query if you have a classes table
        $data['title'] = 'Teacher Dashboard';
        
        // Use the unified dashboard view
        return view('auth/dashboard', $data);
    }
}