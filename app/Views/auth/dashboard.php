<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard - LMS' ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #8B5FBF 0%, #7A4FB0 50%, #8B5FBF 100%);
            min-height: 100vh;
            position: relative;
        }
        
        /* Background decoration */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(255,255,255,0.05) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
            z-index: 0;
        }
        
        /* Navigation */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .navbar-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .navbar-brand {
            color: #8B5FBF;
            font-size: 28px;
            font-weight: 700;
            text-decoration: none;
            letter-spacing: -1px;
        }
        
        .navbar-brand:hover {
            color: #7A4FB0;
        }
        
        .navbar-user {
            color: #8B5FBF;
            font-weight: 600;
            font-size: 16px;
        }
        
        /* Main Content */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
            position: relative;
            z-index: 1;
        }
        
        /* Alert Messages */
        .alert {
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            font-size: 14px;
            font-weight: 500;
        }
        
        .alert-success {
            background: rgba(212, 237, 218, 0.9);
            color: #155724;
            border-left: 4px solid #28a745;
            backdrop-filter: blur(10px);
        }
        
        .alert-danger {
            background: rgba(248, 215, 218, 0.9);
            color: #721c24;
            border-left: 4px solid #dc3545;
            backdrop-filter: blur(10px);
        }
        
        /* Welcome Card */
        .welcome-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 40px;
            text-align: center;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .welcome-card h2 {
            color: #8B5FBF;
            font-size: 32px;
            font-weight: 700;
            margin: 0;
            letter-spacing: -1px;
        }
        
        /* User Info Card */
        .user-info-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            max-width: 500px;
        }
        
        .user-info-card h5 {
            color: #8B5FBF;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #E8E8E8;
        }
        
        .info-item:last-child {
            border-bottom: none;
        }
        
        .info-label {
            color: #666;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .info-value {
            color: #333;
            font-weight: 500;
            font-size: 16px;
        }
        
        .role-badge {
            background: #8B5FBF;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* Logout Button */
        .logout-btn {
            position: fixed;
            bottom: 30px;
            left: 30px;
            background: #dc3545;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
            z-index: 1000;
        }
        
        .logout-btn:hover {
            background: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(220, 53, 69, 0.4);
            color: white;
            text-decoration: none;
        }
        
        /* Grid Layout */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        @media (min-width: 768px) {
            .dashboard-grid {
                grid-template-columns: 1fr 1fr;
            }
            
            .welcome-card {
                grid-column: 1 / -1;
            }
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 20px 15px;
            }
            
            .navbar-container {
                padding: 0 15px;
            }
            
            .navbar-brand {
                font-size: 24px;
            }
            
            .navbar-user {
                font-size: 14px;
            }
            
            .welcome-card {
                padding: 30px 20px;
            }
            
            .welcome-card h2 {
                font-size: 28px;
            }
            
            .user-info-card {
                padding: 25px 20px;
            }
            
            .logout-btn {
                bottom: 20px;
                left: 20px;
                padding: 10px 16px;
                font-size: 13px;
            }
            
            .info-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="navbar-container">
            <a class="navbar-brand" href="<?= base_url('dashboard') ?>">LMS</a>
            <span class="navbar-user">
                Welcome, <?= esc($user['name']) ?>
            </span>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
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

        <div class="dashboard-grid">
            <!-- Welcome Card -->
            <div class="welcome-card">
                <h2>Welcome back, <?= esc($user['name']) ?>!</h2>
            </div>

            <!-- User Info Card -->
            <div class="user-info-card">
                <h5>User Information</h5>
                <div class="info-item">
                    <span class="info-label">Full Name</span>
                    <span class="info-value"><?= esc($user['name']) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email Address</span>
                    <span class="info-value"><?= esc($user['email']) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Account Role</span>
                    <span class="role-badge"><?= ucfirst(esc($user['role'])) ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Logout Button -->
    <a href="<?= base_url('logout') ?>" class="logout-btn" 
       onclick="return confirm('Are you sure you want to logout?')">
        🚪 Logout
    </a>
</body>
</html>