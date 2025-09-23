<?php namespace App\Controllers;

class AdminController extends BaseController
{
    public function dashboard()
    {
        $session = session();
        if ($session->get('role') !== 'admin') {
            return redirect()->to('/login'); // Unauthorized access
        }

        // Prepare any data needed
        $data['users'] = []; // Example data
        
        return view('admin/dashboard', $data);
    }
}
