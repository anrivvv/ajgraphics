<?php session_start(); ?>

<?php if (isset($_SESSION['logged_in']) && $_SESSION['username'] == 'admin'): ?>
<?php
$pdo = new PDO("mysql:host=localhost;dbname=db", "root", "");

// Fetch inventory
$inventory = $pdo->query("
    SELECT i.name, inv.Quantity, inv.StockThreshold
    FROM Inventory inv
    JOIN item i ON inv.ItemID = i.id
")->fetchAll(PDO::FETCH_ASSOC);

// Fetch transactions
$transactions = $pdo->query("
    SELECT t.TransactionDate, i.name, t.Quantity, t.TransactionStatus, u.username
    FROM Transactions t
    JOIN item i ON t.ItemID = i.id
    JOIN users u ON t.UserID = u.id
    ORDER BY t.TransactionDate DESC
    LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AJ Graphics - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=shopping_cart_checkout" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
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
      
      .dashboard-container {
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
        display: flex;
        justify-content: space-between;
        align-items: center;
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
      
      .status-badge {
        display: inline-block;
        padding: 0.35rem 0.65rem;
        border-radius: 0.25rem;
        font-size: 0.75rem;
        font-weight: 600;
      }
      
      .status-ok {
        background-color: rgba(28, 200, 138, 0.1);
        color: var(--success-color);
      }
      
      .status-low {
        background-color: rgba(231, 74, 59, 0.1);
        color: var(--danger-color);
      }
      
      .transaction-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.75rem;
      }
      
      .transaction-received {
        background-color: rgba(28, 200, 138, 0.1);
        color: var(--success-color);
      }
      
      .transaction-used, .transaction-sold, .transaction-returned {
        background-color: rgba(231, 74, 59, 0.1);
        color: var(--danger-color);
      }
      
      .quick-actions {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
      }
      
      .quick-action-btn {
        flex: 1;
        padding: 1.5rem;
        border-radius: 0.5rem;
        background: white;
        border: none;
        box-shadow: var(--card-shadow);
        text-align: center;
        transition: transform 0.2s;
      }
      
      .quick-action-btn:hover {
        transform: translateY(-3px);
      }
      
      .quick-action-btn i {
        font-size: 1.5rem;
        margin-bottom: 0.75rem;
        color: var(--primary-color);
      }
      
      .quick-action-btn .btn-text {
        font-weight: 500;
        color: #4a4a4a;
      }
      
      @media (max-width: 768px) {
        .dashboard-container {
          padding: 1.5rem;
        }
        
        .page-header {
          flex-direction: column;
          align-items: flex-start;
        }
        
        .quick-actions {
          flex-direction: column;
        }
        
        .table td {
          padding: 0.75rem;
        }
      }
    </style>
</head>
<body>
    <?php include_once "navbar.php"?>

    <div class="dashboard-container">
        <div class="page-header">
            <div>
                <h1 class="page-title">Admin Dashboard</h1>
                <p class="page-subtitle">Overview of inventory and recent activity</p>
            </div>
        </div>

        <div class="quick-actions">
            <a href="transaction_form.php" class="quick-action-btn">
                <i class="bi bi-plus-circle"></i>
                <div class="btn-text">New Transaction</div>
            </a>
            <a href="manage_items.php" class="quick-action-btn">
                <i class="bi bi-box-seam"></i>
                <div class="btn-text">Manage Items</div>
            </a>
            <a href="manage_suppliers.php" class="quick-action-btn">
                <i class="bi bi-people"></i>
                <div class="btn-text">Manage Suppliers</div>
            </a>
            <a href="reports.php" class="quick-action-btn">
                <i class="bi bi-graph-up"></i>
                <div class="btn-text">View Reports</div>
            </a>
        </div>

        <div class="card">
            <div class="card-header">
                <span><i class="bi bi-box-seam me-2"></i>Inventory Overview</span>
                <span class="badge bg-light text-dark"><?= count($inventory) ?> items</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>Threshold</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($inventory as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td><?= htmlspecialchars($row['Quantity']) ?></td>
                                    <td><?= htmlspecialchars($row['StockThreshold']) ?></td>
                                    <td>
                                        <?php if ($row['Quantity'] <= $row['StockThreshold']): ?>
                                            <span class="status-badge status-low">
                                                <i class="bi bi-exclamation-triangle-fill me-1"></i>Low Stock
                                            </span>
                                        <?php else: ?>
                                            <span class="status-badge status-ok">
                                                <i class="bi bi-check-circle-fill me-1"></i>OK
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <span><i class="bi bi-clock-history me-2"></i>Recent Transactions</span>
                <a href="transactions.php" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>Type</th>
                                <th>User</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transactions as $txn): ?>
                                <tr>
                                    <td><?= htmlspecialchars($txn['TransactionDate']) ?></td>
                                    <td><?= htmlspecialchars($txn['name']) ?></td>
                                    <td><?= htmlspecialchars($txn['Quantity']) ?></td>
                                    <td>
                                        <?php 
                                        $iconClass = 'transaction-received';
                                        if (in_array($txn['TransactionStatus'], ['Used', 'Sold', 'Returned'])) {
                                            $iconClass = 'transaction-used';
                                        }
                                        ?>
                                        <span class="transaction-icon <?= $iconClass ?>">
                                            <i class="bi bi-arrow-left-right"></i>
                                        </span>
                                        <?= htmlspecialchars($txn['TransactionStatus']) ?>
                                    </td>
                                    <td><?= htmlspecialchars($txn['username']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php else: ?>
<?php header('Location: home.php'); ?>
<?php endif; ?>