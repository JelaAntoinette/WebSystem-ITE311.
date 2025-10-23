<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Material</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Upload Course Material</h4>
        </div>
        <div class="card-body">
            
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <form action="<?= site_url('materials/upload/' . $course_id) ?>" 
                  method="post" 
                  enctype="multipart/form-data">

                <div class="mb-3">
                    <label for="material_file" class="form-label">Choose File:</label>
                    <input type="file" name="material_file" id="material_file" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-success">Upload</button>
                <a href="<?= site_url('courses/manage') ?>" class="btn btn-secondary">Back</a>
            </form>

        </div>
    </div>
</div>

</body>
</html>
