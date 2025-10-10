<form method="post" action="/admin/users/update/<?= $user['id'] ?>" style="width:50%;margin:auto;">
    <h2>Edit User</h2>
    <label>Full Name</label><br>
    <input type="text" name="full_name" value="<?= esc($user['full_name']) ?>" required><br><br>

    <label>Email</label><br>
    <input type="email" name="email" value="<?= esc($user['email']) ?>" required><br><br>

    <label>New Password (optional)</label><br>
    <input type="password" name="password"><br><br>

    <label>Role</label><br>
    <select name="role">
        <option value="student" <?= $user['role']=='student'?'selected':'' ?>>Student</option>
        <option value="teacher" <?= $user['role']=='teacher'?'selected':'' ?>>Teacher</option>
        <option value="admin" <?= $user['role']=='admin'?'selected':'' ?>>Admin</option>
    </select><br><br>

    <button type="submit">Update</button>
</form>
