<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "proxy_order";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}

// Create admins table if not exists
$conn->query("CREATE TABLE IF NOT EXISTS admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL
)");

// Insert default admin if none exists
$res = $conn->query("SELECT * FROM admins LIMIT 1");
if ($res->num_rows === 0) {
    $hash = password_hash("admin123", PASSWORD_BCRYPT);
    $stmt = $conn->prepare("INSERT INTO admins (username,password) VALUES (?,?)");
    $user = "admin";
    $stmt->bind_param("ss", $user, $hash);
    $stmt->execute();
}
