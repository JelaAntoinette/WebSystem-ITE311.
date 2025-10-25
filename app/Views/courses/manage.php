<h2>Manage Courses</h2>
<?php foreach ($courses as $course): ?>
    <p><?= esc($course['name']) ?></p>
<?php endforeach; ?>
<h1>Manage Courses</h1>

<?php if(!empty($courses)): ?>
    <ul>
        <?php foreach($courses as $course): ?>
            <li><?= esc($course['name']) ?> - <?= esc($course['description']) ?></li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No courses available.</p>
<?php endif; ?>
