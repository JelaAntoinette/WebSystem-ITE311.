<?= $this->include('templates/header'); ?> <!-- ✅ Loads your navbar/header -->

<div class="container mt-4">
    <h3 class="mb-3">All Notifications</h3>

    <?php if (!empty($notifications)) : ?>
        <ul class="list-group">
            <?php foreach($notifications as $n): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span><?= esc($n['message']); ?></span>
                    <small class="text-muted"><?= esc($n['created_at']); ?></small>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p class="text-muted">No notifications found.</p>
    <?php endif; ?>

    <div class="mt-3">
        <a href="<?= base_url('dashboard'); ?>" class="btn btn-primary btn-sm">Back to Dashboard</a>
    </div>
</div>
