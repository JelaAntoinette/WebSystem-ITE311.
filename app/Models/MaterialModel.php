<?php

namespace App\Models;

use CodeIgniter\Model;

class MaterialModel extends Model
{
    protected $table = 'materials';
    protected $primaryKey = 'id';
    protected $allowedFields = ['course_id', 'file_name', 'file_path', 'file_size', 'uploaded_by', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;

    // Insert a new material
    public function insertMaterial($data)
    {
        return $this->insert($data);
    }

    // Get all materials for a specific course
    public function getMaterialsByCourse($course_id)
    {
        return $this->where('course_id', $course_id)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    // ✅ NEW: Get material with course details
    public function getMaterialWithCourse($material_id)
    {
        return $this->db->table('materials m')
            ->select('m.*, c.course_name, c.course_code')
            ->join('courses c', 'c.id = m.course_id', 'left')
            ->where('m.id', $material_id)
            ->get()
            ->getRowArray();
    }

    // ✅ NEW: Get all materials with course information
    public function getAllMaterialsWithCourses()
    {
        return $this->db->table('materials m')
            ->select('m.*, c.course_name, c.course_code, u.name as uploaded_by_name')
            ->join('courses c', 'c.id = m.course_id', 'left')
            ->join('users u', 'u.id = m.uploaded_by', 'left')
            ->orderBy('m.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    // ✅ NEW: Get total materials count for a course
    public function getTotalMaterialsCount($course_id)
    {
        return $this->where('course_id', $course_id)->countAllResults();
    }

    // ✅ NEW: Get total file size for a course
    public function getTotalFileSize($course_id)
    {
        $result = $this->selectSum('file_size')
                      ->where('course_id', $course_id)
                      ->first();
        
        return $result['file_size'] ?? 0;
    }

    // ✅ NEW: Get latest material for a course
    public function getLatestMaterial($course_id)
    {
        return $this->where('course_id', $course_id)
                    ->orderBy('created_at', 'DESC')
                    ->first();
    }

    // ✅ NEW: Search materials by filename
    public function searchByFileName($search_term)
    {
        return $this->like('file_name', $search_term)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    // ✅ NEW: Get materials uploaded by specific user
    public function getMaterialsByUploader($user_id)
    {
        return $this->where('uploaded_by', $user_id)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    // ✅ NEW: Delete materials by course ID
    public function deleteByCourse($course_id)
    {
        return $this->where('course_id', $course_id)->delete();
    }

    // ✅ NEW: Get material count by file type for a course
    public function getFileTypeCount($course_id)
    {
        $materials = $this->where('course_id', $course_id)->findAll();
        
        $types = [
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
                    $types['pdf']++;
                    break;
                case 'doc':
                case 'docx':
                    $types['doc']++;
                    break;
                case 'xls':
                case 'xlsx':
                    $types['excel']++;
                    break;
                case 'jpg':
                case 'jpeg':
                case 'png':
                case 'gif':
                    $types['image']++;
                    break;
                default:
                    $types['other']++;
            }
        }
        
        return $types;
    }

    // ✅ NEW: Check if student has access to material
    public function checkStudentAccess($material_id, $user_id)
    {
        $material = $this->find($material_id);
        
        if (!$material) {
            return false;
        }
        
        // Check if user is enrolled in the course
        $enrollment = $this->db->table('enrollments')
            ->where('user_id', $user_id)
            ->where('course_id', $material['course_id'])
            ->get()
            ->getRow();
        
        return !empty($enrollment);
    }

    // ✅ NEW: Get recent materials (limit)
    public function getRecentMaterials($limit = 10)
    {
        return $this->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    // ✅ NEW: Get materials statistics
    public function getMaterialsStatistics()
    {
        $total = $this->countAll();
        $totalSize = $this->selectSum('file_size')->first();
        
        return [
            'total_materials' => $total,
            'total_size' => $totalSize['file_size'] ?? 0,
            'total_courses' => $this->distinct()->countAllResults('course_id')
        ];
    }
}