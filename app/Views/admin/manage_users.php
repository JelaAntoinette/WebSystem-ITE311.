<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Manage Users') ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { 
            font-family: 'Segoe UI', Arial, sans-serif; 
            background: #f0f2f5; 
            margin: 0; 
            padding: 0; 
            min-height: 100vh;
        }
        .header {
            background: linear-gradient(135deg, #b305ceff, #f403ecff);
            padding: 20px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header-title {
            font-size: 24px;
            font-weight: 500;
        }
        .header-actions {
            display: flex;
            gap: 15px;
        }
        .add-btn, .back-btn {
            padding: 10px 20px;
            border-radius: 6px;
            color: white;
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }
        .add-btn {
            background: #28a745;
        }
        .add-btn:hover {
            background: #d40bdbff;
        }
        .back-btn {
            background: #7b27dbff;
        }
        .back-btn:hover {
            background: #004494;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .user-name {
            font-size: 1rem;
            color: #2c3e50;
        }
        .email-info {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .email-info a {
            color: #007bff;
            text-decoration: none;
        }
        .email-info a:hover {
            text-decoration: underline;
        }
        .role-badge {
            padding: 6px 12px;
            border-radius: 20px;
            color: white;
            font-size: 0.85rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
        }
        .text-center {
            text-align: center;
        }
        th {
            background: #343a40;
            color: white;
            padding: 15px;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        td {
            padding: 15px;
            vertical-align: middle;
        }
        tr:hover {
            background-color: #f8f9fa;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            color: white;
        }
        .edit-btn {
            background: #007bff;
        }
        .edit-btn:hover {
            background: #0056b3;
        }
        .delete-btn {
            background: #dc3545;
        }
        .delete-btn:hover {
            background: #c82333;
        }
        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 0;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .modal-header {
            background: #343a40;
            color: white;
            padding: 20px;
            border-radius: 12px 12px 0 0;
        }
        .modal-header h2 {
            margin: 0;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        form {
            padding: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #2c3e50;
            font-size: 0.95rem;
        }
        .form-group label i {
            width: 20px;
            color: #007bff;
        }
        .form-input {
            width: 93%;
            padding: 10px 12px;
            border: 2px solid #e1e1e1;
            border-radius: 6px;
            font-size: 1rem;
            transition: all 0.3s;
        }
        .form-input:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
        }
        .password-input-group {
            position: relative;
        }
        .password-hint {
            display: block;
            margin-top: 5px;
            color: #666;
            font-size: 0.85rem;
            font-style: italic;
        }
        .modal-actions {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        .btn-primary {
            background: #129a26ff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
            transition: all 0.2s;
        }
        .btn-primary:hover {
            background: #218838;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
            transition: all 0.2s;
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
        .btn-primary {
            background: #176d27ff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 10px;
        }
        .edit-btn {
            background: #007bff;
        }
        .delete-btn {
            background: #cc2301ff;
        }
        .header { 
            background: linear-gradient(135deg, #6c0492ff, #cf06b4ff);
            color: white;
            padding: 20px;
            font-size: 22px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .cards { 
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            padding: 20px;
            max-width: 1200px;
            margin: 20px auto;
        }
        .card { 
            background: white;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card h2 { 
            margin: 0;
            font-size: 32px;
            color: #000000ff;
        }
        .card p {
            margin: 10px 0 0;
            color: #666;
            font-size: 16px;
        }
        .content-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        table { 
            width: 100%;
            margin: 20px auto;
            border-collapse: separate;
            border-spacing: 0;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        th, td { 
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th { 
            background: #343a40;
            color: white;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 14px;
        }
        tr:hover { 
            background: #f8f9fa;
        }
        .btn { 
            padding: 5px 16px;
            border-radius: 6px;
            text-decoration: none;
            color: white;
            display: inline-block;
            margin: 0 5px;
            font-weight: 500;
            transition: opacity 0.2s;
        }
        .btn:hover {
            opacity: 0.9;
        }
        .edit { background: #007bff; }
        .delete { background: #dc3545; }
        .role {
            padding: 6px 12px;
            border-radius: 20px;
            color: white;
            font-weight: 500;
            font-size: 14px;
            text-transform: uppercase;
            display: inline-block;
        }
        .student { background: #007bff; }
        .teacher { background: #ffc107; color: #000; }
        .admin { background: #b10202ff; }
        .header-actions {
            display: flex;
            gap: 15px;
        }
        .add-btn { 
            background: #5f04c7ff;
            padding: 12px 24px;
            border-radius: 6px;
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.2s;
        }
        .logout-btn { 
            background: #dc3545;
            padding: 12px 24px;
            border-radius: 6px;
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.2s;
        }
        .add-btn:hover, .logout-btn:hover {
            opacity: 0.9;
        }
        .actions { 
            display: flex;
            gap: 8px;
            justify-content: flex-end;
        }
        .section-title {
            font-size: 24px;
            color: #343a40;
            margin: 40px 20px 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #dc3545;
        }
        .alert {
            padding: 15px;
            border-radius: 6px;
            margin: 20px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .manage-users {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-title">
            <i class="fas fa-users-cog"></i> ITE311 JEMINEZ LMS - Admin Panel
        </div>
        <div class="header-actions">
            <button onclick="showAddUserModal()" class="add-btn">
                <i class="fas fa-user-plus"></i> Add New User
            </button>
            <a href="<?= site_url('dashboard') ?>" class="back-btn">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <!-- Add User Modal -->
        <div id="addUserModal" class="modal" style="display: none;">
            <div class="modal-content">
                <div class="modal-header">
                    <h2><i class="fas fa-user-plus"></i> Add New User</h2>
                </div>
                <form action="<?= site_url('admin/users/store') ?>" method="post">
                    <div class="form-group">
                        <label for="name">
                            <i class="fas fa-user"></i> Full Name:
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               required 
                               placeholder="Enter full name"
                               class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="email">
                            <i class="fas fa-envelope"></i> Email Address:
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               required 
                               placeholder="Enter email address"
                               class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="password">
                            <i class="fas fa-lock"></i> Password:
                        </label>
                        <div class="password-input-group">
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   required 
                                   placeholder="Enter password"
                                   class="form-input">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="role">
                            <i class="fas fa-user-tag"></i> User Role:
                        </label>
                        <select name="role" id="role" required class="form-input">
                            <option value="">Select a role</option>
                            <option value="admin">Administrator</option>
                            <option value="teacher">Teacher</option>
                            <option value="student">Student</option>
                        </select>
                    </div>
                    <div class="modal-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save User
                        </button>
                        <button type="button" onclick="hideAddUserModal()" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit User Modal -->
        <div id="editUserModal" class="modal" style="display: none;">
            <div class="modal-content">
                <div class="modal-header">
                    <h2><i class="fas fa-user-edit"></i> Edit User</h2>
                </div>
                <form id="editUserForm" action="" method="post">
                    <div class="form-group">
                        <label for="edit_name">
                            <i class="fas fa-user"></i> Full Name:
                        </label>
                        <input type="text" 
                               id="edit_name" 
                               name="name" 
                               required 
                               placeholder="Enter full name"
                               class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="edit_email">
                            <i class="fas fa-envelope"></i> Email Address:
                        </label>
                        <input type="email" 
                               id="edit_email" 
                               name="email" 
                               required 
                               placeholder="Enter email address"
                               class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="edit_password">
                            <i class="fas fa-lock"></i> Password:
                        </label>
                        <div class="password-input-group">
                            <input type="password" 
                                   id="edit_password" 
                                   name="password" 
                                   placeholder="Leave blank to keep current password"
                                   class="form-input">
                            <small class="password-hint">Only fill this if you want to change the password</small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_role">
                            <i class="fas fa-user-tag"></i> User Role:
                        </label>
                        <select id="edit_role" name="role" required class="form-input">
                            <option value="">Select a role</option>
                            <option value="admin">Administrator</option>
                            <option value="teacher">Teacher</option>
                            <option value="student">Student</option>
                        </select>
                    </div>
                    <div class="modal-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update User
                        </button>
                        <button type="button" onclick="hideEditUserModal()" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="manage-users">
        <?php if(session()->has('message')): ?>
            <div class="alert alert-success">
                <?= session('message') ?>
            </div>
        <?php endif; ?>

        <?php if(session()->has('error')): ?>
            <div class="alert alert-danger">
                <?= session('error') ?>
            </div>
        <?php endif; ?>

<h2>User Management</h2>

<div class="cards">
    <div class="card">
        <h2><?= esc($count_admin ?? 0) ?></h2>
        <p>Administrators</p>
    </div>
    <div class="card">
        <h2><?= esc($count_teacher ?? 0) ?></h2>
        <p>Teachers</p>
    </div>
    <div class="card">
        <h2><?= esc($count_student ?? 0) ?></h2>
        <p>Students</p>
    </div>
    <div class="card">
        <h2><?= esc($count_total ?? 0) ?></h2>
        <p>Total Users</p>
    </div>
</div>

        <table>
    <thead>
        <tr>
            <th style="width: 5%;">#ID</th>
            <th style="width: 20%;">Full Name</th>
            <th style="width: 25%;">Email Address</th>
            <th style="width: 15%;">Role</th>
            <th style="width: 12%;">Created On</th>
            <th style="width: 12%;">Last Updated</th>
            <th style="width: 11%;">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($users) && is_array($users)): ?>
            <?php foreach ($users as $user): ?>
            <tr>
                <td class="text-center"><?= esc($user['id']) ?></td>
                <td>
                    <div class="user-info">
                        <strong class="user-name"><?= esc($user['name']) ?></strong>
                    </div>
                </td>
                <td>
                    <div class="email-info">
                        <i class="fas fa-envelope"></i>
                        <a href="mailto:<?= esc($user['email']) ?>"><?= esc($user['email']) ?></a>
                    </div>
                </td>
                <td>
                    <?php 
                    $roleColors = [
                        'admin' => '#dc3545',    // Red for admin
                        'teacher' => '#ffc107',  // Yellow for teacher
                        'student' => '#007bff'   // Blue for student
                    ];
                    $roleColor = $roleColors[$user['role']] ?? '#6c757d';
                    ?>
                    <span class="role-badge" style="background-color: <?= $roleColor ?>">
                        <?= strtoupper(esc($user['role'])) ?>
                    </span>
                </td>
                <td class="text-center"><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                <td class="text-center"><?= date('M d, Y', strtotime($user['updated_at'])) ?></td>
                <td class="actions">
                    <button onclick="showEditUserModal('<?= $user['id'] ?>', '<?= $user['name'] ?>', '<?= $user['email'] ?>', '<?= $user['role'] ?>')" class="btn edit-btn">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button onclick="deleteUser('<?= $user['id'] ?>')" class="btn delete-btn">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="7" class="text-center">No users found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

    <script>
        // Add User Modal Functions
        function showAddUserModal() {
            document.getElementById('addUserModal').style.display = 'block';
        }

        function hideAddUserModal() {
            document.getElementById('addUserModal').style.display = 'none';
        }

        // Edit User Modal Functions
        function showEditUserModal(id, name, email, role) {
            document.getElementById('editUserModal').style.display = 'block';
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_role').value = role;
            document.getElementById('editUserForm').action = '<?= site_url('admin/users/update/') ?>' + id;
        }

        function hideEditUserModal() {
            document.getElementById('editUserModal').style.display = 'none';
        }

        // Delete User Function
        function deleteUser(id) {
            if (confirm('Are you sure you want to delete this user?')) {
                window.location.href = '<?= site_url('admin/users/delete/') ?>' + id;
            }
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            if (event.target == document.getElementById('addUserModal')) {
                hideAddUserModal();
            }
            if (event.target == document.getElementById('editUserModal')) {
                hideEditUserModal();
            }
        }
    </script>
</body>
</html>
