<?php 
include("../config.php"); 
include("../includes/header.php"); 
include("../includes/functions.php"); 
?>
<!-- PAGE HEADER -->
<div class="container mt-5 text-center">
    <h2 class="fw-bold text-primary">🛒 Submit Product Link</h2>
    <p class="text-muted fs-5">Add a new product by filling the form below.</p>
</div>

<!-- CARD FORM -->
<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-6">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4 p-md-5">
                    <form method="POST" action="cart.php">
                        <div class="mb-4">
                            <label class="form-label fw-semibold">🔗 Product URL</label>
                            <input type="url" name="product_url" class="form-control form-control-lg" placeholder="https://example.com/product" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">📦 Product Name</label>
                            <input type="text" name="product_name" class="form-control form-control-lg" placeholder="Enter Product Name" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">💲 Price</label>
                            <input type="number" step="0.01" name="product_price" class="form-control form-control-lg" placeholder="e.g. 49.99" required>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" name="add" class="btn btn-primary btn-lg shadow-sm">
                                Add to Cart
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- TRACK ORDER MODAL -->
<div class="modal fade" id="trackOrderModal" tabindex="-1" aria-labelledby="trackOrderModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 shadow-lg">
      <div class="modal-header bg-gradient text-white">
        <h5 class="modal-title" id="trackOrderModalLabel">Track Your Order</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="GET" action="track_order.php">
          <div class="modal-body">
              <div class="mb-3">
                  <label for="tracking_code" class="form-label fw-semibold">Enter Tracking Code</label>
                  <input type="text" name="tracking_code" id="tracking_code" class="form-control form-control-lg" placeholder="ORD123456789" required>
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-success">Track</button>
          </div>
      </form>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<style>
/* Track Order Button */

/* Modal Gradient Header */
.modal-header.bg-gradient {
    background: linear-gradient(135deg, #3c3589ff, #263c8bff);
}

/* Page adjustments */
body {
    background-color: #f9f9f9;
}

.card {
    border: none;
}

.btn-primary {
    background: linear-gradient(135deg, #3c3589ff, #263c8bff);
    border: none;
}
</style>

<?php include("../includes/footer.php"); ?>
