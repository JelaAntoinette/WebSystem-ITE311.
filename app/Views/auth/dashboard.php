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
        .alert-success { background: rgba(101, 114, 104, 0.9); color: #155724; border-left: 4px solid #28a745; backdrop-filter: blur(10px); }
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
        .view-all-link { font-size: 12px; float: right; color: #8B5FBF; text-decoration: none; }
        .view-all-link:hover { color: #7A4FB0; text-decoration: underline; }
        
        /* NEW: Admin Quick Actions */
        .admin-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        .action-card {
            background: linear-gradient(135deg, #8B5FBF 0%, #7A4FB0 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 15px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(139, 95, 191, 0.3);
        }
        .action-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(139, 95, 191, 0.4);
            text-decoration: none;
            color: white;
        }
        .action-icon {
            font-size: 32px;
        }
        .action-text h6 {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
        }
        .action-text p {
            margin: 5px 0 0 0;
            font-size: 12px;
            opacity: 0.9;
        }
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

            <!-- ✅ Admin Section with Quick Actions -->
            <?php if ($user['role'] === 'admin'): ?>
                <!-- NEW: Admin Quick Actions -->
                <div class="role-card" style="grid-column: 1 / -1;">
                    <h5>Admin Quick Actions</h5>
                    <div class="admin-actions">
                        <a href="<?= base_url('admin/manage') ?>" class="action-card">
                            <div class="action-icon">👥</div>
                            <div class="action-text">
                                <h6>Manage Users</h6>
                                <p>Add, edit, or remove users</p>
                            </div>
                        </a>
                        <a href="<?= base_url('admin/materials') ?>" class="action-card">
                            <div class="action-icon">📁</div>
                            <div class="action-text">
                                <h6>Manage Materials</h6>
                                <p>Upload & organize course materials</p>
                            </div>
                        </a>
                        <a href="<?= base_url('reports') ?>" class="action-card">
                            <div class="action-icon">📊</div>
                            <div class="action-text">
                                <h6>Reports</h6>
                                <p>View system reports & analytics</p>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- All Users Table -->
                <div class="role-card" style="grid-column: 1 / -1;">
                    <h5>All Users</h5>
                    <?php if (isset($allUsers) && !empty($allUsers)): ?>
                        <table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse:collapse; font-size:14px;">
                            <thead>
                                <tr style="background:#8B5FBF;color:white;">
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Date Registered</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($allUsers as $u): ?>
                                    <tr style="background:#f9f9f9;text-align:center;">
                                        <td><?= esc($u['id']) ?></td>
                                        <td><?= esc($u['name']) ?></td>
                                        <td><?= esc($u['email']) ?></td>
                                        <td><?= ucfirst(esc($u['role'])) ?></td>
                                        <td><?= esc($u['created_at'] ?? 'N/A') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p style="color: #dc3545;">No users found in database.</p>
                        <p style="font-size: 12px; color: #666;">Debug: allUsers variable is <?= isset($allUsers) ? (empty($allUsers) ? 'empty' : 'set with ' . count($allUsers) . ' users') : 'not set' ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Teacher Section -->
            <?php if ($user['role'] === 'teacher' && isset($user['courses'])): ?>
                <div class="role-card">
                    <h5>Classes</h5>
                    <ul>
                        <?php if (!empty($user['courses'])): ?>
                            <?php foreach ($user['courses'] as $course): ?>
                                <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid #eee;">
                                    <div>
                                        <strong><?= esc($course['course_name']) ?></strong><br>
                                        <small><?= esc($course['description'] ?? '') ?></small>
                                    </div>
                                    <a href="<?= base_url('/teacher/course/' . $course['id'] . '/upload') ?>" class="enroll-btn">Upload Materials</a>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No available courses.</p>
                        <?php endif; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Student Section -->
            <?php if ($user['role'] === 'student'): ?>
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

                <div class="role-card">
                    <h5>My Courses 
                        <a href="<?= base_url('student/my-courses') ?>" class="view-all-link">View All →</a>
                    </h5>
                    <?php if (!empty($enrolled)): ?>
                        <ul>
                            <?php 
                            $displayCount = 0;
                            foreach ($enrolled as $course): 
                                if ($displayCount >= 3) break;
                                $displayCount++;
                            ?>
                                <li><?= esc($course['course_name']) ?></li>
                            <?php endforeach; ?>
                            <?php if (count($enrolled) > 3): ?>
                                <li><a href="<?= base_url('student/my-courses') ?>">... and <?= count($enrolled) - 3 ?> more</a></li>
                            <?php endif; ?>
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
                url: '<?= base_url('student/enroll') ?>',
                type: 'POST',
                data: { course_id: courseId },
                dataType: 'json',
                success: function(response){
                    msgBox.text(response.message);
                    if (response.status === 'success') {
                        button.prop('disabled', true).text('Enrolled');
                        msgBox.css('color', 'green');
                        setTimeout(function() { location.reload(); }, 2000);
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