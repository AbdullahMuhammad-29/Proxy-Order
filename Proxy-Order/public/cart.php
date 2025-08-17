<?php
include("../config.php");
include("../includes/functions.php");
include("../includes/header.php");

// Handle Add to Cart (from form)
if (isset($_POST['add'])) {
    $product = [
        "url" => $_POST['product_url'],
        "name" => $_POST['product_name'],
        "price" => floatval($_POST['product_price']),
        "commission" => floatval($_POST['product_price']) * 0.05,
        "shipping" => 5.00, // flat estimate
        "quantity" => 1
    ];
    addToCart($product);
}

// Get cart items
$cart = getCart();
?>

<div class="container my-5">
    <h2 class="mb-4 text-center">🛒 Your Cart</h2>

    <?php if (empty($cart)): ?>
        <p class="text-center">No items in your cart.</p>
    <?php else: ?>
    <table class="table table-bordered text-center">
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Commission (5%)</th>
            <th>Shipping</th>
            <th>Total</th>
        </tr>
        <?php foreach ($cart as $item): 
            $total = calculateItemTotal($item);
        ?>
        <tr>
            <td><a href="<?= $item['url'] ?>" target="_blank"><?= $item['name'] ?></a></td>
            <td>$<?= number_format($item['price'], 2) ?></td>
            <td>$<?= number_format($item['commission'], 2) ?></td>
            <td>$<?= number_format($item['shipping'], 2) ?></td>
            <td>$<?= number_format($total, 2) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h4 class="text-end">Grand Total: $<?= number_format(calculateGrandTotal(), 2) ?></h4>
    <div class="text-end mt-3">
        <a href="checkout.php" class="btn btn-success btn-lg">Proceed to Checkout</a>
    </div>
    <?php endif; ?>
</div>

<?php include("../includes/footer.php"); ?>
