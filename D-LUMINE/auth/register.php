<?php
session_start();
include_once '../config/database.php';
include_once '../models/User.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$error = '';
$success = '';

if($_POST && isset($_POST['register'])) {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    
    // Validation
    if(empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Please fill in all required fields.";
    } elseif($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif(strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {
        $user->email = $email;
        
        if($user->emailExists()) {
            $error = "An account with this email already exists.";
        } else {
            $user->name = $name;
            $user->password = $password;
            $user->phone = $phone;
            $user->address = $address;
            
            if($user->create()) {
                $success = "Registration successful! You can now login.";
                // In a real application, you would send verification email here
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - D'LUMINE</title>
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
            max-width: 450px;
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
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-box">
            <div class="auth-logo">
                <h1>D'LUMINE</h1>
                <p>Create your account</p>
            </div>

            <?php if($error): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if($success): ?>
                <div class="success-message"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="name">Full Name *</label>
                    <input type="text" id="name" name="name" class="form-control" required value="<?php echo $_POST['name'] ?? ''; ?>">
                </div>

                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" name="email" class="form-control" required value="<?php echo $_POST['email'] ?? ''; ?>">
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" class="form-control" value="<?php echo $_POST['phone'] ?? ''; ?>">
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea id="address" name="address" class="form-control" rows="3"><?php echo $_POST['address'] ?? ''; ?></textarea>
                </div>

                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password *</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                </div>

                <button type="submit" name="register" class="btn-auth">Create Account</button>
            </form>

            <div class="auth-links">
                <p>Already have an account? <a href="login.php">Login here</a></p>
            </div>
        </div>
    </div>
</body>
</html>