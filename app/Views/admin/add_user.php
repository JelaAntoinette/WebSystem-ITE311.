<form method="post" action="/admin/users/store" style="width:50%;margin:auto;">
    <h2>Add New User</h2>
    <label>Full Name</label><br>
    <input type="text" name="full_name" required><br><br>

    <label>Email</label><br>
    <input type="email" name="email" required><br><br>

    <label>Password</label><br>
    <input type="password" name="password" required><br><br>

    <label>Role</label><br>
    <select name="role">
        <option value="student">Student</option>
        <option value="teacher">Teacher</option>
        <option value="admin">Admin</option>
    </select><br><br>

    <button type="submit">Save</button>
</form>
