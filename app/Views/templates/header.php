<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= base_url('dashboard') ?>">LMS</a>
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

                <!-- ✅ Notification Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="notifDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Notifications
                        <span id="notif-badge" class="badge bg-danger" style="display: none; margin-left:6px;">0</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end p-2" aria-labelledby="notifDropdown" style="min-width: 300px;">
                        <div id="notifications-container">
                            <p class="text-muted small">Loading...</p>
                        </div>
                        <div class="dropdown-divider"></div>
                      <a class="dropdown-item text-center small" href="<?= base_url('notifications/all'); ?>">View all</a>

                    </ul>
                </li>
                <!-- ✅ End Notification Dropdown -->

            </ul>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- ✅ Temporary JS (will be replaced by AJAX but keeping it for now) -->
<script>
    let unreadCount = 3; // temporary demo
    if (unreadCount > 0) {
        document.getElementById('notif-badge').textContent = unreadCount;
        document.getElementById('notif-badge').style.display = 'inline-block';
        document.getElementById('notifications-container').innerHTML = `
            <div class="small">You have ${unreadCount} unread notifications.</div>
        `;
    }
</script>

<!-- ✅ REAL Step-6 Notification Auto-Refresh Script inserted here -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
function loadNotifications() {
    $.ajax({
        url: "<?= base_url('/notifications/fetch') ?>",
        method: "GET",
        dataType: "json",
        success: function(data) {
            let badge = $("#notif-badge");
            let container = $("#notifications-container");

            if (data.success && data.unread_count > 0) {
                badge.text(data.unread_count).show();
            } else {
                badge.hide();
            }

            if (data.success && data.notifications && data.notifications.length > 0) {
                container.html('');
                data.notifications.forEach(function(n) {
                    container.append(`
                        <div class="d-flex justify-content-between mb-2 p-2 border-bottom">
                            <small>${n.message}</small>
                            <button onclick="markRead(${n.id})" class="btn btn-sm btn-outline-primary">✓</button>
                        </div>
                    `);
                });
            } else {
                container.html(`<p class="text-muted small text-center m-2">No new notifications</p>`);
            }
        },
        error: function(xhr, status, error) {
            console.error('Notification fetch error:', error);
            $("#notifications-container").html(`<p class="text-danger small m-2">Error loading notifications</p>`);
        }
    });
}

function markRead(id) {
    $.post("<?= base_url('/notifications/mark-read/') ?>" + id, function() {
        loadNotifications(); 
    });
}

$(document).ready(function () {
    loadNotifications();
    setInterval(loadNotifications, 60000); // auto-refresh every minute
});
</script>
<!-- ✅ End of script -->
