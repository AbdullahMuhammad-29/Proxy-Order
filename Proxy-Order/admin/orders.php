<?php
include("../config.php");
include("../includes/functions.php");

// Handle status update
if (isset($_POST['update_status'])) {
    $orderId = $_POST['order_id'];
    $newStatus = $_POST['status'];

    $stmt = $conn->prepare("UPDATE orders SET status=? WHERE id=?");
    $stmt->bind_param("si", $newStatus, $orderId);
    $stmt->execute();
    $stmt->close();
}

// Handle search/filter
$searchQuery = '';
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $stmt = $conn->prepare(
        "SELECT * FROM orders WHERE tracking_code LIKE ? OR customer_name LIKE ? ORDER BY id DESC"
    );
    $like = "%$searchQuery%";
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM orders ORDER BY id DESC");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Orders Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body { background: #f0f2f7; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }

        .navbar { box-shadow: 0 4px 12px rgba(0,0,0,0.2); }
        .btn-logout { background: linear-gradient(135deg, #ff4e50, #f9d423); color: #fff; font-weight: 600; border-radius: 50px; transition: 0.2s ease; }
        .btn-logout:hover { transform: scale(1.05); box-shadow: 0 10px 20px rgba(0,0,0,0.3); }

        .orders-card { background: #fff; padding: 2rem; border-radius: 20px; box-shadow: 0 8px 20px rgba(0,0,0,0.1); }

        .table thead { background: linear-gradient(135deg, #3c3589, #263c8b); color: #fff; font-weight: 600; }
        .table, .table th, .table td { border: 1px solid #dee2e6 !important; }
        .table tbody tr:nth-child(even) { background-color: #f9f9ff; }
        .table tbody tr:hover { background-color: #e2e0f7; transform: scale(1.01); transition: 0.2s ease; }
        .table th, .table td { vertical-align: middle !important; padding: 0.5rem 0.75rem; text-align: center; }

        .btn-gradient { background: linear-gradient(135deg, #3c3589ff, #263c8bff); color: #fff; font-weight: 600; border-radius: 50px; transition: 0.2s ease; }
        .btn-gradient:hover { transform: scale(1.05); box-shadow: 0 10px 20px rgba(0,0,0,0.3); }

        .product-link { display: block; font-size: 0.9rem; color: #3c3589; text-decoration: none; white-space: normal; word-wrap: break-word; }
        .product-link:hover { text-decoration: underline; }

        @media (max-width: 992px) {
            .table th, .table td { font-size: 0.85rem; padding: 0.4rem 0.5rem; }
        }

        .search-bar { margin-bottom: 1.5rem; display: flex; flex-wrap: wrap; gap: 0.5rem; justify-content: center; }
        .table-responsive { width: 100%; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">Orders Management</a>
        <div class="ms-auto">
            <a href="logout.php" class="btn btn-logout">Logout</a>
        </div>
    </div>
</nav>

<div class="container my-5">
    <h2 class="text-primary fw-bold mb-4 text-center">📦 Orders Management</h2>

    <!-- Search Bar -->
    <form method="GET" class="search-bar">
        <input type="text" name="search" class="form-control" placeholder="Search by Tracking Code or Customer" value="<?= htmlspecialchars($searchQuery) ?>">
        <button type="submit" class="btn btn-primary">Search</button>
        <?php if(!empty($searchQuery)): ?>
            <a href="orders.php" class="btn btn-secondary">Reset</a>
        <?php endif; ?>
    </form>

    <div class="orders-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 10%;">Tracking Code</th>
                        <th style="width: 15%;">Customer</th>
                        <th style="width: 10%;">Phone</th>
                        <th style="width: 30%;">Products</th>
                        <th style="width: 10%;">Total ($)</th>
                        <th style="width: 10%;">Status</th>
                        <th style="width: 15%;">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($order = $result->fetch_assoc()): ?>
                    <?php
                        $itemsStmt = $conn->prepare("SELECT product_name, product_url FROM order_items WHERE order_id=?");
                        $itemsStmt->bind_param("i", $order['id']);
                        $itemsStmt->execute();
                        $itemsResult = $itemsStmt->get_result();
                        $items = $itemsResult->fetch_all(MYSQLI_ASSOC);
                        $itemsStmt->close();
                    ?>
                    <tr>
                        <td><strong><?= $order['tracking_code'] ?></strong></td>
                        <td><?= htmlspecialchars($order['customer_name']) ?></td>
                        <td><?= htmlspecialchars($order['customer_phone']) ?></td>
                        <td class="text-start">
                            <?php foreach ($items as $item): ?>
                                <a href="<?= htmlspecialchars($item['product_url']) ?>" class="product-link" target="_blank">
                                    <?= htmlspecialchars($item['product_name']) ?>
                                </a>
                            <?php endforeach; ?>
                        </td>
                        <td><?= number_format($order['total_amount'],2) ?></td>
                        <td>
                            <span class="badge <?= $order['status'] == 'Pending' ? 'bg-warning text-dark' : ($order['status']=='Completed' ? 'bg-success' : 'bg-danger') ?>">
                                <?= $order['status'] ?>
                            </span>
                        </td>
                        <td>
                            <form method="POST" class="d-flex justify-content-center gap-2 flex-wrap">
                                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                <select name="status" class="form-select form-select-sm">
                                    <option value="Pending" <?= $order['status']=='Pending'?'selected':'' ?>>Pending</option>
                                    <option value="Completed" <?= $order['status']=='Completed'?'selected':'' ?>>Completed</option>
                                    <option value="Cancelled" <?= $order['status']=='Cancelled'?'selected':'' ?>>Cancelled</option>
                                     <option value="Shipped" <?= $order['status']=='Cancelled'?'selected':'' ?>>Shiped</option>
                                      <option value="Packing" <?= $order['status']=='Cancelled'?'selected':'' ?>>Packing</option>
                                </select>
                                <button type="submit" name="update_status" class="btn btn-gradient btn-sm">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
                <?php if ($result->num_rows == 0): ?>
                    <tr><td colspan="7">No orders found.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
