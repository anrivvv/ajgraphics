<?php
session_start();
if ($_SESSION['username'] !== 'admin') {
    header('Location: home.php');
    exit;
}

include_once "get_db.inc.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders - AJ Graphics</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/eedbcd0c96.js" crossorigin="anonymous"></script>
    <style>
        :root {
            --sidebar-width: 250px;
            --primary-color: #32CD32;
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
            display: flex;
            min-height: 100vh;
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            transition: all 0.3s;
        }
        
        .orders-container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 2.5rem;
            background: white;
            border-radius: 0.5rem;
            box-shadow: var(--card-shadow);
        }
        
        h1 {
            font-weight: 600;
            color: #2e3a4d;
            margin-bottom: 1.5rem !important;
            border-bottom: 1px solid #e3e6f0;
            padding-bottom: 1rem;
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
            background-color: rgba(50, 205, 50, 0.05);
        }
        
        .btn-info {
            background-color: var(--info-color);
            border-color: var(--info-color);
        }
        
        .btn-info:hover {
            background-color: #2c9faf;
            border-color: #2a96a5;
        }
        
        .order-items {
            margin-top: 0;
            padding: 1.5rem;
            background: var(--light-bg);
            border-radius: 0 0 8px 8px;
        }
        
        .order-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #e3e6f0;
            transition: all 0.2s;
        }
        
        .order-item:hover {
            background-color: white;
        }
        
        .order-item:last-child {
            border-bottom: none;
        }
        
        .order-item img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
            margin-right: 1.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        
        .status-form select {
            padding: 0.5rem 1rem;
            border-radius: 0.35rem;
            border: 1px solid #d1d3e2;
            font-size: 0.85rem;
            color: #6e707e;
            background-color: white;
            transition: all 0.2s;
            cursor: pointer;
        }
        
        .status-form select:focus {
            border-color: #bac8f3;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        
        .status-badge {
            padding: 0.35rem 0.65rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-pending {
            background-color: rgba(246, 194, 62, 0.2);
            color: #f6c23e;
        }
        
        .status-shipped {
            background-color: rgba(54, 185, 204, 0.2);
            color: #36b9cc;
        }
        
        .status-completed {
            background-color: rgba(28, 200, 138, 0.2);
            color: #1cc88a;
        }
        
        .status-cancelled {
            background-color: rgba(231, 74, 59, 0.2);
            color: #e74a3b;
        }
        
        .alert {
            border-radius: 0.35rem;
            padding: 1rem 1.5rem;
        }
        
        .total-amount {
            font-weight: 600;
            color: #2e3a4d;
        }
        
        .no-orders {
            text-align: center;
            padding: 3rem;
            color: #6e707e;
        }
        
        .no-orders i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #d1d3e2;
        }
        
        .action-buttons .btn {
            margin-right: 0.5rem;
        }
        
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }
            
            .orders-container {
                padding: 1.5rem;
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
        }
    </style>
