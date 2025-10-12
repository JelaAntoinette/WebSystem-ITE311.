<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($course->name) ?> - Course Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="<?= site_url('student/dashboard') ?>" class="btn btn-secondary btn-lg">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
    </div>
    
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= site_url('student/dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= esc($course->name) ?></li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="<?= site_url('student/dashboard') ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title mb-0"><?= esc($course->name) ?></h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <h4 class="mb-3">Course Description</h4>
                    <p class="lead"><?= esc($course->description) ?></p>

                    <!-- Course Details -->
                    <div class="mt-4">
                        <h5>Course Information</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <strong>Course Code:</strong> <?= esc($course->code) ?>
                            </li>
                            <li class="list-group-item">
                                <strong>Credits:</strong> <?= esc($course->credits) ?>
                            </li>
                            <li class="list-group-item">
                                <strong>Status:</strong> 
                                <span class="badge bg-success">Active</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Course Actions</h5>
                            <button class="btn btn-primary btn-lg w-100 mb-3 enroll-btn" 
                                    data-course-id="<?= esc($course->id) ?>">
                                Enroll Now
                            </button>
                            <a href="<?= site_url('student/dashboard') ?>" class="btn btn-secondary w-100">
                                <i class="bi bi-arrow-left"></i> Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('.enroll-btn').click(function() {
        const courseId = $(this).data('course-id');
        
        $.ajax({
            url: '<?= site_url('course/enroll') ?>',
            type: 'POST',
            data: {
                course_id: courseId
            },
            success: function(response) {
                if(response.status === 'success') {
                    alert('Successfully enrolled in the course!');
                    window.location.href = '<?= site_url('student/dashboard') ?>';
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
            }
        });
    });
});
</script>

</body>
</html>