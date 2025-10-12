<?php
session_start();

if(isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit;
}

if($_POST && isset($_POST['login'])) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if($email === 'admin@dlumine.com' && $password === 'admin123') {
        $_SESSION['admin_logged_in'] = true;
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid credentials!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - D'LUMINE</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f5f5f5; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .login-container { background: white; padding: 40px; border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        .login-header { text-align: center; margin-bottom: 30px; }
        .login-header h1 { color: #000080; font-family: 'Playfair Display', serif; margin-bottom: 10px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 500; }
        .form-group input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px; }
        .btn { width: 100%; padding: 12px; background: #000080; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; }
        .btn:hover { background: #000060; }
        .error { color: red; text-align: center; margin-bottom: 15px; }
        .demo-credentials { background: #f0f8ff; padding: 15px; border-radius: 5px; margin-top: 20px; font-size: 14px; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>D'LUMINE Admin</h1>
            <p>Access the admin panel</p>
        </div>
        
        <?php if(isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" name="login" class="btn">Login</button>
        </form>
        
        <div class="demo-credentials">
            <strong>Demo Credentials:</strong><br>
            Email: admin@dlumine.com<br>
            Password: admin123
        </div>
    </div>
</body>
</html>