</head>
<body>
    <?php include_once "sidebar.php"; ?>
    
    <!-- Main Content -->
    <div class="main-content">
        <?php include_once "navbar.php"?>

        <div class="orders-container">
            <?php
            // Get filter and search parameters from GET
            $search = $_GET['search'] ?? '';
            $filter_status = $_GET['filter_status'] ?? '';
            ?>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="mb-0">Manage Orders</h1>
                <form class="d-flex" method="GET" action="manage_orders.php" role="search" style="gap: 0.5rem; align-items: center;">
                    <select name="filter_status" class="form-select form-select-sm" aria-label="Filter by status" style="min-width: 140px;">
                        <option value="" <?= $filter_status === '' ? 'selected' : '' ?>>All Statuses</option>
                        <option value="pending" <?= $filter_status === 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="shipped" <?= $filter_status === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                        <option value="completed" <?= $filter_status === 'completed' ? 'selected' : '' ?>>Completed</option>
                        <option value="cancelled" <?= $filter_status === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                    <input type="search" name="search" class="form-control form-control-sm" placeholder="Search orders..." value="<?= htmlspecialchars($search) ?>" aria-label="Search orders" />
                    <button type="submit" class="btn btn-info btn-sm" aria-label="Apply filter and search">
                        <i class=""></i> Filter
                    </button>
                </form>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <?= $_SESSION['success'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <?php unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?= $_SESSION['error'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Items</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $sql = 'SELECT o.*, u.username, COALESCE(SUM(oi.total_price), 0) as total 
                                FROM orders o 
                                LEFT JOIN users u ON o.user_id = u.id 
                                LEFT JOIN order_items oi ON o.id = oi.order_id ';
                        
                        $params = [];
                        $conditions = [];

                        if ($filter_status !== '') {
                            $conditions[] = 'o.status = :status';
                            $params[':status'] = $filter_status;
                        }

                        if ($search !== '') {
                            $conditions[] = '(u.username LIKE :search OR o.id LIKE :search)';
                            $params[':search'] = '%' . $search . '%';
                        }

                        if (count($conditions) > 0) {
                            $sql .= ' WHERE ' . implode(' AND ', $conditions);
                        }

                        $sql .= ' GROUP BY o.id ORDER BY o.order_date DESC';

                        $stmt = $pdo->prepare($sql);
                        $stmt->execute($params);
                        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        $num = 1;
                        ?>

                        <?php if (empty($orders)): ?>
                            <tr>
                                <td colspan="7">
                                    <div class="no-orders">
                                        <i class="bi bi-cart-x"></i>
                                        <h4>No Orders Found</h4>
                                        <p>There are currently no orders to display.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td data-label="Order #">#<?= htmlspecialchars($num++) ?></td>
                                    <td data-label="Customer"><?= htmlspecialchars($order['username']) ?></td>
                                    <td data-label="Date"><?= date('M j, Y', strtotime($order['order_date'])) ?></td>
                                    <td data-label="Status">
                                        <span class="status-badge status-<?= $order['status'] ?>">
                                            <i class="bi 
                                                <?= $order['status'] === 'pending' ? 'bi-hourglass' : '' ?>
                                                <?= $order['status'] === 'shipped' ? 'bi-truck' : '' ?>
                                                <?= $order['status'] === 'completed' ? 'bi bi-check-circle' : '' ?>
                                                <?= $order['status'] === 'cancelled' ? 'bi-x-circle' : '' ?>
                                                me-1"></i>
                                            <?= ucfirst(htmlspecialchars($order['status'])) ?>
                                        </span>
                                    </td>
                                    <td data-label="Total" class="total-amount">₱<?= number_format($order['total'], 2) ?></td>
                                    <td data-label="Items">
                                        <button class="btn btn-info btn-sm" type="button" data-bs-toggle="collapse" 
                                                data-bs-target="#items-<?= $order['id'] ?>" aria-expanded="false">
                                            <i class=""></i>View Items
                                        </button>
                                    </td>
                                    <td data-label="Actions">
                                        <form action="update_order_status.php" method="POST" class="status-form d-inline-block">
                                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                            <select name="status" onchange="this.form.submit()" class="form-select-sm">
                                                <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                                <option value="shipped" <?= $order['status'] === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                                                <option value="completed" <?= $order['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                                                <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="7" class="p-0">
                                        <div class="collapse" id="items-<?= $order['id'] ?>">
                                            <div class="order-items">
                                                <h5 class="mb-3"><i class="bi bi-box-seam me-2"></i>Order Items</h5>
                                                <?php
                                                $items_sql = 'SELECT oi.*, i.name as item_name, i.image 
                                                            FROM order_items oi 
                                                            LEFT JOIN item i ON oi.item_id = i.id 
                                                            WHERE oi.order_id = ?';
                                                $items_stmt = $pdo->prepare($items_sql);
                                                $items_stmt->execute([$order['id']]);
                                                $items = $items_stmt->fetchAll(PDO::FETCH_ASSOC);
                                                ?>
                                                <?php foreach ($items as $item): ?>
                                                    <div class="order-item">
                                                        <img src="uploads/<?= htmlspecialchars($item['image'] ?? 'default.jpg') ?>" 
                                                             alt="<?= htmlspecialchars($item['item_name']) ?>">
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1"><?= htmlspecialchars($item['item_name']) ?></h6>
                                                            <div class="d-flex justify-content-between">
                                                                <small class="text-muted">Quantity: <?= $item['quantity'] ?></small>
                                                                <small class="text-muted">Price: ₱<?= number_format($item['price'], 2) ?></small>
                                                                <small class="text-muted">Subtotal: ₱<?= number_format($item['total_price'], 2) ?></small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                                <div class="order-item bg-white mt-2 rounded">
                                                    <div class="w-100 text-end">
                                                        <h5 class="mb-0">Total: <span class="text-primary">₱<?= number_format($order['total'], 2) ?></span></h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add animation to status changes
        document.querySelectorAll('select[name="status"]').forEach(select => {
            select.addEventListener('change', function() {
                const row = this.closest('tr');
                row.style.transition = 'all 0.3s ease';
                row.style.backgroundColor = 'rgba(50, 205, 50, 0.1)';
                setTimeout(() => {
                    row.style.backgroundColor = '';
                }, 1000);
            });
        });
    </script>
</body>
</html>