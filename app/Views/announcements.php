<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        h1 { color: #333; }
        .announcement {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .date {
            color: gray;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <h1>Announcements</h1>

    <?php if (!empty($announcements)): ?>
        <?php foreach ($announcements as $row): ?>
            <div class="announcement">
                <h2><?= esc($row['title']) ?></h2>
                <p><?= esc($row['content']) ?></p>
                <p class="date">Posted on: <?= esc($row['date_posted']) ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No announcements available.</p>
    <?php endif; ?>
</body>
</html>
