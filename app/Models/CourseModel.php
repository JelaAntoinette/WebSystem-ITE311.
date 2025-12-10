<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Course Model
 * Handles database operations for courses
 */
class CourseModel extends Model
{
    protected $table            = 'courses';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    
    protected $allowedFields    = [
        'course_name',
        'subject_code',
        'description',
        'instructor_name',
        'year_level',
        'date_started',
        'date_ended',
        'year_started',
        'year_ended',
        'status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'course_name' => 'required|min_length[3]|max_length[100]',
        'subject_code' => 'permit_empty|max_length[20]',
        'status' => 'required|in_list[active,inactive,completed]'
    ];
    
    protected $validationMessages   = [
        'course_name' => [
            'required' => 'Course name is required',
            'min_length' => 'Course name must be at least 3 characters long'
        ]
    ];
    
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Get all active courses
     */
    public function getActiveCourses()
    {
        return $this->where('status', 'active')
                    ->orderBy('course_name', 'ASC')
                    ->findAll();
    }

    /**
     * Get courses by instructor
     */
    public function getCoursesByInstructor($instructorName)
    {
        return $this->where('instructor_name', $instructorName)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get courses by year level
     */
    public function getCoursesByYearLevel($yearLevel)
    {
        return $this->where('year_level', $yearLevel)
                    ->where('status', 'active')
                    ->orderBy('course_name', 'ASC')
                    ->findAll();
    }

    /**
     * Search courses by keyword
     */
    public function searchCourses($keyword)
    {
        return $this->groupStart()
                    ->like('course_name', $keyword)
                    ->orLike('subject_code', $keyword)
                    ->orLike('description', $keyword)
                    ->orLike('instructor_name', $keyword)
                    ->groupEnd()
                    ->orderBy('course_name', 'ASC')
                    ->findAll();
    }

    /**
     * Get course with enrollment count
     */
    public function getCourseWithEnrollmentCount($courseId)
    {
        $db = \Config\Database::connect();
        
        $query = $db->query("
            SELECT c.*, COUNT(e.id) as enrollment_count
            FROM courses c
            LEFT JOIN enrollments e ON c.id = e.course_id AND e.status = 'active'
            WHERE c.id = ?
            GROUP BY c.id
        ", [$courseId]);
        
        return $query->getRowArray();
    }
}
