<?php namespace App\Controllers;

    class TestController extends BaseController
{
    public function testDb()
    {
        try {
            $db = \Config\Database::connect();
            
            echo "Database connection successful!<br>";
            echo "Connected to: " . $db->database . "<br><br>";
            
            echo "Testing users table...<br>";
            $query = $db->query("SHOW TABLES LIKE 'users'");
            $tableExists = $query->getResultArray();
            
            if (empty($tableExists)) {
                echo "Users table does not exist!<br>";
                return;
            }
            
            echo "Users table exists. Fetching records...<br><br>";
            
            $query = $db->query("SELECT * FROM users");
            $results = $query->getResultArray();
            
            echo "Found " . count($results) . " users:<br><br>";
            echo "<pre>";
            print_r($results);
            echo "</pre>";
            
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
            echo "<br>Error details:<br>";
            echo "<pre>";
            print_r($e->getTraceAsString());
            echo "</pre>";
        }
    }

    public function createTestNotification()
    {
        try {
            $db = \Config\Database::connect();
            $session = session();
            
            // Get current logged-in user ID
            $userId = $session->get('userID');
            
            if (!$userId) {
                echo "Error: No user is logged in.<br>";
                echo "Please log in first and then visit this page again.";
                return;
            }
            
            // Create test notification
            $data = [
                'user_id' => $userId,
                'message' => 'Test notification created at ' . date('Y-m-d H:i:s'),
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $inserted = $db->table('notifications')->insert($data);
            
            if ($inserted) {
                echo "✅ Test notification created successfully!<br>";
                echo "User ID: " . $userId . "<br>";
                echo "Message: " . $data['message'] . "<br><br>";
                echo '<a href="' . base_url('dashboard') . '">Go to Dashboard</a> to see the notification.';
            } else {
                echo "❌ Failed to create notification.<br>";
                echo "Error: " . print_r($db->error(), true);
            }
            
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
