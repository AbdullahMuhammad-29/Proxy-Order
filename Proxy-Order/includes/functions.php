<?php
session_start();

// Add product to cart
function addToCart($product) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // If product already exists in cart (by URL), just increase quantity
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['url'] === $product['url']) {
            $item['quantity'] += $product['quantity'] ?? 1;
            return;
        }
    }

    // Otherwise, add new product
    $_SESSION['cart'][] = $product;
}

// Get all cart items
function getCart() {
    return isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
}

// Calculate total for one product
function calculateItemTotal($item) {
    $quantity = $item['quantity'] ?? 1;
    return ($item['price'] + ($item['commission'] ?? 0) + ($item['shipping'] ?? 0)) * $quantity;
}

// Calculate grand total
function calculateGrandTotal() {
    $total = 0;
    foreach (getCart() as $item) {
        $total += calculateItemTotal($item);
    }
    return $total;
}

// Clear cart
function clearCart() {
    unset($_SESSION['cart']);
}
?>
