<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard - LMS' ?></title>
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
        .alert { padding: 15px 20px; border-radius: 12px; margin-bottom: 25px; font-size: 14px; font-weight: 500; }
        .alert-success { background: rgba(212, 237, 218, 0.9); color: #155724; border-left: 4px solid #28a745; backdrop-filter: blur(10px); }
        .alert-danger { background: rgba(248, 215, 218, 0.9); color: #721c24; border-left: 4px solid #dc3545; backdrop-filter: blur(10px); }
        .welcome-card, .user-info-card, .role-card {
            background: rgba(255,255,255,0.95); backdrop-filter: blur(10px);
            border-radius: 20px; border: 1px solid rgba(255,255,255,0.2);
            padding: 40px; text-align: center; margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .welcome-card h2 { color: #8B5FBF; font-size: 32px; font-weight: 700; margin: 0; letter-spacing: -1px; }
        .user-info-card h5, .role-card h5 { color: #8B5FBF; font-size: 20px; font-weight: 600; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px; }
        .info-item { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #E8E8E8; }
        .info-item:last-child { border-bottom: none; }
        .info-label { color: #666; font-weight: 600; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; }
        .info-value { color: #333; font-weight: 500; font-size: 16px; }
        .role-badge { background: #8B5FBF; color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .logout-btn { position: fixed; bottom: 30px; left: 30px; background: #dc3545; color: white; border: none; padding: 12px 20px; border-radius: 50px; font-size: 14px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease; box-shadow: 0 5px 15px rgba(220,53,69,0.3); z-index: 1000; }
        .logout-btn:hover { background: #c82333; transform: translateY(-2px); box-shadow: 0 8px 25px rgba(220,53,69,0.4); color: white; text-decoration: none; }
        .dashboard-grid { display: grid; grid-template-columns: 1fr; gap: 20px; }
        @media (min-width: 768px) { .dashboard-grid { grid-template-columns: 1fr 1fr; } .welcome-card { grid-column: 1 / -1; } }
        .enroll-btn { background:#8B5FBF;color:white;border:none;border-radius:5px;padding:6px 12px;cursor:pointer;transition:0.2s; }
        .enroll-btn:hover { background:#7A4FB0; }
    </style>
</head>
<body>

    <!-- Include Header/Navbar -->
    <?= view('templates/header', ['user' => $user]) ?>

    <div class="container">
        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <div class="dashboard-grid">
            <!-- Welcome Card -->
            <div class="welcome-card">
                <h2>Welcome back, <?= esc($user['name']) ?>!</h2>
                <p>Role: <span class="role-badge"><?= ucfirst(esc($user['role'])) ?></span></p>
            </div>

            <!-- User Info Card -->
            <div class="user-info-card">
                <h5>User Information</h5>
                <div class="info-item"><span class="info-label">Full Name</span><span class="info-value"><?= esc($user['name']) ?></span></div>
                <div class="info-item"><span class="info-label">Email Address</span><span class="info-value"><?= esc($user['email']) ?></span></div>
                <div class="info-item"><span class="info-label">Account Role</span><span class="role-badge"><?= ucfirst(esc($user['role'])) ?></span></div>
            </div>

            <!-- Role-specific content -->
            <?php if ($user['role'] === 'admin' && isset($allUsers)): ?>
                <div class="role-card">
                    <h5>All Users</h5>
                    <ul>
                        <?php foreach($allUsers as $u): ?>
                            <li><?= esc($u['name']) ?> (<?= esc($u['role']) ?>)</li>
                        <?php endforeach; ?>
                    </ul>
                </div>

            <?php elseif ($user['role'] === 'teacher' && isset($classes)): ?>
                <div class="role-card">
                    <h5>My Classes</h5>
                    <ul>
                        <?php foreach($classes as $c): ?>
                            <li><?= esc($c['class_name']) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

            <?php elseif ($user['role'] === 'student'): ?>
                <!-- ✅ Available Courses Section -->
                <div class="role-card">
                    <h5>Available Courses</h5>
                    <?php if (!empty($courses)): ?>
                        <?php foreach ($courses as $course): ?>
                            <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid #eee;">
                                <div>
                                    <strong><?= esc($course['course_name']) ?></strong><br>
                                    <small><?= esc($course['description'] ?? '') ?></small>
                                </div>
                                <button class="enroll-btn" data-id="<?= $course['id'] ?>">Enroll</button>
                                <div id="msg-<?= $course['id'] ?>" style="font-size:13px;margin-left:10px;"></div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No available courses.</p>
                    <?php endif; ?>
                </div>

                <!-- ✅ My Courses Section -->
                <div class="role-card">
                    <h5>My Courses</h5>
                    <?php if (!empty($enrolled)): ?>
                        <ul>
                            <?php foreach ($enrolled as $course): ?>
                                <li><?= esc($course['course_name']) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>You haven't enrolled in any courses yet.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Logout Button -->
    <a href="<?= base_url('logout') ?>" class="logout-btn" onclick="return confirm('Are you sure you want to logout?')">🚪 Logout</a>

    <!-- ✅ Enroll AJAX Script -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function(){
        $('.enroll-btn').click(function(){
            let courseId = $(this).data('id');
            let button = $(this);
            let msgBox = $('#msg-' + courseId);

            $.ajax({
                url: '<?= base_url('course/enroll') ?>', // ✅ updated to Course controller
                type: 'POST',
                data: { course_id: courseId },
                dataType: 'json',
                success: function(response){
                    msgBox.text(response.message);
                    if (response.status === 'success') {
                        button.prop('disabled', true).text('Enrolled');
                        msgBox.css('color', 'green');
                    } else {
                        msgBox.css('color', 'red');
                    }
                },
                error: function(){
                    msgBox.text('Error connecting to server.');
                    msgBox.css('color', 'red');
                }
            });
        });
    });
    </script>
</body>
</html>
