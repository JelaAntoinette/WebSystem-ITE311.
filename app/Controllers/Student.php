<?php
namespace App\Controllers;
use App\Models\CourseModel;

class Student extends BaseController {

    public function dashboard() {
        $model = new CourseModel();
        $data['courses'] = $model->findAll(); // fetch all courses

        // fetch enrolled courses from database
        $db = \Config\Database::connect();
        $studentId = session()->get('user_id'); // use session to get logged-in student
        $builder = $db->table('enrollments');
        $builder->select('courses.*');
        $builder->join('courses', 'courses.id = enrollments.course_id');
        $builder->where('enrollments.student_id', $studentId);
        $data['enrolled'] = $builder->get()->getResultArray();

        return view('student_dashboard', $data);
    }

    public function enroll() {
        $courseId = $this->request->getPost('course_id');
        $studentId = session()->get('user_id');

        if (!$courseId || !$studentId) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid data'
            ]);
        }

        $db = \Config\Database::connect();
        $builder = $db->table('enrollments');

        // check if already enrolled
        $exists = $builder->where('student_id', $studentId)
                          ->where('course_id', $courseId)
                          ->get()->getRowArray();

        if ($exists) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Already enrolled'
            ]);
        }

        $builder->insert([
            'student_id' => $studentId,
            'course_id' => $courseId
        ]);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Enrolled successfully'
        ]);
    }
}