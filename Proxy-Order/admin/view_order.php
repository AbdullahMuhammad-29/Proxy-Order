<?php
session_start();
require_once("../includes/functions.php");

// Require login
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("No order ID provided.");
}

$order_id = intval($_GET['id']);
$sql = "SELECT * FROM orders WHERE id=$order_id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Order not found.");
}

$order = $result->fetch_assoc();

// Safe getter (prevents undefined array key warnings)
function safeValue($array, $key, $default = "N/A") {
    return isset($array[$key]) && $array[$key] !== "" ? htmlspecialchars($array[$key]) : $default;
}

include("../includes/header.php");
?>

<h2 class="mb-4">Order Details</h2>

<p><strong>Order ID:</strong> <?php echo safeValue($order, "id"); ?></p>
<p><strong>Name:</strong> <?php echo safeValue($order, "name"); ?></p>
<p><strong>Phone:</strong> <?php echo safeValue($order, "phone"); ?></p>
<p><strong>Address:</strong> <?php echo safeValue($order, "address"); ?></p>
<p><strong>Payment Method:</strong> <?php echo safeValue($order, "payment_method"); ?></p>
<p><strong>Status:</strong> <?php echo safeValue($order, "status"); ?></p>
<p><strong>Created At:</strong> <?php echo safeValue($order, "created_at"); ?></p>

<a href="update_status.php?id=<?php echo $order['id']; ?>" class="btn btn-primary">Update Status</a>
<a href="orders.php" class="btn btn-secondary">Back to Orders</a>

<?php include("../includes/footer.php"); ?>
