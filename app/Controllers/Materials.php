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
        if ($this->request->getMethod() === 'POST') {
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
                    'file_size'  => $file->getSize(),
                    'uploaded_by' => session()->get('userID') ?? session()->get('user_id'),
                    'created_at' => date('Y-m-d H:i:s'),
                ];

                $this->materialModel->insertMaterial($data);
                return redirect()->back()->with('success', 'Material uploaded successfully.');
            } else {
                return redirect()->back()->with('error', 'File upload failed.');
            }
        }
        
        // Fetch existing materials for this course to display
        $materials = $this->materialModel->getMaterialsByCourse($course_id);
        
        return view('materials/upload_form', [
            'course_id' => $course_id,
            'materials' => $materials
        ]);
    }

    // Display downloadable materials for students
    public function viewCourseMaterials($course_id)
    {
        $materials = $this->materialModel->getMaterialsByCourse($course_id);
        
        // Get course name
        $db = \Config\Database::connect();
        $course = $db->table('courses')
            ->where('id', $course_id)
            ->get()
            ->getRow();
        
        $course_name = $course ? $course->course_name : 'Unknown Course';

        return view('materials/student_materials', [
            'materials' => $materials,
            'course_id' => $course_id,
            'course_name' => $course_name,
        ]);
    }

    // Delete material
    public function delete($material_id, $course_id = null)
    {
        // Check if user is admin
        if (session()->get('role') !== 'admin') {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        $material = $this->materialModel->find($material_id);

        if ($material) {
            // Delete the file from server
            if (file_exists($material['file_path'])) {
                unlink($material['file_path']);
            }

            // Delete record from database
            $this->materialModel->delete($material_id);

            if ($course_id) {
                return redirect()->to('materials/upload/' . $course_id)
                               ->with('success', 'Material deleted successfully.');
            }

            return redirect()->back()->with('success', 'Material deleted successfully.');
        }

        return redirect()->back()->with('error', 'Material not found.');
    }

    // Secure download method
    public function download($material_id)
    {
        // Check if user is logged in
        $session = session();
        
        // Check for both possible session variable names
        $userId = $session->get('userID') ?? $session->get('user_id');
        $userRole = $session->get('role');
        
        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Please log in to download materials.');
        }

        // Get material details
        $material = $this->materialModel->find($material_id);
        if (!$material) {
            return redirect()->back()->with('error', 'Material not found.');
        }

        // If user is admin or teacher, allow download without enrollment check
        if ($userRole === 'admin' || $userRole === 'teacher') {
            // Check if file exists
            if (file_exists($material['file_path'])) {
                return $this->response->download($material['file_path'], null)
                                     ->setFileName($material['file_name']);
            }
            return redirect()->back()->with('error', 'File not found on the server.');
        }

        // For students, check if they are enrolled in the course
        if ($userRole === 'student') {
            $db = \Config\Database::connect();
            $enrollment = $db->table('enrollments')
                ->where('user_id', $userId)
                ->where('course_id', $material['course_id'])
                ->get()
                ->getRow();

            if (!$enrollment) {
                return redirect()->back()->with('error', 'You are not enrolled in this course.');
            }
        }

        // Check if file exists
        if (file_exists($material['file_path'])) {
            return $this->response->download($material['file_path'], null)
                                 ->setFileName($material['file_name']);
        }

        return redirect()->back()->with('error', 'File not found on the server.');
    }

    // Display all uploaded materials (for admin and student dashboards)
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

    // Get materials count for a specific course
    public function getMaterialsCount($course_id)
    {
        $count = $this->materialModel->where('course_id', $course_id)->countAllResults();
        return $this->response->setJSON(['count' => $count]);
    }

    // Get total size of materials for a course
    public function getCourseMaterialsSize($course_id)
    {
        $db = \Config\Database::connect();
        $result = $db->table('materials')
            ->selectSum('file_size')
            ->where('course_id', $course_id)
            ->get()
            ->getRow();
        
        $totalSize = $result ? $result->file_size : 0;
        
        return $this->response->setJSON(['total_size' => $totalSize]);
    }

    // Get latest material for a course
    public function getLatestMaterial($course_id)
    {
        $material = $this->materialModel
            ->where('course_id', $course_id)
            ->orderBy('created_at', 'DESC')
            ->first();
        
        return $this->response->setJSON($material);
    }

    // Search materials by filename
    public function searchMaterials()
    {
        $search = $this->request->getGet('search');
        
        if (!$search) {
            return redirect()->back()->with('error', 'Please enter a search term.');
        }

        $db = \Config\Database::connect();
        $materials = $db->table('materials')
            ->like('file_name', $search)
            ->orLike('course_id', $search)
            ->get()
            ->getResultArray();

        return view('materials/search_results', [
            'materials' => $materials,
            'search_term' => $search
        ]);
    }

    // Get materials by course for AJAX requests
    public function getMaterialsByAjax($course_id)
    {
        $materials = $this->materialModel->getMaterialsByCourse($course_id);
        return $this->response->setJSON($materials);
    }

    // Bulk delete materials
    public function bulkDelete()
    {
        // Check if user is admin
        if (session()->get('role') !== 'admin') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access.'
            ]);
        }

        $material_ids = $this->request->getPost('material_ids');
        
        if (!$material_ids || !is_array($material_ids)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No materials selected.'
            ]);
        }

        $deleted = 0;
        foreach ($material_ids as $material_id) {
            $material = $this->materialModel->find($material_id);
            
            if ($material) {
                // Delete file from server
                if (file_exists($material['file_path'])) {
                    unlink($material['file_path']);
                }
                
                // Delete from database
                $this->materialModel->delete($material_id);
                $deleted++;
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => "{$deleted} material(s) deleted successfully."
        ]);
    }

    // Get file type statistics
    public function getFileTypeStats($course_id = null)
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('materials');
        
        if ($course_id) {
            $builder->where('course_id', $course_id);
        }
        
        $materials = $builder->get()->getResultArray();
        
        $stats = [
            'pdf' => 0,
            'doc' => 0,
            'excel' => 0,
            'image' => 0,
            'other' => 0
        ];
        
        foreach ($materials as $material) {
            $extension = strtolower(pathinfo($material['file_name'], PATHINFO_EXTENSION));
            
            switch ($extension) {
                case 'pdf':
                    $stats['pdf']++;
                    break;
                case 'doc':
                case 'docx':
                    $stats['doc']++;
                    break;
                case 'xls':
                case 'xlsx':
                    $stats['excel']++;
                    break;
                case 'jpg':
                case 'jpeg':
                case 'png':
                case 'gif':
                    $stats['image']++;
                    break;
                default:
                    $stats['other']++;
            }
        }
        
        return $this->response->setJSON($stats);
    }

    // AJAX Upload for Admin Dashboard
    public function ajaxUpload()
    {
        // Check if user is admin
        if (session()->get('role') !== 'admin') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access.'
            ]);
        }

        // Validation rules
        $validationRule = [
            'material_file' => [
                'label' => 'Material File',
                'rules' => 'uploaded[material_file]'
                    . '|mime_in[material_file,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation,image/jpg,image/jpeg,image/png,application/zip,video/mp4]'
                    . '|max_size[material_file,10240]', // 10MB limit
            ],
            'course_id' => 'required|numeric',
            'title' => 'permit_empty|max_length[255]',
            'description' => 'permit_empty|max_length[1000]'
        ];

        if (!$this->validate($validationRule)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $file = $this->request->getFile('material_file');

        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            
            // Create directory if it doesn't exist
            $uploadPath = 'uploads/materials';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $file->move($uploadPath, $newName);

            $data = [
                'course_id'  => $this->request->getPost('course_id'),
                'file_name'  => $file->getClientName(),
                'file_path'  => $uploadPath . '/' . $newName,
                'file_size'  => $file->getSize(),
                'title'      => $this->request->getPost('title') ?: $file->getClientName(),
                'description' => $this->request->getPost('description') ?: '',
                'uploaded_by' => session()->get('userID') ?? session()->get('user_id'),
                'created_at' => date('Y-m-d H:i:s'),
            ];

            $material_id = $this->materialModel->insert($data);

            if ($material_id) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Material uploaded successfully',
                    'material_id' => $material_id,
                    'data' => $data
                ]);
            } else {
                // Delete file if database insert fails
                unlink($uploadPath . '/' . $newName);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to save material to database'
                ]);
            }
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'File upload error: ' . $file->getErrorString()
            ]);
        }
    }

    // Get all materials for admin dashboard (AJAX)
    public function getAllMaterialsAjax()
    {
        $db = \Config\Database::connect();
        $materials = $db->query("
            SELECT m.*, 
                   c.course_name, 
                   u.name AS uploader_name,
                   CONCAT(ROUND(m.file_size / 1024, 2), ' KB') as file_size_formatted
            FROM materials m
            LEFT JOIN courses c ON m.course_id = c.id
            LEFT JOIN users u ON m.uploaded_by = u.id
            ORDER BY m.created_at DESC
        ")->getResultArray();

        return $this->response->setJSON([
            'success' => true,
            'materials' => $materials
        ]);
    }

    // Update material details
    public function updateMaterial($material_id)
    {
        // Check if user is admin
        if (session()->get('role') !== 'admin') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access.'
            ]);
        }

        $title = $this->request->getPost('title');
        $description = $this->request->getPost('description');

        $data = [];
        if ($title) $data['title'] = $title;
        if ($description) $data['description'] = $description;

        if (empty($data)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No data to update'
            ]);
        }

        $updated = $this->materialModel->update($material_id, $data);

        if ($updated) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Material updated successfully'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update material'
            ]);
        }
    }

    // Get materials by course for dropdown
    public function getMaterialsByCourse($course_id)
    {
        $materials = $this->materialModel->where('course_id', $course_id)
                                         ->orderBy('created_at', 'DESC')
                                         ->findAll();
        
        return $this->response->setJSON([
            'success' => true,
            'materials' => $materials
        ]);
    }

    // View/Preview material (for PDFs and images)
    public function preview($material_id)
    {
        $session = session();
        $userId = $session->get('userID') ?? $session->get('user_id');
        
        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Please log in to view materials.');
        }

        $material = $this->materialModel->find($material_id);
        if (!$material) {
            return redirect()->back()->with('error', 'Material not found.');
        }

        if (!file_exists($material['file_path'])) {
            return redirect()->back()->with('error', 'File not found on server.');
        }

        // Get mime type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $material['file_path']);
        finfo_close($finfo);

        // Set headers
        $this->response->setHeader('Content-Type', $mimeType);
        $this->response->setHeader('Content-Disposition', 'inline; filename="' . $material['file_name'] . '"');
        
        return $this->response->setBody(file_get_contents($material['file_path']));
    }

    // Get material statistics for dashboard
    public function getDashboardStats()
    {
        $db = \Config\Database::connect();
        
        // Total materials
        $totalMaterials = $db->table('materials')->countAll();
        
        // Total size
        $sizeResult = $db->table('materials')
            ->selectSum('file_size')
            ->get()
            ->getRow();
        $totalSize = $sizeResult ? $sizeResult->file_size : 0;
        $totalSizeMB = round($totalSize / (1024 * 1024), 2);
        
        // Materials by type
        $materials = $db->table('materials')->get()->getResultArray();
        $typeStats = [
            'pdf' => 0,
            'doc' => 0,
            'excel' => 0,
            'image' => 0,
            'video' => 0,
            'other' => 0
        ];
        
        foreach ($materials as $material) {
            $extension = strtolower(pathinfo($material['file_name'], PATHINFO_EXTENSION));
            
            switch ($extension) {
                case 'pdf':
                    $typeStats['pdf']++;
                    break;
                case 'doc':
                case 'docx':
                    $typeStats['doc']++;
                    break;
                case 'xls':
                case 'xlsx':
                    $typeStats['excel']++;
                    break;
                case 'jpg':
                case 'jpeg':
                case 'png':
                case 'gif':
                    $typeStats['image']++;
                    break;
                case 'mp4':
                case 'avi':
                case 'mov':
                    $typeStats['video']++;
                    break;
                default:
                    $typeStats['other']++;
            }
        }
        
        // Recent uploads (last 7 days)
        $recentUploads = $db->table('materials')
            ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-7 days')))
            ->countAllResults();
        
        return $this->response->setJSON([
            'success' => true,
            'stats' => [
                'total_materials' => $totalMaterials,
                'total_size_mb' => $totalSizeMB,
                'type_breakdown' => $typeStats,
                'recent_uploads' => $recentUploads
            ]
        ]);
    }

    // NEW: Get all courses for dropdown (ADDED THIS!)
    public function getAllCourses()
    {
        $db = \Config\Database::connect();
        $courses = $db->table('courses')
            ->select('id, course_name')
            ->orderBy('course_name', 'ASC')
            ->get()
            ->getResultArray();
        
        return $this->response->setJSON([
            'success' => true,
            'courses' => $courses
        ]);
    }

    // NEW: Admin materials page view
    public function adminMaterialsPage()
    {
        // Check if admin
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Unauthorized access.');
        }
        
        return view('admin/materials');
    }
}