<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['file_id'])) {
    $file_id = $_GET['file_id'];
    
    // Fetch file details from database
    $stmt = $pdo->prepare("SELECT * FROM course_materials WHERE id = ?");
    $stmt->execute([$file_id]);
    $file = $stmt->fetch();
    
    if ($file) {
        $filepath = $file['file_path'];
        
        if (file_exists($filepath)) {
            // Set headers for download
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
            header('Content-Length: ' . filesize($filepath));
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            
            // Clear output buffer
            ob_clean();
            flush();
            
            // Read file and output
            readfile($filepath);
            exit();
        }
    }
}

header('Location: student_materials.php');
exit();
?>