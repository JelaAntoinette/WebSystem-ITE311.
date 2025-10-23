<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Course Materials</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h3 class="mb-4 text-primary">Course Materials</h3>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <?php if (!empty($materials)): ?>
        <table class="table table-bordered table-hover">
            <thead class="table-primary">
                <tr>
                    <th>#</th>
                    <th>File Name</th>
                    <th>Uploaded On</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; foreach ($materials as $material): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= esc($material['file_name']) ?></td>
                        <td><?= date('F d, Y h:i A', strtotime($material['created_at'])) ?></td>
                        <td>
                            <a href="<?= site_url('materials/download/' . $material['id']) ?>" class="btn btn-sm btn-success">
                                Download
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">No materials available for this course.</div>
    <?php endif; ?>
</div>

</body>
</html>
