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
}
