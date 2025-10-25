<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Courses - Student Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
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
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .enrollment-date {
            color: #999; font-size: 12px;
        }
        
        .course-status {
            background: #28a745; color: white; padding: 4px 8px;
            border-radius: 12px; font-size: 11px; font-weight: 600;
        }
        
        .view-materials-btn {
            display: inline-block;
            background: #8B5FBF;
            color: white;
            padding: 6px 12px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .view-materials-btn:hover {
            background: #7A4FB0;
            text-decoration: none;
            color: white;
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
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .stat-item h4 { color: #8B5FBF; font-size: 24px; margin-bottom: 5px; }
        .stat-item p { color: #666; font-size: 14px; }
        
        /* Materials Section Styles */
        .materials-section {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid rgba(255,255,255,0.2);
            padding: 30px;
            margin-top: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .materials-section h3 {
            color: #8B5FBF;
            font-size: 24px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .materials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 15px;
        }
        
        .material-card {
            background: white;
            border: 1px solid #eee;
            border-radius: 10px;
            padding: 15px;
            transition: all 0.3s ease;
        }
        
        .material-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .material-icon {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        
        .material-title {
            color: #333;
            font-weight: 600;
            margin-bottom: 5px;
            font-size: 14px;
        }
        
        .material-course {
            color: #8B5FBF;
            font-size: 12px;
            margin-bottom: 5px;
        }
        
        .material-date {
            color: #999;
            font-size: 11px;
            margin-bottom: 10px;
        }
        
        .download-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #28a745;
            color: white;
            padding: 6px 12px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .download-btn:hover {
            background: #218838;
            text-decoration: none;
            color: white;
        }
        
        .no-materials {
            text-align: center;
            padding: 40px;
            color: #999;
        }
        
        .no-materials i {
            font-size: 3rem;
            margin-bottom: 15px;
            opacity: 0.5;
        }
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
                <h4><?= !empty($materials) ? count($materials) : 0 ?></h4>
                <p>Available Materials</p>
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
                            <a href="<?= base_url('materials/viewCourseMaterials/' . $course['id']) ?>" class="view-materials-btn">
                                <i class="bi bi-folder2-open"></i> View Materials
                            </a>
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
        
        <!-- Materials Section -->
        <?php if (!empty($enrolled_courses)): ?>
        <div class="materials-section">
            <h3>
                <i class="bi bi-files"></i>
                Recent Course Materials
            </h3>
            
            <?php if (!empty($materials)): ?>
                <div class="materials-grid">
                    <?php foreach (array_slice($materials, 0, 6) as $material): ?>
                        <div class="material-card">
                            <div class="material-icon">
                                <?php 
                                $extension = pathinfo($material['file_name'], PATHINFO_EXTENSION);
                                $iconClass = 'bi-file-earmark';
                                $iconColor = '#6c757d';
                                
                                switch(strtolower($extension)) {
                                    case 'pdf':
                                        $iconClass = 'bi-file-earmark-pdf';
                                        $iconColor = '#dc3545';
                                        break;
                                    case 'doc':
                                    case 'docx':
                                        $iconClass = 'bi-file-earmark-word';
                                        $iconColor = '#0d6efd';
                                        break;
                                    case 'xls':
                                    case 'xlsx':
                                        $iconClass = 'bi-file-earmark-excel';
                                        $iconColor = '#28a745';
                                        break;
                                    case 'ppt':
                                    case 'pptx':
                                        $iconClass = 'bi-file-earmark-ppt';
                                        $iconColor = '#fd7e14';
                                        break;
                                    case 'jpg':
                                    case 'jpeg':
                                    case 'png':
                                    case 'gif':
                                        $iconClass = 'bi-file-earmark-image';
                                        $iconColor = '#0dcaf0';
                                        break;
                                }
                                ?>
                                <i class="bi <?= $iconClass ?>" style="color: <?= $iconColor ?>;"></i>
                            </div>
                            <div class="material-title">
                                <?= esc($material['file_name']) ?>
                            </div>
                            <div class="material-course">
                                <i class="bi bi-book"></i> <?= esc($material['course_name']) ?>
                            </div>
                            <div class="material-date">
                                <i class="bi bi-calendar3"></i> <?= date('M d, Y', strtotime($material['created_at'])) ?>
                            </div>
                            <a href="<?= base_url('materials/download/' . $material['id']) ?>" class="download-btn">
                                <i class="bi bi-download"></i> Download
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if (count($materials) > 6): ?>
                <div style="text-align: center; margin-top: 20px;">
                    <p style="color: #666;">
                        Showing 6 of <?= count($materials) ?> materials. 
                        Click "View Materials" on each course to see all files.
                    </p>
                </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="no-materials">
                    <i class="bi bi-inbox"></i>
                    <p>No materials available yet for your enrolled courses.</p>
                </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>