<?php
include("../config.php");
include("../includes/functions.php");
include("../includes/header.php");

$cart = getCart(); // Get current cart

// Check if cart is empty
if (empty($cart)) {
    echo "<div class='alert alert-warning mt-3 shadow-sm p-3 rounded-4'>
            ⚠️ Your cart is empty. Please add items before checkout.
          </div>";
    include("../includes/footer.php");
    exit; // Stop further processing
}

$orderPlaced = false;
$errors = [];

if (isset($_POST['submit_order'])) {
    // Sanitize inputs
    $name    = trim($_POST['name']);
    $phone   = trim($_POST['phone']);
    $payment = trim($_POST['payment']);

    // Validate required fields
    if (empty($name)) $errors[] = "Name is required.";
    if (empty($phone)) $errors[] = "Phone number is required.";
    if (empty($payment)) $errors[] = "Payment method is required.";

    if (empty($errors)) {
        // Calculate grand total
        $grand = 0;
        foreach ($cart as $c) {
            $grand += $c['price'] + $c['commission'] + $c['shipping'];
        }

        $tracking_code = "ORD" . time();
        $status = "Pending";

        $stmt = $conn->prepare("
            INSERT INTO orders 
            (tracking_code, customer_name, customer_phone, payment_method, total_amount, status) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("ssssds", $tracking_code, $name, $phone, $payment, $grand, $status);

        if ($stmt->execute()) {
            $orderPlaced = true;
            $orderId = $stmt->insert_id;

            $itemStmt = $conn->prepare("
                INSERT INTO order_items (order_id, product_name, product_url, product_price) 
                VALUES (?, ?, ?, ?)
            ");
            foreach ($cart as $c) {
                $itemStmt->bind_param("issd", $orderId, $c['name'], $c['url'], $c['price']);
                $itemStmt->execute();
            }

            clearCart();
        } else {
            $errors[] = "Database error: " . $stmt->error;
        }
    }
}
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">

            <?php if (!empty($errors)) : ?>
            <div class="alert alert-danger mt-3 shadow-sm p-3 rounded-4" id="errorBox">
                <?php foreach ($errors as $err) echo "❌ $err<br>"; ?>
            </div>
            <script>
                setTimeout(() => { document.getElementById('errorBox').style.display = 'none'; }, 5000);
            </script>
            <?php endif; ?>

            <?php if (!$orderPlaced) : ?>
            <div class="card shadow-lg rounded-4 p-4 p-md-5">
                <h2 class="text-center text-primary fw-bold mb-4">🛒 Checkout</h2>

                <form method="POST" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Name</label>
                        <input type="text" name="name" class="form-control form-control-lg" placeholder="Your Name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Phone</label>
                        <input type="text" name="phone" class="form-control form-control-lg" placeholder="+1 1234567890" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Payment Method</label>
                        <select name="payment" class="form-select form-select-lg" required>
                            <option value="">Select a payment method</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="Wallet">Wallet</option>
                            <option value="Card">Card</option>
                        </select>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" name="submit_order" class="btn btn-gradient btn-lg shadow-sm">
                            ✅ Place Order
                        </button>
                    </div>
                </form>
            </div>
            <?php else: ?>
            <div id="orderModal" class="modal-overlay">
                <div class="modal-content slide-in">
                    <h3 class="mb-3">✅ Order Placed Successfully!</h3>
                    <p>Your tracking code is: <strong><?= $tracking_code ?></strong></p>
                    <a href="/Proxy-Order/public/index.php" class="btn btn-gradient btn-lg mt-3">🏠 Return to Home</a>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<style>
.card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
.card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.15); }
.btn-gradient { background: linear-gradient(135deg, #3c3589, #263c8b); border: none; font-weight: 600; transition: transform 0.2s ease, box-shadow 0.2s ease; color: #fff; }
.btn-gradient:hover { transform: scale(1.05); box-shadow: 0 10px 20px rgba(0,0,0,0.3); }
.form-control:focus, .form-select:focus { border-color: #3c3589; box-shadow: 0 0 0 0.2rem rgba(60, 53, 137, 0.25); }
.modal-overlay { position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5); display:flex; justify-content:center; align-items:center; z-index:9999; }
.modal-content { background:#fff; padding:2rem; border-radius:15px; text-align:center; max-width:400px; width:90%; animation: slideIn 0.5s ease; }
@keyframes slideIn { from { transform: translateY(-50px); opacity:0; } to { transform: translateY(0); opacity:1; } }
@media (max-width:768px){ .card { padding:2rem 1.5rem; } }
</style>

<?php include("../includes/footer.php"); ?>
