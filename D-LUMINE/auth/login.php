<?php
session_start();
include_once '../config/database.php';
include_once '../models/User.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$error = '';
$success = '';

if($_POST && isset($_POST['login'])) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if(empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        $user->email = $email;
        
        if($user->emailExists()) {
            if(password_verify($password, $user->password)) {
                // Login successful - no email verification required
                $_SESSION['user_id'] = $user->id;
                $_SESSION['user_name'] = $user->name;
                $_SESSION['user_email'] = $user->email;
                $_SESSION['user_role'] = $user->role;
                
                // Check if there's a redirect URL
                $redirect_url = $_SESSION['redirect_url'] ?? '';
                
                // Redirect to appropriate page
                if($user->role == 'admin') {
                    header("Location: ../admin/index.php");
                } else if($redirect_url) {
                    unset($_SESSION['redirect_url']);
                    header("Location: $redirect_url");
                } else {
                    header("Location: ../index.php");
                }
                exit;
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "No account found with this email.";
        }
    }
}

// Check for redirect parameter
if(isset($_GET['redirect'])) {
    $_SESSION['redirect_url'] = '../' . $_GET['redirect'] . '.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - D'LUMINE</title>
    <link rel="stylesheet" href="../style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
        }
        .auth-box {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .auth-logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .auth-logo h1 {
            font-family: 'Playfair Display', serif;
            color: #000080;
            margin-bottom: 10px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #eaeaea;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #000080;
            box-shadow: 0 0 0 3px rgba(0,0,128,0.1);
            outline: none;
        }
        .btn-auth {
            width: 100%;
            padding: 12px;
            background: #000080;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-auth:hover {
            background: #000060;
            transform: translateY(-2px);
        }
        .auth-links {
            text-align: center;
            margin-top: 20px;
        }
        .auth-links a {
            color: #000080;
            text-decoration: none;
        }
        .auth-links a:hover {
            text-decoration: underline;
        }
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }
        .demo-credentials {
            background: #f0f8ff;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            font-size: 14px;
            border-left: 4px solid #000080;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-box">
            <div class="auth-logo">
                <h1>D'LUMINE</h1>
                <p>Login to your account</p>
            </div>

            <?php if($error): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if($success): ?>
                <div class="success-message"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" required value="<?php echo $_POST['email'] ?? ''; ?>">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>

                <button type="submit" name="login" class="btn-auth">Login</button>
            </form>

            <div class="demo-credentials">
                <strong>Demo Customer Account:</strong><br>
                Email: john@example.com<br>
                Password: password
            </div>

            <div class="auth-links">
                <p>Don't have an account? <a href="register.php">Sign up here</a></p>
                <p><a href="forgot-password.php">Forgot your password?</a></p>
            </div>
        </div>
    </div>
</body>
</html>