<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - ITE311 Project</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            padding: 0;
        }
        
        nav { 
            background: #f4f4f4; 
            padding: 15px 40px; 
            margin-bottom: 20px;
            display: flex;
            justify-content: flex-end;
        }
        
        nav a { 
            margin-left: 20px; 
            text-decoration: none; 
            color: #333; 
            font-weight: 500;
        }
        
        nav a:hover { 
            color: #007bff; 
        }
        
        .content {
            text-align: center;
            padding: 40px;
        }
        
        h1 { 
            color: #333; 
            margin-bottom: 30px;
        }
        
        p {
            max-width: 600px;
            margin: 20px auto;
            line-height: 1.6;
            color: #555;
        }
    </style>
</head>
<body>
   <nav>
        <a href="<?= base_url('/') ?>">Home</a>
        <a href="<?= base_url('/about') ?>">About</a>
        <a href="<?= base_url('/contact') ?>">Contact</a>
        <a href="<?= base_url('/login') ?>">Login</a>
    </nav>
    
    <div class="content">
        <h1>Contact Us</h1>
        <p>This is the contact page of my CodeIgniter project.</p>
        <p>You can reach us through this page for any inquiries.</p>
    </div>
</body>
</html>