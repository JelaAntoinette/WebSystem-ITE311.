<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Manage Courses') ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body { 
            font-family: 'Segoe UI', Arial, sans-serif; 
            background: #f0f2f5; 
            min-height: 100vh;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px 40px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header-title {
            font-size: 28px;
            font-weight: 600;
        }
        .header-actions {
            display: flex;
            gap: 15px;
        }
        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            color: white;
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .add-btn {
            background: #28a745;
        }
        .add-btn:hover {
            background: #218838;
            transform: translateY(-2px);
        }
        .back-btn {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
        }
        .back-btn:hover {
            background: rgba(255,255,255,0.3);
        }
        .container {
            max-width: 1400px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #667eea;
        }
        .stat-label {
            color: #6c757d;
            margin-top: 5px;
            font-size: 14px;
        }
        .courses-table-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table thead {
            background: #667eea;
            color: white;
        }
        .table th,
        .table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        .table th {
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .table tbody tr {
            transition: background 0.2s;
        }
        .table tbody tr:hover {
            background: #f8f9fa;
        }
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-active {
            background: #d4edda;
            color: #155724;
        }
        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }
        .status-completed {
            background: #cce5ff;
            color: #004085;
        }
        .action-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            margin-right: 5px;
            transition: all 0.2s;
        }
        .edit-btn {
            background: #17a2b8;
            color: white;
        }
        .edit-btn:hover {
            background: #138496;
        }
        .delete-btn {
            background: #dc3545;
            color: white;
        }
        .delete-btn:hover {
            background: #c82333;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            overflow-y: auto;
        }
        .modal-content {
            background: white;
            margin: 50px auto;
            padding: 0;
            width: 90%;
            max-width: 700px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
        }
        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 30px;
            border-radius: 10px 10px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-header h2 {
            margin: 0;
            font-size: 24px;
        }
        .close {
            color: white;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .close:hover {
            transform: scale(1.2);
        }
        .modal-body {
            padding: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ced4da;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
        }
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .submit-btn {
            background: #28a745;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s;
        }
        .submit-btn:hover {
            background: #218838;
            transform: translateY(-2px);
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }
        .empty-state i {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.5;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-title">
            <i class="fas fa-book"></i> Manage Courses
        </div>
        <div class="header-actions">
            <button class="btn add-btn" onclick="openAddModal()">
                <i class="fas fa-plus"></i> Add New Course
            </button>
            <a href="/admin/dashboard" class="btn back-btn">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <div class="container">
        <?php if(session()->getFlashdata('message')): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?= session()->getFlashdata('message') ?>
            </div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('warning')): ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                <?= session()->getFlashdata('warning') ?>
            </div>
        <?php endif; ?>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value"><?= count($courses ?? []) ?></div>
                <div class="stat-label">Total Courses</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?= count(array_filter($courses ?? [], fn($c) => ($c['status'] ?? '') === 'active')) ?></div>
                <div class="stat-label">Active Courses</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?= count(array_filter($courses ?? [], fn($c) => ($c['status'] ?? '') === 'completed')) ?></div>
                <div class="stat-label">Completed Courses</div>
            </div>
        </div>

        <div class="courses-table-container">
            <?php if(empty($courses)): ?>
                <div class="empty-state">
                    <i class="fas fa-book"></i>
                    <h3>No courses yet</h3>
                    <p>Click "Add New Course" to create your first course</p>
                </div>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Course Name</th>
                            <th>Subject Code</th>
                            <th>Instructor</th>
                            <th>Year Level</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($courses as $course): ?>
                        <tr>
                            <td><?= esc($course['id']) ?></td>
                            <td><strong><?= esc($course['course_name']) ?></strong></td>
                            <td><?= esc($course['subject_code'] ?? 'N/A') ?></td>
                            <td><?= esc($course['instructor_name'] ?? 'Not Assigned') ?></td>
                            <td><?= esc($course['year_level'] ?? 'N/A') ?></td>
                            <td>
                                <span class="status-badge status-<?= esc($course['status'] ?? 'active') ?>">
                                    <?= esc($course['status'] ?? 'active') ?>
                                </span>
                            </td>
                            <td>
                                <button class="action-btn edit-btn" onclick='openEditModal(<?= json_encode($course) ?>)'>
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="action-btn delete-btn" onclick="deleteCourse(<?= $course['id'] ?>)">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <!-- Add Course Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-plus-circle"></i> Add New Course</h2>
                <span class="close" onclick="closeAddModal()">&times;</span>
            </div>
            <div class="modal-body">
                <form action="/admin/courses/store" method="POST">
                    <div class="form-group">
                        <label for="course_name">Course Name *</label>
                        <input type="text" id="course_name" name="course_name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="subject_code">Subject Code</label>
                        <input type="text" id="subject_code" name="subject_code" placeholder="e.g., ITE311, CS201" maxlength="20">
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" placeholder="Course description..."></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="instructor_name">Instructor Name</label>
                            <select id="instructor_name" name="instructor_name">
                                <option value="">Select Instructor</option>
                                <?php foreach($teachers ?? [] as $teacher): ?>
                                    <option value="<?= esc($teacher['name']) ?>"><?= esc($teacher['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="year_level">Year Level</label>
                            <select id="year_level" name="year_level">
                                <option value="">Select Year Level</option>
                                <option value="1st Year">1st Year</option>
                                <option value="2nd Year">2nd Year</option>
                                <option value="3rd Year">3rd Year</option>
                                <option value="4th Year">4th Year</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="date_started">Date Started</label>
                            <input type="date" id="date_started" name="date_started" min="<?= date('Y-m-d') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="date_ended">Date Ended</label>
                            <input type="date" id="date_ended" name="date_ended" min="<?= date('Y-m-d') ?>">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="year_started">Year Started</label>
                            <input type="number" id="year_started" name="year_started" min="<?= date('Y') ?>" max="2100" placeholder="<?= date('Y') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="year_ended">Year Ended</label>
                            <input type="number" id="year_ended" name="year_ended" min="<?= date('Y') ?>" max="2100" placeholder="<?= intval(date('Y')) + 1 ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Status *</label>
                        <select id="status" name="status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-save"></i> Add Course
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Course Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-edit"></i> Edit Course</h2>
                <span class="close" onclick="closeEditModal()">&times;</span>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST">
                    <input type="hidden" id="edit_id" name="id">
                    
                    <div class="form-group">
                        <label for="edit_course_name">Course Name *</label>
                        <input type="text" id="edit_course_name" name="course_name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_subject_code">Subject Code</label>
                        <input type="text" id="edit_subject_code" name="subject_code" placeholder="e.g., ITE311, CS201" maxlength="20">
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_description">Description</label>
                        <textarea id="edit_description" name="description" placeholder="Course description..."></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_instructor_name">Instructor Name</label>
                            <select id="edit_instructor_name" name="instructor_name">
                                <option value="">Select Instructor</option>
                                <?php foreach($teachers ?? [] as $teacher): ?>
                                    <option value="<?= esc($teacher['name']) ?>"><?= esc($teacher['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_year_level">Year Level</label>
                            <select id="edit_year_level" name="year_level">
                                <option value="">Select Year Level</option>
                                <option value="1st Year">1st Year</option>
                                <option value="2nd Year">2nd Year</option>
                                <option value="3rd Year">3rd Year</option>
                                <option value="4th Year">4th Year</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_date_started">Date Started</label>
                            <input type="date" id="edit_date_started" name="date_started">
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_date_ended">Date Ended</label>
                            <input type="date" id="edit_date_ended" name="date_ended">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_year_started">Year Started</label>
                            <input type="number" id="edit_year_started" name="year_started" min="2000" max="2100">
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_year_ended">Year Ended</label>
                            <input type="number" id="edit_year_ended" name="year_ended" min="2000" max="2100">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_status">Status *</label>
                        <select id="edit_status" name="status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-save"></i> Update Course
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Add Modal Functions
        function openAddModal() {
            document.getElementById('addModal').style.display = 'block';
        }
        
        function closeAddModal() {
            document.getElementById('addModal').style.display = 'none';
        }
        
        // Edit Modal Functions
        function openEditModal(course) {
            document.getElementById('editModal').style.display = 'block';
            document.getElementById('editForm').action = '/admin/courses/update/' + course.id;
            document.getElementById('edit_id').value = course.id;
            document.getElementById('edit_course_name').value = course.course_name;
            document.getElementById('edit_subject_code').value = course.subject_code || '';
            document.getElementById('edit_description').value = course.description || '';
            document.getElementById('edit_instructor_name').value = course.instructor_name || '';
            document.getElementById('edit_year_level').value = course.year_level || '';
            document.getElementById('edit_date_started').value = course.date_started || '';
            document.getElementById('edit_date_ended').value = course.date_ended || '';
            document.getElementById('edit_year_started').value = course.year_started || '';
            document.getElementById('edit_year_ended').value = course.year_ended || '';
            document.getElementById('edit_status').value = course.status || 'active';
        }
        
        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }
        
        // Delete Course Function
        function deleteCourse(id) {
            if (confirm('Are you sure you want to delete this course? This action cannot be undone.')) {
                window.location.href = '/admin/courses/delete/' + id;
            }
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const addModal = document.getElementById('addModal');
            const editModal = document.getElementById('editModal');
            if (event.target == addModal) {
                closeAddModal();
            }
            if (event.target == editModal) {
                closeEditModal();
            }
        }
    </script>
</body>
</html>
