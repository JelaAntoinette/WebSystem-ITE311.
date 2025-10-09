<?php

namespace App\Models;

use CodeIgniter\Model;

class EnrollmentModel extends Model
{
    protected $table      = 'enrollments';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'course_id', 'enrollment_date'];
    protected $useTimestamps = false; // we use enrollment_date instead

    /**
     * Insert a new enrollment. Returns insertID on success, false on duplicate or failure.
     *
     * $data should contain: user_id, course_id, enrollment_date (optional)
     */
    public function enrollUser(array $data)
    {
        // ensure enrollment_date exists
        if (empty($data['enrollment_date'])) {
            $data['enrollment_date'] = date('Y-m-d H:i:s');
        }

        // prevent duplicate at app level
        if ($this->isAlreadyEnrolled($data['user_id'], $data['course_id'])) {
            return false;
        }

        return $this->insert($data); // returns insert id or false
    }

    /**
     * Get all enrollments (courses) for a given user ID
     */
    public function getUserEnrollments(int $user_id)
    {
        return $this->where('user_id', $user_id)->findAll();
    }

    /**
     * Check if a user is already enrolled in a course
     */
    public function isAlreadyEnrolled(int $user_id, int $course_id): bool
    {
        return (bool)$this->where(['user_id' => $user_id, 'course_id' => $course_id])->first();
    }
}
