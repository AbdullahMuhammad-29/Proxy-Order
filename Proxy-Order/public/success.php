<?php
include("../includes/header.php");

// Get tracking code from URL
$tracking_code = isset($_GET['tracking']) ? $_GET['tracking'] : '';
?>
<div class="container my-5 text-center">
    <h2 class="text-success fw-bold">✅ Order Placed Successfully!</h2>
    <p class="lead">Thank you for your order. Your tracking code is: <strong><?php echo htmlspecialchars($tracking_code); ?></strong></p>
    <a href="index.php" class="btn btn-primary btn-lg mt-3">Go Home</a>
</div>

<?php include("../includes/footer.php"); ?>
