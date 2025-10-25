<?php

namespace App\Controllers;

use App\Models\MaterialModel;
use CodeIgniter\Controller;

class Materials extends Controller
{
    protected $materialModel;

    public function __construct()
    {
        $this->materialModel = new MaterialModel();
        helper(['form', 'url']);
    }

    // Show upload form and handle upload
    public function upload($course_id)
    {
        if ($this->request->getMethod() === 'POST   ') {
            // Load validation rules
            $validationRule = [
                'material_file' => [
                    'label' => 'Material File',
                    'rules' => 'uploaded[material_file]'
                        . '|mime_in[material_file,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,image/jpg,image/jpeg,image/png]'
                        . '|max_size[material_file,5120]', // 5MB limit
                ],
            ];

            if (!$this->validate($validationRule)) {
                return redirect()->back()->with('error', $this->validator->listErrors());
            }

            // Handle the upload
            $file = $this->request->getFile('material_file');

            if ($file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move('uploads/materials', $newName);

                $data = [
                    'course_id'  => $course_id,
                    'file_name'  => $file->getClientName(),
                    'file_path'  => 'uploads/materials/' . $newName,
                    'created_at' => date('Y-m-d H:i:s'),
                ];

                $this->materialModel->insertMaterial($data);
                return redirect()->back()->with('success', 'Material uploaded successfully.');
            } else {
                return redirect()->back()->with('error', 'File upload failed.');
            }
        }
        $user = session()->get('role');
        return view('materials/upload_form', ['course_id' => $course_id]);
    }

    // ✅ Display downloadable materials for students
    public function viewCourseMaterials($course_id)
    {
        $materials = $this->materialModel->getMaterialsByCourse($course_id);

        return view('materials/student_materials', [
            'materials' => $materials,
            'course_id' => $course_id,
        ]);
    }

    // Delete material
    public function delete($material_id)
    {
        $material = $this->materialModel->find($material_id);

        if ($material) {
            // Delete the file from server
            if (file_exists($material['file_path'])) {
                unlink($material['file_path']);
            }

            // Delete record from database
            $this->materialModel->delete($material_id);

            return redirect()->back()->with('success', 'Material deleted successfully.');
        }

        return redirect()->back()->with('error', 'Material not found.');
    }

    // ✅ Secure download method (Step 7)
    public function download($material_id)
    {
        // Check if user is logged in
        $session = session();
        $userId = $session->get('user_id');
        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Please log in to download materials.');
        }

        // Get material details
        $material = $this->materialModel->find($material_id);
        if (!$material) {
            return redirect()->back()->with('error', 'Material not found.');
        }

        // Check if user is enrolled in the course
        $db = \Config\Database::connect();
        $enrollment = $db->table('enrollments')
            ->where('user_id', $userId)
            ->where('course_id', $material['course_id'])
            ->get()
            ->getRow();

        if (!$enrollment) {
            return redirect()->back()->with('error', 'You are not enrolled in this course.');
        }

        // Check if file exists
        if (file_exists($material['file_path'])) {
            return $this->response->download($material['file_path'], null);
        }

        return redirect()->back()->with('error', 'File not found on the server.');
    }

    // ✅ NEW: Display all uploaded materials (for admin and student dashboards)
    public function allMaterials()
    {
        $db = \Config\Database::connect();
        $materials = $db->query("
            SELECT m.*, c.course_name, u.name AS uploaded_by
            FROM materials m
            LEFT JOIN courses c ON m.course_id = c.id
            LEFT JOIN users u ON c.teacher_id = u.id
            ORDER BY m.created_at DESC
        ")->getResultArray();

        return view('materials/all_materials', ['materials' => $materials]);
    }
}
