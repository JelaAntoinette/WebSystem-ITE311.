<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Course Materials</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .file-icon {
            font-size: 1.5rem;
        }
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }
        .stats-card {
            border-left: 4px solid;
        }
        .stats-card.primary {
            border-left-color: #0d6efd;
        }
        .stats-card.success {
            border-left-color: #198754;
        }
        .stats-card.info {
            border-left-color: #0dcaf0;
        }
    </style>
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-primary">
            <i class="bi bi-folder2-open"></i> Course Materials
        </h3>
        <a href="<?= site_url('student/dashboard') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
    </div>

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

    <?php if (!empty($course_name)): ?>
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title text-secondary mb-0">
                    <i class="bi bi-book"></i> Course: <?= esc($course_name) ?>
                </h5>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($materials)): ?>
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card stats-card primary shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-files text-primary" style="font-size: 2.5rem;"></i>
                        <h3 class="mt-2 mb-0"><?= count($materials) ?></h3>
                        <p class="text-muted mb-0">Total Files</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stats-card success shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-hdd text-success" style="font-size: 2.5rem;"></i>
                        <h3 class="mt-2 mb-0">
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
                        </h3>
                        <p class="text-muted mb-0">Total Size</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stats-card info shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-clock-history text-info" style="font-size: 2.5rem;"></i>
                        <h3 class="mt-2 mb-0">
                            <?php 
                            if (!empty($materials)) {
                                echo date('M d, Y', strtotime($materials[0]['created_at']));
                            }
                            ?>
                        </h3>
                        <p class="text-muted mb-0">Last Updated</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-files"></i> Available Materials (<?= count($materials) ?>)
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="table-primary">
                            <tr>
                                <th width="5%">#</th>
                                <th width="5%" class="text-center"><i class="bi bi-file-earmark"></i></th>
                                <th width="45%">File Name</th>
                                <th width="15%">File Size</th>
                                <th width="20%">Uploaded On</th>
                                <th width="10%">Action</th>
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
                                        <small>
                                            <i class="bi bi-calendar3"></i> <?= date('F d, Y', strtotime($material['created_at'])) ?>
                                            <br>
                                            <i class="bi bi-clock"></i> <?= date('h:i A', strtotime($material['created_at'])) ?>
                                        </small>
                                    </td>
                                    <td>
                                        <a href="<?= site_url('materials/download/' . $material['id']) ?>" 
                                           class="btn btn-sm btn-success"
                                           title="Download this file">
                                            <i class="bi bi-download"></i> Download
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-inbox text-muted" style="font-size: 5rem;"></i>
                <h4 class="text-muted mt-4">No materials available for this course</h4>
                <p class="text-muted">Check back later for course materials and resources.</p>
                <a href="<?= site_url('student/dashboard') ?>" class="btn btn-primary mt-3">
                    <i class="bi bi-arrow-left"></i> Go to Dashboard
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>