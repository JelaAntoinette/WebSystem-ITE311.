<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Courses - Student Dashboard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #8B5FBF 0%, #7A4FB0 50%, #8B5FBF 100%);
            min-height: 100vh;
            position: relative;
        }
        body::before {
            content: '';
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: radial-gradient(circle, rgba(255,255,255,0.05) 1px, transparent 1px);
            background-size: 40px 40px; pointer-events: none; z-index: 0;
        }
        .container { max-width: 1200px; margin: 0 auto; padding: 40px 20px; position: relative; z-index: 1; }
        
        .header-card {
            background: rgba(255,255,255,0.95); backdrop-filter: blur(10px);
            border-radius: 20px; border: 1px solid rgba(255,255,255,0.2);
            padding: 30px; text-align: center; margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .header-card h1 { color: #8B5FBF; font-size: 32px; font-weight: 700; margin-bottom: 10px; }
        .header-card p { color: #666; font-size: 16px; }
        
        .courses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .course-card {
            background: rgba(255,255,255,0.95); backdrop-filter: blur(10px);
            border-radius: 15px; border: 1px solid rgba(255,255,255,0.2);
            padding: 25px; box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }
        
        .course-name {
            color: #8B5FBF; font-size: 20px; font-weight: 600; 
            margin-bottom: 15px; line-height: 1.4;
        }
        
        .course-description {
            color: #666; font-size: 14px; line-height: 1.6;
            margin-bottom: 15px; min-height: 60px;
        }
        
        .course-meta {
            display: flex; justify-content: space-between; align-items: center;
            padding-top: 15px; border-top: 1px solid #eee;
        }
        
        .enrollment-date {
            color: #999; font-size: 12px;
        }
        
        .course-status {
            background: #28a745; color: white; padding: 4px 8px;
            border-radius: 12px; font-size: 11px; font-weight: 600;
        }
        
        .no-courses {
            background: rgba(255,255,255,0.95); backdrop-filter: blur(10px);
            border-radius: 20px; border: 1px solid rgba(255,255,255,0.2);
            padding: 60px; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .no-courses h3 { color: #8B5FBF; font-size: 24px; margin-bottom: 15px; }
        .no-courses p { color: #666; margin-bottom: 20px; }
        
        .btn {
            display: inline-block; background: #8B5FBF; color: white;
            padding: 12px 24px; border-radius: 25px; text-decoration: none;
            font-weight: 600; transition: all 0.3s ease;
        }
        
        .btn:hover {
            background: #7A4FB0; transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(139, 95, 191, 0.3);
            text-decoration: none; color: white;
        }
        
        .back-btn {
            background: #6c757d; margin-bottom: 20px;
            display: inline-flex; align-items: center; gap: 8px;
        }
        
        .back-btn:hover { background: #5a6268; }
        
        .stats-card {
            background: rgba(255,255,255,0.95); backdrop-filter: blur(10px);
            border-radius: 15px; padding: 20px; margin-bottom: 30px;
            display: flex; justify-content: space-around; text-align: center;
        }
        
        .stat-item h4 { color: #8B5FBF; font-size: 24px; margin-bottom: 5px; }
        .stat-item p { color: #666; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Back Button -->
        <a href="<?= base_url('student/dashboard') ?>" class="btn back-btn">
            ← Back to Dashboard
        </a>
        
        <!-- Header -->
        <div class="header-card">
            <h1>My Enrolled Courses</h1>
            <p>Welcome back, <?= esc($user['name'] ?? 'Student') ?>! Here are your enrolled courses.</p>
        </div>
        
        <!-- Course Statistics - Only show if user has enrolled courses -->
        <?php if (!empty($enrolled_courses)): ?>
        <div class="stats-card">
            <div class="stat-item">
                <h4><?= count($enrolled_courses) ?></h4>
                <p>Enrolled Courses</p>
            </div>
            <div class="stat-item">
                <h4>
                    <?php 
                    $activeCount = 0;
                    foreach ($enrolled_courses as $course) {
                        if (isset($course['status']) && $course['status'] === 'active') {
                            $activeCount++;
                        } elseif (!isset($course['status'])) {
                            $activeCount++;
                        }
                    }
                    echo $activeCount;
                    ?>
                </h4>
                <p>Active Enrollments</p>
            </div>
            <div class="stat-item">
                <h4><?= date('Y') ?></h4>
                <p>Current Year</p>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Courses Grid - Only show enrolled courses -->
        <?php if (!empty($enrolled_courses)): ?>
            <div class="courses-grid">
                <?php foreach ($enrolled_courses as $course): ?>
                    <div class="course-card">
                        <div class="course-name">
                            <?= esc($course['course_name']) ?>
                        </div>
                        <div class="course-description">
                            <?= esc($course['description'] ?? 'No description available for this course.') ?>
                        </div>
                        <div class="course-meta">
                            <span class="enrollment-date">
                                Enrolled: <?= date('M j, Y', strtotime($course['enrollment_date'])) ?>
                            </span>
                            <span class="course-status">
                                <?= ucfirst($course['status'] ?? 'Active') ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <!-- No Enrolled Courses Message -->
            <div class="no-courses">
                <h3>No Enrolled Courses</h3>
                <p>You haven't enrolled in any courses yet. Browse available courses and start your learning journey!</p>
                <a href="<?= base_url('student/dashboard') ?>" class="btn">
                    Browse Available Courses
                </a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

<h3>Uploaded Materials</h3>

<?php if (!empty($materials)): ?>
    <ul>
        <?php foreach ($materials as $material): ?>
            <li>
                <strong><?= esc($material['title']) ?></strong>
                (<?= esc($material['course_name']) ?>) —
                <a href="<?= base_url($material['file_path']) ?>" target="_blank">Download</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No materials available yet.</p>
<?php endif; ?>
