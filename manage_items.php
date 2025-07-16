  <?php session_start(); ?>

  <?php if (isset($_SESSION['logged_in']) && $_SESSION['username'] == 'admin'): ?>
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AJ Graphics - Manage Items</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/eedbcd0c96.js" crossorigin="anonymous"></script>
    <style>
      :root {
        --primary-color: #4e73df;
        --success-color: #1cc88a;
        --info-color: #36b9cc;
        --warning-color: #f6c23e;
        --danger-color: #e74a3b;
        --light-bg: #f8f9fc;
        --card-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
      }
      
      body {
        font-family: 'Poppins', sans-serif;
        background-color: #f5f7fa;
        color: #4a4a4a;
      }
      
      .items-container {
        max-width: 1400px;
        margin: 2rem auto;
        padding: 2.5rem;
      }
      
      .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e3e6f0;
      }
      
      .page-title {
        font-weight: 600;
        color: #2e3a4d;
        margin-bottom: 0;
      }
      
      .page-subtitle {
        color: #6e707e;
        font-size: 0.9rem;
      }
      
      .card {
        border: none;
        border-radius: 0.5rem;
        box-shadow: var(--card-shadow);
        margin-bottom: 2rem;
      }
      
      .card-header {
        background-color: white;
        border-bottom: 1px solid #e3e6f0;
        padding: 1.25rem 1.5rem;
        font-weight: 600;
        color: #4a4a4a;
      }
      
      .card-body {
        padding: 1.5rem;
      }
      
      .table-responsive {
        border-radius: 0.35rem;
        overflow: hidden;
      }
      
      .table {
        margin-bottom: 0;
      }
      
      .table thead th {
        background-color: var(--light-bg);
        color: #4a4a4a;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        border-bottom-width: 1px;
        padding: 1rem 1.25rem;
      }
      
      .table tbody td {
        padding: 1.25rem;
        vertical-align: middle;
        border-color: #e3e6f0;
      }
      
      .table tbody tr:hover {
        background-color: rgba(78, 115, 223, 0.05);
      }
      
      .item-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: transform 0.2s;
      }
      
      .item-image:hover {
        transform: scale(1.05);
      }
      
      .btn-action {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        border-radius: 0.25rem;
        margin-right: 0.5rem;
      }
      
      .btn-add {
        padding: 0.5rem 1.25rem;
        font-weight: 500;
        border-radius: 0.35rem;
      }
      
      .search-box {
        position: relative;
        flex-grow: 1;
        max-width: 400px;
      }
      
      .search-box .form-control {
        padding-left: 2.5rem;
        border-radius: 0.35rem;
      }
      
      .search-box i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6e707e;
      }
      
      .no-items {
        text-align: center;
        padding: 3rem;
        color: #6e707e;
      }
      
      .no-items i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: #d1d3e2;
      }
      
      .description-cell {
        max-width: 300px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
      }
      
      .price-cell {
        font-weight: 600;
        color: var(--primary-color);
      }
      
      .category-badge {
        display: inline-block;
        padding: 0.35rem 0.65rem;
        border-radius: 0.25rem;
        font-size: 0.75rem;
        font-weight: 600;
        background-color: rgba(78, 115, 223, 0.1);
        color: var(--primary-color);
      }
      
      @media (max-width: 768px) {
        .items-container {
          padding: 1.5rem;
        }
        
        .page-header {
          flex-direction: column;
          align-items: flex-start;
        }
        
        .btn-add {
          margin-top: 1rem;
          width: 100%;
        }
        
        .table thead {
          display: none;
        }
        
        .table, .table tbody, .table tr, .table td {
          display: block;
          width: 100%;
        }
        
        .table tr {
          margin-bottom: 1.5rem;
          border: 1px solid #e3e6f0;
          border-radius: 0.35rem;
          overflow: hidden;
        }
        
        .table td {
          padding: 0.75rem;
          text-align: right;
          position: relative;
          padding-left: 50%;
        }
        
        .table td::before {
          content: attr(data-label);
          position: absolute;
          left: 1rem;
          width: calc(50% - 1rem);
          padding-right: 1rem;
          font-weight: 600;
          text-align: left;
          color: #4a4a4a;
        }
        
        .action-buttons {
          display: flex;
          justify-content: flex-end;
        }
      }
      
      /* Modal styles */
      .modal-content {
        border: none;
        border-radius: 0.5rem;
      }
      
      .modal-header {
        border-bottom: 1px solid #e3e6f0;
        padding: 1.25rem 1.5rem;
      }
      
      .modal-title {
        font-weight: 600;
      }
      
      .modal-body {
        padding: 1.5rem;
      }
      
      .form-label {
        font-weight: 500;
        color: #4a4a4a;
      }
    </style>
  </head>
  <body>
  <?php include_once "navbar.php"?>

  <div class="items-container">
    <div class="page-header">
      <div>
        <h1 class="page-title">Manage Items</h1>
        <p class="page-subtitle">Add, edit, or remove products from your catalog</p>
      </div>
      <button type="button" class="btn btn-primary btn-add" data-bs-toggle="modal" data-bs-target="#addItemModal">
        <i class="bi bi-plus-lg me-2"></i>Add New Item
      </button>
    </div>

    <div class="card">
      <div class="card-header">
        <i class="bi bi-funnel me-2"></i>Filters
      </div>
      <div class="card-body">
        <form class="row g-3 align-items-center" method="GET">
          <div class="col-md-8 search-box">
            <i class="bi bi-search"></i>
            <input type="text" name="search" class="form-control" 
                  value="<?= $_GET['search'] ?? '' ?>" placeholder="Search by name or description...">
          </div>
          <div class="col-md-4 d-flex">
            <button type="submit" class="btn btn-primary me-2">
              <i class="bi bi-funnel me-1"></i>Apply Filters
            </button>
            <a href="manage_items.php" class="btn btn-outline-secondary">
              <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
            </a>
          </div>
        </form>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <i class="bi bi-grid me-2"></i>Product Catalog
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Description</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php 
              include_once "get_db.inc.php";
              
              $where = [];
              $params = [];
              
              if (!empty($_GET['search'])) {
                  $where[] = "(name LIKE ? OR description LIKE ?)";
                  $params[] = "%{$_GET['search']}%";
                  $params[] = "%{$_GET['search']}%";
              }
              
              $query = "SELECT * FROM item";
              if (!empty($where)) {
                  $query .= " WHERE " . implode(" AND ", $where);
              }
              $query .= " ORDER BY id DESC";
              
              try {
                  $stmt = $pdo->prepare($query);
                  $stmt->execute($params);
                  $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
              } catch (PDOException $e) {
                  $items = [];
              }
              
              if (empty($items)): ?>
              <tr>
                <td colspan="6">
                  <div class="no-items">
                    <i class="bi bi-box-seam"></i>
                    <h4>No Items Found</h4>
                    <p>There are currently no items in your catalog.</p>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addItemModal">
                      <i class="bi bi-plus-lg me-1"></i>Add Your First Item
                    </button>
                  </div>
                </td>
              </tr>
              <?php else: ?>
                <?php foreach($items as $item): ?>
                <tr>
                  <td data-label="Image">
                    <img src="uploads/<?= htmlspecialchars($item['image']) ?>" 
                        alt="<?= htmlspecialchars($item['name']) ?>" 
                        class="item-image"
                        data-bs-toggle="modal" 
                        data-bs-target="#imageModal"
                        onclick="showImage('uploads/<?= htmlspecialchars($item['image']) ?>', '<?= htmlspecialchars($item['name']) ?>')">
                  </td>
                  <td data-label="Name"><?= htmlspecialchars($item['name']) ?></td>
                  <td data-label="Category">
                    <span class="category-badge"><?= ucfirst(htmlspecialchars($item['category'])) ?></span>
                  </td>
                  <td data-label="Price" class="price-cell">₱<?= number_format($item['price'], 2) ?></td>
                  <td data-label="Description" class="description-cell" title="<?= htmlspecialchars($item['description']) ?>">
                    <?= htmlspecialchars($item['description']) ?>
                  </td>
                  <td data-label="Actions" class="action-buttons">
                    <button type="button" class="btn btn-sm btn-outline-primary btn-action" 
                            onclick="editItem(<?= $item['id'] ?>)">
                      <i class="bi bi-pencil-square me-1"></i>Edit
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger btn-action" 
                            onclick="deleteItem(<?= $item['id'] ?>)">
                      <i class="bi bi-trash me-1"></i>Delete
                    </button>
                  </td>
                </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Add Item Modal -->
  <div class="modal fade" id="addItemModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Add New Item</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="addItemForm" action="create_item_handler.inc.php" method="POST" enctype="multipart/form-data">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Item Name</label>
                <input type="text" class="form-control" name="item_name" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Category</label>
                <input type="text" class="form-control" name="category" required>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Price</label>
                <div class="input-group">
                  <span class="input-group-text">₱</span>
                  <input type="number" class="form-control" name="price" step="0.01" required>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Stock</label>
                <input type="number" class="form-control" name="stock" required>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Description</label>
              <textarea class="form-control" name="description" rows="3" required></textarea>
            </div>
            <div class="mb-4">
              <label class="form-label">Image</label>
              <input type="file" class="form-control" name="image" accept="image/*" required>
              <small class="text-muted">Recommended size: 800x800 pixels</small>
            </div>
            <div class="d-flex justify-content-end">
              <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle me-1"></i>Add Item
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Item Modal -->
  <div class="modal fade" id="editItemModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Item</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="editItemContent">
          <!-- Content will be loaded dynamically -->
        </div>
      </div>
    </div>
  </div>

  <!-- Image Preview Modal -->
  <div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="imageModalTitle"></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center">
          <img id="modalImage" src="" alt="" class="img-fluid" style="max-height: 70vh;">
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
  <script>
  function editItem(itemId) {
    fetch(`get_item.php?id=${itemId}`)
      .then(response => response.text())
      .then(html => {
        document.getElementById('editItemContent').innerHTML = html;
        new bootstrap.Modal(document.getElementById('editItemModal')).show();
      });
  }

  function deleteItem(itemId) {
    if (confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
      fetch('delete_item.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id=${itemId}`
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          location.reload();
        } else {
          alert('Error deleting item: ' + data.message);
        }
      });
    }
  }

  function showImage(src, title) {
    document.getElementById('modalImage').src = src;
    document.getElementById('imageModalTitle').textContent = title;
  }
  </script>
  </body>
  </html>

  <?php else: ?>
  <?php header('Location: home.php'); ?>
  <?php endif; ?>