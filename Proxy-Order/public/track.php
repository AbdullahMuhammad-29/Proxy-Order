<?php
require_once("../includes/functions.php");

$status = null;
$error = null;

// Safe getter
function safeValue($array, $key, $default = "N/A") {
    return isset($array[$key]) && $array[$key] !== "" ? htmlspecialchars($array[$key]) : $default;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = intval($_POST['order_id']);
    $result = $conn->query("SELECT * FROM orders WHERE id=$order_id");

    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();
        $status = safeValue($order, "status");
    } else {
        $error = "Order not found.";
    }
}

include("../includes/header.php");
?>

<h2 class="mb-4">Track Your Order</h2>

<form method="post">
    <div class="mb-3">
        <label for="order_id" class="form-label">Enter Your Order ID</label>
        <input type="number" name="order_id" id="order_id" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Track</button>
</form>

<?php if ($status): ?>
    <div class="alert alert-info mt-3">
        <strong>Status:</strong> <?php echo $status; ?>
    </div>
<?php elseif ($error): ?>
    <div class="alert alert-danger mt-3">
        <?php echo $error; ?>
    </div>
<?php endif; ?>

<?php include("../includes/footer.php"); ?>
