<nav class="navbar">
    <div class="navbar-container">
        <a class="navbar-brand" href="<?= base_url('dashboard') ?>">LMS</a>
        <div class="navbar-links">
            <span class="navbar-user">Welcome, <?= esc($user['name']) ?></span>

            <!-- Role-specific navigation -->
            <?php if ($user['role'] === 'admin'): ?>
                <a href="<?= base_url('/users') ?>">Manage Users</a>
                <a href="<?= base_url('/reports') ?>">Reports</a>
            <?php elseif ($user['role'] === 'teacher'): ?>
                <a href="<?= base_url('/classes') ?>">My Classes</a>
            <?php else: ?>
                <a href="<?= base_url('/courses') ?>">My Courses</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
