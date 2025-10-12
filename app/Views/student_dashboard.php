<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-light">

<div class="container py-5">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="<?= site_url('dashboard') ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Main Dashboard
        </a>
    </div>

    <h2 class="text-center mb-4 text-primary fw-bold">🎓 Student Dashboard</h2>

    <!-- ✅ Enrolled Courses Section -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-success text-white fw-bold">My Enrolled Courses</div>
        <div class="card-body" id="enrolledCourses">
            <?php if (!empty($enrolled)): ?>
                <ul class="list-group">
                    <?php foreach ($enrolled as $course): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="<?= site_url('course/view/' . $course['id']) ?>" class="text-decoration-none text-dark">
                                <?= esc($course['name']) ?>
                            </a>
                            <span class="badge bg-success">Enrolled</span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-muted">You are not enrolled in any courses yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- 📚 Available Courses Section -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white fw-bold">Available Courses</div>
        <div class="card-body">
            <?php if (!empty($courses)): ?>
                <ul class="list-group" id="availableCourses">
                    <?php foreach ($courses as $course): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="<?= site_url('course/view/' . $course['id']) ?>" class="text-decoration-none text-dark">
                                <?= esc($course['name']) ?>
                            </a>
                            <button 
                                class="btn btn-sm btn-outline-primary enroll-btn"
                                data-course-id="<?= esc($course['id']) ?>"
                                <?php
                                    foreach($enrolled as $e) {
                                        if($e['id'] == $course['id']) echo 'disabled';
                                    }
                                ?>
                            >
                                <?php
                                    $isEnrolled = false;
                                    foreach($enrolled as $e) {
                                        if($e['id'] == $course['id']) $isEnrolled = true;
                                    }
                                    echo $isEnrolled ? 'Enrolled' : 'Enroll';
                                ?>
                            </button>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-muted">No available courses to enroll.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- 💬 Alert message area -->
    <div id="alertBox" class="mt-3"></div>
</div>

<script>
$(document).ready(function() {
    $('.enroll-btn').on('click', function(e) {
        e.preventDefault();

        const button = $(this);
        const courseId = button.data('course-id');

      $.post('<?= base_url('course/enroll') ?>', { course_id: courseId }, function(response) {

            let alertClass = (response.status === 'success') ? 'alert-success' : 'alert-danger';
            
            $('#alertBox').html(`
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    ${response.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `);

            if (response.status === 'success') {
                // Move the course to the enrolled list dynamically
                const courseName = button.closest('li').text().trim();
                if ($('#enrolledCourses ul').length === 0) {
                    $('#enrolledCourses').html('<ul class="list-group"></ul>');
                }
                $('#enrolledCourses ul').append(`
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        ${courseName}
                        <span class="badge bg-success">Enrolled</span>
                    </li>
                `);

                // Disable the button
                button.prop('disabled', true).text('Enrolled');
            }
        }, 'json');
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
