<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= base_url('dashboard') ?>">My Website</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-label="Toggle">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav ms-auto">
                <?php if ($user['role'] === 'admin'): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('/users') ?>">Manage Users</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('/reports') ?>">Reports</a></li>
                <?php elseif ($user['role'] === 'teacher'): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('/classes') ?>">My Classes</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('/courses') ?>">My Courses</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
