<?php
session_start();
include("config.php"); // Make sure this path is correct

if(!isset($_SESSION['user_id'])){
    echo json_encode(["error" => "Not logged in"]);
    exit();
}

$user_id = $_SESSION['user_id'];
$order_id = $_GET['order_id'];

$stmt = $conn->prepare("SELECT status FROM orders WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if($row = $result->fetch_assoc()){
    echo json_encode(["status" => $row['status']]);
} else {
    echo json_encode(["error" => "Order not found"]);
}
?>
