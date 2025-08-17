<?php
session_start();
include("../config.php");

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("No order ID provided.");
}

$order_id = intval($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = $conn->real_escape_string($_POST['status']);
    $sql = "UPDATE orders SET status='$status' WHERE id=$order_id";
    if ($conn->query($sql)) {
        header("Location: orders.php?msg=Status Updated");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}

$result = $conn->query("SELECT * FROM orders WHERE id=$order_id");
$order = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Update Order Status</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
  <div class="card p-4 shadow-lg">
    <h3 class="mb-4">Update Status for Order #<?= $order['id'] ?></h3>
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Current Status</label>
        <select name="status" class="form-select">
          <option <?= $order['status']=='Pending'?'selected':'' ?>>Pending</option>
          <option <?= $order['status']=='Processing'?'selected':'' ?>>Processing</option>
          <option <?= $order['status']=='Shipped'?'selected':'' ?>>Shipped</option>
          <option <?= $order['status']=='Completed'?'selected':'' ?>>Completed</option>
          <option <?= $order['status']=='Cancelled'?'selected':'' ?>>Cancelled</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Update</button>
      <a href="orders.php" class="btn btn-secondary">Back</a>
    </form>
  </div>
</body>
</html>
