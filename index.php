<?php
session_start();
include 'db.php';

if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $_SESSION['admin'] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Library Tracker - Admin Login</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        .login-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            padding: 40px;
            width: 100%;
            max-width: 400px;
            animation: fadeIn 0.5s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
            font-weight: 600;
            font-size: 28px;
            position: relative;
            padding-bottom: 15px;
        }
        
        h2:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(to right, #667eea, #764ba2);
            border-radius: 2px;
        }
        
        .library-icon {
            text-align: center;
            margin-bottom: 20px;
            font-size: 48px;
            color: #667eea;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e1e5ee;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }
        
        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        input[type="submit"] {
            background: linear-gradient(to right, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 14px 20px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
        }
        
        input[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        input[type="submit"]:active {
            transform: translateY(0);
        }
        
        .error-message {
            background: #ffeaea;
            color: #d32f2f;
            padding: 12px;
            border-radius: 6px;
            text-align: center;
            margin-top: 20px;
            border-left: 4px solid #d32f2f;
            font-size: 14px;
            animation: shake 0.3s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        .forgot-password {
            text-align: center;
            margin-top: 15px;
        }
        
        .forgot-password a {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }
        
        .forgot-password a:hover {
            color: #764ba2;
            text-decoration: underline;
        }
        
        .footer-note {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 12px;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }
        
        @media (max-width: 480px) {
            .login-container {
                margin: 20px;
                padding: 30px 20px;
            }
            
            body {
                background: white;
                align-items: flex-start;
                padding-top: 40px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="library-icon">📚</div>
        <h2>Admin Login</h2>
        <form method="POST">
            <div class="form-group">
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <input type="submit" name="login" value="Login">
            
            <?php if(isset($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <div class="forgot-password">
                <a href="#">Forgot password?</a>
            </div>
        </form>
        <div class="footer-note">
            Library Tracker System © <?php echo date('Y'); ?>
        </div>
    </div>
</body>
</html>