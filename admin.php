<?php session_start(); ?>

<?php if (isset($_SESSION['logged_in']) && $_SESSION['username'] == 'admin'): ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AJ Graphics - Admin Dashboard</title>
  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="account.css">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/eedbcd0c96.js" crossorigin="anonymous"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <style>
    :root {
      --sidebar-width: 250px;
      --card-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }
    
    /* Sidebar styles */
    .sidebar {
        width: var(--sidebar-width);
        background: #fff;
        box-shadow: var(--card-shadow);
        position: fixed;
        height: 100vh;
        transition: all 0.3s;
        z-index: 1000;
    }
    
    .sidebar-header {
        padding: 1.5rem 1.5rem 0.5rem;
        background: var(--primary-color);
        color: white;
    }
    
    .sidebar-header h3 {
        font-size: 1.2rem;
        font-weight: 600;
    }
    
    .sidebar-menu {
        padding: 0 1rem;
    }
    
    .nav-link {
        color: #6e707e;
        padding: 0.75rem 1rem;
        margin: 0.25rem 0;
        border-radius: 0.35rem;
        display: flex;
        align-items: center;
    }
    
    .nav-link:hover, .nav-link.active {
        background-color: rgba(50, 205, 50, 0.1);
        color: var(--primary-color);
    }
    
    .nav-link i {
        margin-right: 0.5rem;
        font-size: 1rem;
    }
    
    @media (max-width: 768px) {
        .sidebar {
            width: 0;
            overflow: hidden;
        }
        
        .sidebar.active {
            width: var(--sidebar-width);
        }
    }

    /* Main content styles */
    body {
      background: linear-gradient(to bottom,rgb(249, 252, 252), #32CD32);
      min-height: 100vh;
      margin: 0;
      padding: 0;
    }

    .main-content {
      margin-left: var(--sidebar-width);
      transition: margin-left 0.3s;
    }

    @media (max-width: 768px) {
      .main-content {
        margin-left: 0;
      }
    }

    .admin-container {
      max-width: 1200px;
      margin: 2rem auto;
      padding: 2rem;
      background: white;
      border-radius: 15px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .admin-header {
      text-align: center;
      margin-bottom: 2rem;
    }

    .stats-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1.5rem;
      margin-bottom: 2rem;
    }

    .stat-card {
      background: white;
      border-radius: 15px;
      padding: 1.5rem;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    .stat-card i {
      font-size: 2.5rem;
      margin-bottom: 1rem;
      color: #00CED1;
    }

    .stat-card h3 {
      font-size: 2rem;
      margin: 0.5rem 0;
      color: #2c3e50;
    }

    .stat-card p {
      color: #666;
      margin: 0;
    }

    .admin-links {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1.5rem;
      margin-bottom: 2rem;
    }

    .admin-link {
      background: white;
      border-radius: 15px;
      padding: 2rem;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      text-align: center;
      transition: transform 0.3s ease;
      cursor: pointer;
      text-decoration: none;
      color: inherit;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 1rem;
    }

    .admin-link:hover {
      transform: translateY(-5px);
      color: inherit;
    }

    .admin-link i {
      font-size: 2.5rem;
      color: #00CED1;
    }

    .admin-link span {
      font-size: 1.2rem;
      font-weight: 600;
      color: #2c3e50;
    }

    .recent-orders {
      background: white;
      border-radius: 15px;
      padding: 1.5rem;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .recent-orders h2 {
      color: #2c3e50;
      margin-bottom: 1.5rem;
    }

    .table {
      width: 100%;
      border-collapse: collapse;
    }

    .table th,
    .table td {
      padding: 1rem;
      text-align: left;
      border-bottom: 1px solid #eee;
    }

    .table th {
      background: #f8f9fa;
      font-weight: 600;
      color: #2c3e50;
    }

    .status-badge {
      padding: 0.25rem 0.5rem;
      border-radius: 20px;
      font-size: 0.875rem;
    }

    .status-pending {
      background: #fff3cd;
      color: #856404;
    }

    .status-completed {
      background: #d4edda;
      color: #155724;
    }

    .status-cancelled {
      background: #f8d7da;
      color: #721c24;
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
   <?php include_once "sidebar.php"; ?>
  <!-- Main Content -->
  <div class="main-content">
    <?php include_once "navbar.php"?>

    <div class="admin-container">
      <div class="admin-header" style="position: relative;">
        <h1>Admin Dashboard</h1>
        <p>Welcome back, <?= htmlspecialchars($_SESSION['username']) ?>!</p>
      </div>

      <div class="stats-container">
        <?php
        include_once "get_db.inc.php";
        
        // Total Orders
        $stmt = $pdo->query("SELECT COUNT(*) FROM orders");
        $total_orders = $stmt->fetchColumn();
        
        // Total Users
        $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE username != 'admin'");
        $total_users = $stmt->fetchColumn();
        
        // Total Items
        $stmt = $pdo->query("SELECT COUNT(*) FROM item");
        $total_items = $stmt->fetchColumn();
        
        // Total Revenue
        $stmt = $pdo->query("SELECT COALESCE(SUM(total_price), 0) FROM order_items");
        $total_revenue = $stmt->fetchColumn();
        ?>
        
        <div class="stat-card">
          <i class="fas fa-shopping-cart"></i>
          <h3><?= $total_orders ?></h3>
          <p>Total Orders</p>
        </div>
        
        <div class="stat-card">
          <i class="fas fa-users"></i>
          <h3><?= $total_users ?></h3>
          <p>Total Users</p>
        </div>
        
        <div class="stat-card">
          <i class="fas fa-box"></i>
          <h3><?= $total_items ?></h3>
          <p>Total Items</p>
        </div>
        
        <div class="stat-card">
          <i class="fas fa-money-bill-wave"></i>
          <h3>₱<?= number_format($total_revenue, 2) ?></h3>
          <p>Total Revenue</p>
        </div>
      </div>

      <div class="admin-links">
        <a href="manage_orders.php" class="admin-link">
          <i class="fas fa-shopping-cart"></i>
          <span>Manage Orders</span>
        </a>
        <a href="manage_users.php" class="admin-link">
          <i class="fas fa-users"></i>
          <span>Manage Users</span>
        </a>
        <a href="manage_items.php" class="admin-link">
          <i class="fas fa-box"></i>
          <span>Manage Items</span>
        </a>
        <a href="transaction_form.php" class="admin-link">
          <i class="fas fa-money-bill-wave"></i>
          <span>Manage Transactions</span>
        </a>
      </div>

      <div class="recent-orders">
        <h2>Recent Orders</h2>
        <table class="table">
          <thead>
            <tr>
              <th>Order #</th>
              <th>Customer</th>
              <th>Date</th>
              <th>Status</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sql = 'SELECT o.*, u.username, COALESCE(SUM(oi.total_price), 0) as total 
                    FROM orders o 
                    LEFT JOIN users u ON o.user_id = u.id 
                    LEFT JOIN order_items oi ON o.id = oi.order_id 
                    GROUP BY o.id 
                    ORDER BY o.order_date DESC 
                    LIMIT 5';
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $recent_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $num = 1;
            ?>

            <?php foreach ($recent_orders as $order): ?>
              <tr>
                <td>#<?= htmlspecialchars($num++) ?></td>
                <td><?= htmlspecialchars($order['username']) ?></td>
                <td><?= htmlspecialchars($order['order_date']) ?></td>
                <td>
                  <span class="status-badge status-<?= $order['status'] ?>">
                    <?= ucfirst(htmlspecialchars($order['status'])) ?>
                  </span>
                </td>
                <td>₱<?= number_format($order['total'], 2) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Toggle sidebar on mobile
    document.addEventListener('DOMContentLoaded', function() {
      const sidebar = document.getElementById('sidebar');
      const navbarToggler = document.querySelector('.navbar-toggler');
      
      if (navbarToggler) {
        navbarToggler.addEventListener('click', function() {
          sidebar.classList.toggle('active');
        });
      }
      
      // Highlight active link based on current page
      const currentPage = location.pathname.split('/').pop();
      const navLinks = document.querySelectorAll('.nav-link');
      
      navLinks.forEach(link => {
        if (link.getAttribute('href') === currentPage) {
          link.classList.add('active');
        } else {
          link.classList.remove('active');
        }
      });
    });
  </script>
</body>
</html>

<?php else: ?>
<?php header('Location: home.php'); ?>
<?php endif; ?>