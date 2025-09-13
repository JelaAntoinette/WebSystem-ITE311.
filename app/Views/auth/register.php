<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - LMS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {  
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #8B5FBF;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        
        /* Background decoration */
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: float 20s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        
        .register-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 50px 40px;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            width: 100%;
            max-width: 500px;
            position: relative;
            z-index: 1;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .header {
            text-align: center;
            margin-bottom: 35px;
        }
        
        .header h2 {
            color: #8B5FBF;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
            letter-spacing: -1px;
        }
        
        .header p {
            color: #666;
            font-size: 16px;
            font-weight: 300;
        }
        
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }
        
        .form-label {
            display: block;
            margin-bottom: 6px;
            color: #8B5FBF;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 18px;
            border: 2px solid #E8E8E8;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #FAFAFA;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #8B5FBF;
            background: white;
            box-shadow: 0 0 0 4px rgba(139, 95, 191, 0.1);
            transform: translateY(-2px);
        }
        
        .form-text {
            font-size: 12px;
            color: #888;
            margin-top: 5px;
            font-style: italic;
        }
        
        .btn-primary {
            width: 100%;
            background: #8B5FBF;
            border: none;
            padding: 16px;
            border-radius: 12px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 15px;
        }
        
        .btn-primary:hover {
            background: #7A4FB0;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(139, 95, 191, 0.3);
        }
        
        .login-link {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #E8E8E8;
            font-size: 15px;
            color: #666;
        }
        
        .login-link a {
            color: #8B5FBF;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        
        .login-link a:hover {
            color: #7A4FB0;
            text-decoration: underline;
        }
        
        .alert {
            padding: 12px 18px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 500;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            color: #155724;
            border-left: 4px solid #28a745;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #f8d7da, #f5c6cb);
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        
        .alert ul {
            margin: 8px 0 0 20px;
            padding: 0;
        }
        
        .alert li {
            margin-bottom: 5px;
        }
        
        .text-danger {
            color: #dc3545;
            font-size: 12px;
            font-weight: 500;
            margin-top: 5px;
            display: block;
        }
        
        /* Responsive design */
        @media (max-width: 600px) {
            .register-container {
                padding: 40px 30px;
                margin: 10px;
                max-width: 100%;
            }
            
            .header h2 {
                font-size: 28px;
            }
            
            .form-control {
                padding: 10px 15px;
                font-size: 16px; /* Prevent zoom on iOS */
            }
        }
        
        @media (max-height: 700px) {
            .register-container {
                margin: 10px 0;
                padding: 30px 40px;
            }
            
            .header {
                margin-bottom: 25px;
            }
            
            .form-group {
                margin-bottom: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="header">
            <h2>Join LMS</h2>
            <p>Create your account to get started</p>
        </div>

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

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <strong>Please fix the following errors:</strong>
                <ul>
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= base_url('register') ?>">
            <div class="form-group">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" 
                       value="<?= old('name') ?>" required>
                <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['name'])): ?>
                    <div class="text-danger"><?= session()->getFlashdata('errors')['name'] ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" 
                       value="<?= old('email') ?>" required>
                <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['email'])): ?>
                    <div class="text-danger"><?= session()->getFlashdata('errors')['email'] ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
                <div class="form-text">Minimum 6 characters</div>
                <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['password'])): ?>
                    <div class="text-danger"><?= session()->getFlashdata('errors')['password'] ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="password_confirm" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['password_confirm'])): ?>
                    <div class="text-danger"><?= session()->getFlashdata('errors')['password_confirm'] ?></div>
                <?php endif; ?>
            </div>
            
            <button type="submit" class="btn-primary">Create Account</button>
        </form>

        <div class="login-link">
            <span>Already have an account? <a href="<?= base_url('login') ?>">Sign In</a></span>
        </div>
    </div>
</body>
</html>