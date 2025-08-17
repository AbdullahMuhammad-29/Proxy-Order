<?php 
include("../config.php"); 
session_start(); 

if (isset($_POST['login'])) {
    $user = $_POST['username'];
    $pass = md5($_POST['password']);

    $res = $conn->query("SELECT * FROM admins WHERE username='$user' AND password='$pass'");
    if ($res->num_rows > 0) {
        $_SESSION['admin'] = $user;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "❌ Invalid login!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #cfdce6ff, #cfdce6ff);
            font-family: Arial, sans-serif;
        }
        .login-box {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            width: 350px;
            box-shadow: 0px 8px 20px rgba(0,0,0,0.15);
            text-align: center;
        }
        .login-box h2 {
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px;
        }
        .btn-primary {
            background: #4facfe;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            transition: 0.3s;
        }
        .btn-primary:hover {
            background: #3b82f6;
        }
        .error-msg {
            color: #ff4e50;
            margin-bottom: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>🔑 Admin Login</h2>
        <?php if (isset($error)) echo "<p class='error-msg'>$error</p>"; ?>
        <form method="POST">
            <div class="mb-3">
                <input type="text" name="username" class="form-control" placeholder="👤 Username" required>
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="🔒 Password" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary">Login</button>
        </form>
    </div>
</body>
</html>
