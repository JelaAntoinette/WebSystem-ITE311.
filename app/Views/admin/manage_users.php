<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title) ?></title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8f9fa; }
        .header { background: #dc3545; color: white; padding: 15px; font-size: 20px; display: flex; justify-content: space-between; align-items: center; }
        .cards { display: flex; justify-content: space-around; margin: 20px 0; }
        .card { background: white; padding: 20px; border-radius: 10px; text-align: center; width: 20%; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .card h2 { margin: 10px 0; }
        table { width: 90%; margin: 0 auto; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; }
        th, td { padding: 12px; text-align: center; border-bottom: 1px solid #ddd; }
        th { background: #343a40; color: white; }
        tr:hover { background: #f1f1f1; }
        .btn { padding: 6px 12px; border-radius: 5px; text-decoration: none; color: white; }
        .edit { background: #007bff; }
        .delete { background: #dc3545; }
        .role { padding: 4px 10px; border-radius: 15px; color: white; font-weight: bold; font-size: 13px; }
        .student { background: #007bff; }
        .teacher { background: #ffc107; color: black; }
        .admin { background: #dc3545; }
        .add-btn { background: #28a745; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; margin-right: 30px; }
        .logout-btn { background: #ff4444; color: white; padding: 10px 15px; border-radius: 6px; text-decoration: none; }
        .actions { display: flex; justify-content: center; gap: 8px; }
    </style>
</head>
<body>

<div class="header">
    <div>ITE311 FUNDAR LMS - Admin Panel</div>
    <div>
        <a href="/admin/users/create" class="add-btn">Add New User</a>
        <a href="/logout" class="logout-btn">Logout</a>
    </div>
</div>

<h2 style="text-align:center; margin-top:20px;">User Management</h2>

<div class="cards">
    <div class="card">
        <h2><?= $count_admin ?></h2>
        <p>Administrators</p>
    </div>
    <div class="card">
        <h2><?= $count_teacher ?></h2>
        <p>Teachers</p>
    </div>
    <div class="card">
        <h2><?= $count_student ?></h2>
        <p>Students</p>
    </div>
    <div class="card">
        <h2><?= $count_total ?></h2>
        <p>Total Users</p>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Created Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($users as $user): ?>
        <tr>
            <td><?= esc($user['full_name']) ?></td>
            <td><?= esc($user['email']) ?></td>
            <td>
                <span class="role <?= esc($user['role']) ?>">
                    <?= strtoupper($user['role']) ?>
                </span>
            </td>
            <td><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
            <td class="actions">
                <a href="/admin/users/edit/<?= $user['id'] ?>" class="btn edit">Edit</a>
                <a href="/admin/users/delete/<?= $user['id'] ?>" class="btn delete" onclick="return confirm('Delete this user?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
