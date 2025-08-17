<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #cfdce6ff, #cfdce6ff);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .dashboard {
            background: #fff;
            border-radius: 12px;
            padding: 30px;
            width: 400px;
            text-align: center;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        .dashboard h2 {
            margin-bottom: 15px;
            font-size: 24px;
            color: #333;
        }
        .dashboard p {
            font-size: 16px;
            color: #666;
            margin-bottom: 25px;
        }
        .dashboard a {
            display: inline-block;
            margin: 10px 5px;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            color: #fff;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .dashboard a:hover {
            opacity: 0.85;
        }
        .btn-orders {
            background: #4facfe;
        }
        .btn-logout {
            background: #ff4e50;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['admin']); ?> 👋</h2>
        <p>Manage your store from here</p>
        <a href="orders.php" class="btn-orders">📦 View Orders</a>
        <a href="logout.php" class="btn-logout">🚪 Logout</a>
    </div>
</body>
</html>
