<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Product Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      /* Gradient background */
      background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 50%, #e3f2fd 100%);
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      min-height: 100vh;
      position: relative;
      overflow-x: hidden;
    }

    /* Decorative floating circles */
    body::before, 
    body::after {
      content: "";
      position: absolute;
      border-radius: 50%;
      background: rgba(25, 118, 210, 0.08);
      z-index: -1;
    }

    body::before {
      width: 400px;
      height: 400px;
      top: -100px;
      left: -100px;
    }

    body::after {
      width: 300px;
      height: 300px;
      bottom: -120px;
      right: -100px;
    }

    .container {
      border: none;
      border-radius: 18px;
      background: #ffffff;
      margin-top: 30px;
      margin-bottom: 30px;
      position: relative;
      box-shadow: 0 8px 20px rgba(25, 118, 210, 0.15);
      padding: 30px;
    }

    /* Page header */
    .page-header h2 {
      font-weight: 700;
      background: linear-gradient(90deg, #1976d2, #42a5f5);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .page-header p {
      font-size: 1rem;
      color: #6c757d;
    }

    /* Card redesign */
    .card {
      border: none;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(25, 118, 210, 0.1);
    }

    .card-body {
      padding: 0;
    }

    /* Table styling */
    .table thead {
      background: linear-gradient(90deg, #1976d2, #42a5f5);
      color: #fff;
      font-size: 0.95rem;
      text-transform: uppercase;
    }

    .table tbody tr:hover {
      background-color: #f5faff;
      transition: 0.2s ease;
    }

    .table td, .table th {
      padding: 14px;
      vertical-align: middle;
    }

    /* Buttons */
    .btn-custom {
      border-radius: 8px;
      font-weight: 500;
      padding: 6px 14px;
    }

    .btn-primary {
      background: linear-gradient(90deg, #1976d2, #42a5f5);
      border: none;
    }

    .btn-primary:hover {
      opacity: 0.9;
    }

    .btn-success {
      background: linear-gradient(90deg, #2e7d32, #66bb6a);
      border: none;
    }

    .btn-danger {
      background: linear-gradient(90deg, #c62828, #ef5350);
      border: none;
    }

    /* Modals */
    .modal-content {
      border-radius: 16px;
      box-shadow: 0 6px 18px rgba(0,0,0,0.2);
    }

    .modal-header {
      border-bottom: none;
    }

    .modal-footer {
      border-top: none;
    }
  </style>
</head>
<body>

<div class="container">
  <!-- Page Header -->
  <div class="page-header text-center">
    <h2><i class="bi bi-box-seam"></i> Product Management</h2>
  
    <p class="text-muted"></p>
  </div>

  <!-- Alerts -->
  <div class="mb-3">
    <?php getErrors(); ?>
    <?php getMessage(); ?>
  </div>

  <!-- Search + Add Product -->
  <div class="d-flex justify-content-between mb-3 align-items-center">
    <form action="<?=site_url('/');?>" method="get" class="col-sm-5 d-flex">
      <?php
      $q = '';
      if(isset($_GET['q'])) {
        $q = $_GET['q'];
      }
      ?>
      <input class="form-control me-2" name="q" type="text" placeholder="Search" value="<?=html_escape($q);?>">
                <button type="submit" class="btn btn-primary" type="button">Search</button>
    </form>
        


    <button class="btn btn-success btn-custom shadow-sm" data-bs-toggle="modal" data-bs-target="#addModal">
      + Add Product
    </button>
  </div>

  <!-- Table Card -->
  <div class="card">
    <div class="card-body">
      <table class="table table-hover align-middle text-center mb-0">
        <thead>
          <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Created</th>
            <th>Updated</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if(!empty($all)): ?>
            <?php foreach($all as $product): ?>
              <tr>
                <td><?= htmlspecialchars($product['product_name']); ?></td>
                <td><?= htmlspecialchars($product['quantity']); ?></td>
                <td>₱<?= htmlspecialchars($product['rice']); ?></td>
                <td><?= htmlspecialchars($product['created_at']); ?></td>
                <td><?= htmlspecialchars($product['updated_at']); ?></td>
                <td>
                  <button class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#editModal<?= $product['id']; ?>">Edit</button>
                  <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $product['id']; ?>">Delete</button>
                </td>
              </tr>

              <!-- Edit Modal -->
              <div class="modal fade" id="editModal<?= $product['id']; ?>" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <form action="/update-product/<?= $product['id']; ?>" method="POST">
                      <div class="modal-header bg-primary text-white rounded-top">
                        <h5 class="modal-title">Edit Product</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body">
                        <input type="hidden" name="id" value="<?= $product['id']; ?>">
                        <div class="mb-3">
                          <label class="form-label">Product Name</label>
                          <input type="text" name="product_name" class="form-control" value="<?= $product['product_name']; ?>" required>
                        </div>
                        <div class="mb-3">
                          <label class="form-label">Quantity</label>
                          <input type="number" name="quantity" class="form-control" value="<?= $product['quantity']; ?>" required>
                        </div>
                        <div class="mb-3">
                          <label class="form-label">Price</label>
                          <input type="number" name="price" class="form-control" value="<?= $product['price']; ?>" required>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-custom">Update</button>
                        <button type="button" class="btn btn-light btn-custom" data-bs-dismiss="modal">Cancel</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>

              <!-- Delete Modal -->
              <div class="modal fade" id="deleteModal<?= $product['id']; ?>" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <form action="/delete-product/<?= $product['id']; ?>" method="POST">
                      <div class="modal-header bg-danger text-white rounded-top">
                        <h5 class="modal-title">Delete Product</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body">
                        <p>Are you sure you want to delete <strong><?= $product['product_name'] ?></strong>?</p>
                        <input type="hidden" name="id" value="<?= $product['id']; ?>">
                      </div>
                      <div class="modal-footer">
                        <button type="submit" class="btn btn-danger btn-custom">Delete</button>
                        <button type="button" class="btn btn-light btn-custom" data-bs-dismiss="modal">Cancel</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>

            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" class="text-muted">No products found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>

      <!-- Pagination -->
      <div class="d-flex justify-content-center my-3">
        <?php echo $page; ?>
      </div>
    </div>
  </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="/create-user" method="POST">
        <div class="modal-header bg-success text-white rounded-top">
          <h5 class="modal-title">Add Product</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Product Name</label>
            <input type="text" name="product_name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Quantity</label>
            <input type="number" name="quantity" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Price</label>
            <input type="number" name="Price" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success btn-custom">Add</button>
          <button type="button" class="btn btn-light btn-custom" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Bootstrap + Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL; ?>/public/js/alert.js"></script>
</body>
</html>
