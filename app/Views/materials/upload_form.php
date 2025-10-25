<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Material</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .file-icon {
            font-size: 1.5rem;
        }
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="bi bi-cloud-upload"></i> Upload Course Material</h4>
        </div>
        <div class="card-body">
            
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form action="<?= site_url('materials/upload/' . $course_id) ?>" 
                  method="post" 
                  enctype="multipart/form-data">

                <div class="mb-3">
                    <label for="material_file" class="form-label">
                        <i class="bi bi-file-earmark-arrow-up"></i> Choose File:
                    </label>
                    <input type="file" name="material_file" id="material_file" class="form-control" required>
                    <div class="form-text">
                        Allowed formats: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG (Max: 5MB)
                    </div>
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="bi bi-upload"></i> Upload
                </button>
                <a href="<?= site_url('courses/manage') ?>" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </form>

        </div>
    </div>

    <!-- Display Uploaded Materials Section -->
    <?php if (!empty($materials)): ?>
    <div class="card shadow-sm mt-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="bi bi-files"></i> Uploaded Materials (<?= count($materials) ?>)</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">#</th>
                            <th width="5%" class="text-center"><i class="bi bi-file-earmark"></i></th>
                            <th width="40%">File Name</th>
                            <th width="15%">Upload Date</th>
                            <th width="15%">File Size</th>
                            <th width="20%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; foreach ($materials as $material): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td class="text-center">
                                <?php 
                                $extension = pathinfo($material['file_name'], PATHINFO_EXTENSION);
                                $iconClass = 'bi-file-earmark';
                                $iconColor = 'text-secondary';
                                
                                switch(strtolower($extension)) {
                                    case 'pdf':
                                        $iconClass = 'bi-file-earmark-pdf';
                                        $iconColor = 'text-danger';
                                        break;
                                    case 'doc':
                                    case 'docx':
                                        $iconClass = 'bi-file-earmark-word';
                                        $iconColor = 'text-primary';
                                        break;
                                    case 'xls':
                                    case 'xlsx':
                                        $iconClass = 'bi-file-earmark-excel';
                                        $iconColor = 'text-success';
                                        break;
                                    case 'ppt':
                                    case 'pptx':
                                        $iconClass = 'bi-file-earmark-ppt';
                                        $iconColor = 'text-warning';
                                        break;
                                    case 'zip':
                                    case 'rar':
                                        $iconClass = 'bi-file-earmark-zip';
                                        $iconColor = 'text-info';
                                        break;
                                    case 'jpg':
                                    case 'jpeg':
                                    case 'png':
                                    case 'gif':
                                        $iconClass = 'bi-file-earmark-image';
                                        $iconColor = 'text-info';
                                        break;
                                    case 'txt':
                                        $iconClass = 'bi-file-earmark-text';
                                        break;
                                }
                                ?>
                                <i class="bi <?= $iconClass ?> <?= $iconColor ?> file-icon"></i>
                            </td>
                            <td>
                                <strong><?= esc($material['file_name']) ?></strong>
                                <br>
                                <small class="text-muted">
                                    <i class="bi bi-tag"></i> .<?= strtoupper($extension) ?> file
                                </small>
                            </td>
                            <td>
                                <small>
                                    <i class="bi bi-calendar3"></i> <?= date('M d, Y', strtotime($material['created_at'])) ?>
                                    <br>
                                    <i class="bi bi-clock"></i> <?= date('h:i A', strtotime($material['created_at'])) ?>
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">
                                    <?php 
                                    if (isset($material['file_size'])) {
                                        $size = $material['file_size'];
                                        if ($size < 1024) {
                                            echo $size . ' B';
                                        } elseif ($size < 1024 * 1024) {
                                            echo number_format($size / 1024, 2) . ' KB';
                                        } else {
                                            echo number_format($size / (1024 * 1024), 2) . ' MB';
                                        }
                                    } else {
                                        echo 'N/A';
                                    }
                                    ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?= site_url('materials/download/' . $material['id']) ?>" 
                                   class="btn btn-sm btn-primary"
                                   title="Download this file">
                                    <i class="bi bi-download"></i> Download
                                </a>
                                <a href="<?= site_url('materials/delete/' . $material['id'] . '/' . $course_id) ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Are you sure you want to delete this material?')"
                                   title="Delete this file">
                                    <i class="bi bi-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-light">
            <div class="row text-center">
                <div class="col-md-4">
                    <i class="bi bi-files text-primary" style="font-size: 1.5rem;"></i>
                    <p class="mb-0"><strong><?= count($materials) ?></strong> Files</p>
                </div>
                <div class="col-md-4">
                    <i class="bi bi-hdd text-success" style="font-size: 1.5rem;"></i>
                    <p class="mb-0">
                        <strong>
                            <?php 
                            $totalSize = 0;
                            foreach ($materials as $material) {
                                if (isset($material['file_size'])) {
                                    $totalSize += $material['file_size'];
                                }
                            }
                            if ($totalSize < 1024) {
                                echo $totalSize . ' B';
                            } elseif ($totalSize < 1024 * 1024) {
                                echo number_format($totalSize / 1024, 2) . ' KB';
                            } else {
                                echo number_format($totalSize / (1024 * 1024), 2) . ' MB';
                            }
                            ?>
                        </strong> Total
                    </p>
                </div>
                <div class="col-md-4">
                    <i class="bi bi-clock-history text-info" style="font-size: 1.5rem;"></i>
                    <p class="mb-0">
                        <strong>
                            <?php 
                            if (!empty($materials)) {
                                echo date('M d, Y', strtotime($materials[0]['created_at']));
                            }
                            ?>
                        </strong> Last Upload
                    </p>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="card shadow-sm mt-4">
        <div class="card-body text-center py-5">
            <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
            <h5 class="text-muted mt-3">No materials uploaded yet</h5>
            <p class="text-muted">Upload your first course material using the form above.</p>
        </div>
    </div>
    <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>