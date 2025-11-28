<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Materials - Admin Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .navbar {
            background: #800dcdff;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .navbar h2 {
            color: white;
            font-size: 24px;
        }

        .nav-links {
            display: flex;
            gap: 20px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .nav-links a:hover, .nav-links a.active {
            background: rgba(255,255,255,0.2);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .header-section {
            background: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .header-section h1 {
            color: #764ba2;
            margin-bottom: 10px;
            font-size: 32px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card h3 {
            color: #667eea;
            font-size: 36px;
            margin-bottom: 10px;
        }

        .stat-card p {
            color: #666;
            font-size: 14px;
        }

        .upload-section {
            background: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .upload-section h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 24px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: border 0.3s;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        .file-upload-area {
            border: 3px dashed #667eea;
            border-radius: 12px;
            padding: 40px;
            text-align: center;
            background: #f8f9ff;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
        }

        .file-upload-area:hover {
            background: #eef0ff;
            border-color: #764ba2;
        }

        .file-upload-area input[type="file"] {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            opacity: 0;
            cursor: pointer;
        }

        .file-upload-icon {
            font-size: 48px;
            color: #667eea;
            margin-bottom: 10px;
        }

        .file-info {
            margin-top: 15px;
            padding: 10px;
            background: #e8f5e9;
            border-radius: 8px;
            display: none;
        }

        .file-info.show {
            display: block;
        }

        .btn {
            padding: 14px 32px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .materials-section {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .materials-section h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 24px;
        }

        .search-bar {
            margin-bottom: 20px;
        }

        .search-bar input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
        }

        .materials-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .materials-table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .materials-table th {
            padding: 15px;
            text-align: left;
            color: white;
            font-weight: 600;
            font-size: 14px;
        }

        .materials-table td {
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 14px;
        }

        .materials-table tbody tr:hover {
            background: #f8f9ff;
        }

        .btn-small {
            padding: 8px 16px;
            font-size: 13px;
            margin-right: 5px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
        }

        .btn-download {
            background: #4caf50;
            color: white;
        }

        .btn-download:hover {
            background: #45a049;
        }

        .btn-delete {
            background: #f44336;
            color: white;
        }

        .btn-delete:hover {
            background: #da190b;
        }

        .btn-view {
            background: #2196F3;
            color: white;
        }

        .btn-view:hover {
            background: #0b7dda;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #f44336;
        }

        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .file-icon {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: 600;
            color: white;
            margin-right: 5px;
        }

        .file-icon.pdf { background: #f44336; }
        .file-icon.doc { background: #2196F3; }
        .file-icon.xls { background: #4caf50; }
        .file-icon.img { background: #ff9800; }
        .file-icon.video { background: #9c27b0; }
        .file-icon.other { background: #607d8b; }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .empty-state-icon {
            font-size: 64px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <div class="navbar">
        <h2> LMS Admin</h2>
        <div class="nav-links">
            <a href="<?= base_url('admin/dashboard') ?>">Dashboard</a>
            <a href="<?= base_url('admin/manage') ?>">Manage Users</a>
            <a href="<?= base_url('admin/materials') ?>" class="active">Materials</a>
            <a href="<?= base_url('reports') ?>">Reports</a>
        </div>
    </div>

    <div class="container">
        <!-- Alert Messages -->
        <div id="alertContainer"></div>

        <!-- Header -->
        <div class="header-section">
            <h1> Manage Course Materials</h1>
            <p style="color: #666; margin-top: 10px;">Upload, manage, and organize learning materials for all courses</p>
        </div>

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3 id="totalMaterials">0</h3>
                <p>Total Materials</p>
            </div>
            <div class="stat-card">
                <h3 id="totalSize">0 MB</h3>
                <p>Storage Used</p>
            </div>
            <div class="stat-card">
                <h3 id="pdfCount">0</h3>
                <p>PDF Documents</p>
            </div>
            <div class="stat-card">
                <h3 id="recentUploads">0</h3>
                <p>Recent Uploads (7 days)</p>
            </div>
        </div>

        <!-- Upload Section -->
        <div class="upload-section">
            <h2>Upload New Material</h2>
            
            <form id="uploadForm" enctype="multipart/form-data">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="courseSelect">Select Course *</label>
                        <select id="courseSelect" name="course_id" required>
                            <option value="">-- Select Course --</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="materialTitle">Material Title (Optional)</label>
                        <input type="text" id="materialTitle" name="title" placeholder="e.g., Week 1 - Introduction">
                    </div>
                </div>

                <div class="form-group">
                    <label for="materialDescription">Description (Optional)</label>
                    <textarea id="materialDescription" name="description" rows="3" placeholder="Brief description of the material"></textarea>
                </div>

                <div class="form-group">
                    <label>Upload File *</label>
                    <div class="file-upload-area" id="fileUploadArea">
                        <input type="file" id="materialFile" name="material_file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.zip,.mp4" required>
                        <div class="file-upload-icon"></div>
                        <p style="color: #667eea; font-weight: 600; margin-bottom: 5px;">Click to browse or drag and drop</p>
                        <p style="color: #999; font-size: 13px;">Supported: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ZIP, Images, MP4 (Max 10MB)</p>
                    </div>
                    <div class="file-info" id="fileInfo">
                        <strong>Selected file:</strong> <span id="fileName"></span> (<span id="fileSize"></span>)
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" id="uploadBtn">
                     Upload Material
                </button>
            </form>
        </div>

        <!-- Materials List -->
        <div class="materials-section">
            <h2>All Uploaded Materials</h2>
            
            <div class="search-bar">
                <input type="text" id="searchInput" placeholder=" Search materials by name, course, or uploader...">
            </div>

            <table class="materials-table">
                <thead>
                    <tr>
                        <th>File</th>
                        <th>Course</th>
                        <th>Size</th>
                        <th>Uploaded By</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="materialsTableBody">
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px;">
                            <div class="loading"></div>
                            <p style="margin-top: 10px; color: #999;">Loading materials...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        const BASE_URL = '<?= base_url() ?>';

        function showAlert(message, type) {
            const alertContainer = document.getElementById('alertContainer');
            const alert = document.createElement('div');
            alert.className = `alert alert-${type}`;
            alert.textContent = message;
            alertContainer.appendChild(alert);
            setTimeout(() => alert.remove(), 5000);
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }

        function getFileIcon(filename) {
            const ext = filename.split('.').pop().toLowerCase();
            const icons = {
                pdf: 'pdf', doc: 'doc', docx: 'doc',
                xls: 'xls', xlsx: 'xls',
                jpg: 'img', jpeg: 'img', png: 'img', gif: 'img',
                mp4: 'video', avi: 'video', mov: 'video',
            };
            return icons[ext] || 'other';
        }

        document.getElementById('materialFile').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                document.getElementById('fileName').textContent = file.name;
                document.getElementById('fileSize').textContent = formatFileSize(file.size);
                document.getElementById('fileInfo').classList.add('show');
            }
        });

        async function loadCourses() {
            try {
                const response = await fetch(`${BASE_URL}/materials/ajax/courses`);
                const data = await response.json();
                const select = document.getElementById('courseSelect');
                select.innerHTML = '<option value="">-- Select Course --</option>';
                if (data.success && data.courses) {
                    data.courses.forEach(course => {
                        const option = document.createElement('option');
                        option.value = course.id;
                        option.textContent = course.course_name;
                        select.appendChild(option);
                    });
                }
            } catch (error) {
                console.error('Error loading courses:', error);
            }
        }

        async function loadMaterials() {
            try {
                const response = await fetch(`${BASE_URL}/materials/ajax/all`);
                const data = await response.json();
                const tbody = document.getElementById('materialsTableBody');
                
                if (data.success && data.materials && data.materials.length > 0) {
                    tbody.innerHTML = data.materials.map(m => `
                        <tr>
                            <td>
                                <span class="file-icon ${getFileIcon(m.file_name)}">${getFileIcon(m.file_name).toUpperCase()}</span>
                                ${m.file_name}
                            </td>
                            <td>${m.course_name || 'N/A'}</td>
                            <td>${m.file_size_formatted}</td>
                            <td>${m.uploader_name || 'Unknown'}</td>
                            <td>${new Date(m.created_at).toLocaleDateString()}</td>
                            <td>
                                <button class="btn-small btn-view" onclick="viewMaterial(${m.id})"> View</button>
                                <button class="btn-small btn-download" onclick="downloadMaterial(${m.id})"> Download</button>
                                <button class="btn-small btn-delete" onclick="deleteMaterial(${m.id})"> Delete</button>
                            </td>
                        </tr>
                    `).join('');
                } else {
                    tbody.innerHTML = `<tr><td colspan="6"><div class="empty-state"><div class="empty-state-icon">📭</div><h3>No materials uploaded yet</h3></div></td></tr>`;
                }
            } catch (error) {
                console.error('Error loading materials:', error);
            }
        }

        async function loadStats() {
            try {
                const response = await fetch(`${BASE_URL}/materials/stats/dashboard`);
                const data = await response.json();
                if (data.success && data.stats) {
                    document.getElementById('totalMaterials').textContent = data.stats.total_materials;
                    document.getElementById('totalSize').textContent = data.stats.total_size_mb + ' MB';
                    document.getElementById('pdfCount').textContent = data.stats.type_breakdown.pdf || 0;
                    document.getElementById('recentUploads').textContent = data.stats.recent_uploads;
                }
            } catch (error) {
                console.error('Error loading stats:', error);
            }
        }

        document.getElementById('uploadForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const uploadBtn = document.getElementById('uploadBtn');
            const formData = new FormData(this);
            
            uploadBtn.disabled = true;
            uploadBtn.innerHTML = '⏳ Uploading... <span class="loading"></span>';
            
            try {
                const response = await fetch(`${BASE_URL}/materials/ajax-upload`, {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                
                if (data.success) {
                    showAlert('✅ Material uploaded successfully!', 'success');
                    this.reset();
                    document.getElementById('fileInfo').classList.remove('show');
                    loadMaterials();
                    loadStats();
                } else {
                    showAlert('Upload failed: ' + (data.message || 'Unknown error'), 'error');
                }
            } catch (error) {
                showAlert('Upload failed: Network error', 'error');
            } finally {
                uploadBtn.disabled = false;
                uploadBtn.innerHTML = 'Upload Material';
            }
        });

        function downloadMaterial(id) {
            window.location.href = `${BASE_URL}/materials/download/${id}`;
        }

        function viewMaterial(id) {
            window.open(`${BASE_URL}/materials/preview/${id}`, '_blank');
        }

        async function deleteMaterial(id) {
            if (!confirm('Are you sure you want to delete this material?')) return;
            try {
                await fetch(`${BASE_URL}/materials/delete/${id}`, { method: 'POST' });
                showAlert('✅ Material deleted successfully!', 'success');
                loadMaterials();
                loadStats();
            } catch (error) {
                showAlert('❌ Failed to delete material', 'error');
            }
        }

        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#materialsTableBody tr');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            loadCourses();
            loadMaterials();
            loadStats();
        });
    </script>
</body>
</html>