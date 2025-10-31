<?php 

namespace App\Controllers;

use CodeIgniter\Controller;

class TeacherController extends BaseController
{
    // ✅ TEACHER DASHBOARD
    public function dashboard()
    {
        $session = session();
        
        // Check if teacher is logged in
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'teacher') {
            return redirect()->to('/login');
        }

        $db = \Config\Database::connect();
        
        $userId = $session->get('userID');
        
        // ✅ Check if teacher has any notifications, if not create a welcome notification (teacher only)
        $notifCount = $db->table('notifications')->where('user_id', $userId)->countAllResults();
        if ($notifCount == 0 && $session->get('role') === 'teacher') {
            $db->table('notifications')->insert([
                'user_id' => $userId,
                'message' => 'Welcome to the LMS Teacher Portal! You will be notified when students enroll in courses.',
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        // Get all courses for dropdown or display
        $getAllCourses = $db->query("SELECT * FROM courses ORDER BY course_name");

        // Get uploaded materials (teacher can see them)
        $materials = $db->table('materials')
                        ->orderBy('created_at', 'DESC')
                        ->get()
                        ->getResultArray();

        // Prepare user data
        $data['user'] = [
            'userID'   => $session->get('userID'),
            'name'     => $session->get('name'),
            'email'    => $session->get('email'),
            'role'     => $session->get('role'),
            'courses'  => $getAllCourses->getResultArray(),
        ];

        $data['materials'] = $materials;
        $data['title'] = 'Teacher Dashboard';
        
        return view('auth/dashboard', $data);
    }

    // ✅ UPLOAD MATERIALS
    public function uploadMaterial()
    {
        $session = session();
        $db = \Config\Database::connect();

        // Get uploaded file
        $file = $this->request->getFile('material_file');
        $courseId = $this->request->getPost('course_id');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Create uploads folder if not exists
            $uploadPath = WRITEPATH . 'uploads/materials/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Move file to writable/uploads/materials
            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);

            // Save record to materials table
            $db->table('materials')->insert([
                'course_id'  => $courseId,
                'file_name'  => $file->getClientName(),
                'file_path'  => $newName,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            $session->setFlashdata('success', 'Material uploaded successfully!');
        } else {
            $session->setFlashdata('error', 'File upload failed. Please try again.');
        }

        return redirect()->to('/teacher/dashboard');
    }

    // ✅ DOWNLOAD MATERIALS
    public function download($id)
    {
        $db = \Config\Database::connect();
        $file = $db->table('materials')->where('id', $id)->get()->getRow();

        if (!$file) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('File not found');
        }

        $filePath = WRITEPATH . 'uploads/materials/' . $file->file_path;

        return $this->response->download($filePath, null)->setFileName($file->file_name);
    }
}
