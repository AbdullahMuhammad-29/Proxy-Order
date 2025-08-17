<?php 
include("../config.php"); 
include("../includes/header.php"); 
?>

<div class="container mt-5">

    <h2 class="text-center text-primary mb-4">🚚 Track Your Order</h2>

<?php
if (isset($_GET['tracking_code'])) {
    $tracking_code = $_GET['tracking_code'];

    // Use prepared statement to avoid SQL injection
    $stmt = $conn->prepare("SELECT * FROM orders WHERE tracking_code = ?");
    $stmt->bind_param("s", $tracking_code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();
        echo '<div class="card shadow-sm p-4 text-center">';
        echo '<h4 class="fw-bold">Order Found!</h4>';
        echo '<p><strong>Tracking Code:</strong> ' . htmlspecialchars($order['tracking_code']) . '</p>';
        echo '<p><strong>Customer Name:</strong> ' . htmlspecialchars($order['customer_name']) . '</p>';
        echo '<p><strong>Status:</strong> <span class="badge bg-info text-dark">' . htmlspecialchars($order['status']) . '</span></p>';
        echo '</div>';
    } else {
        echo '<div class="alert alert-danger text-center shadow-sm">❌ No order found with this tracking code.</div>';
    }
} else {
    echo '<div class="alert alert-warning text-center shadow-sm">⚠️ Please enter a tracking code.</div>';
}
?>

</div>

<?php include("../includes/footer.php"); ?>
