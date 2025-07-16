<?php session_start(); ?>

<?php if (isset($_SESSION['logged_in']) && $_SESSION['username'] == 'admin'): ?>
<?php
$pdo = new PDO("mysql:host=localhost;dbname=db", "root", "");

// Process form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $item_id = $_POST['item_id'];
    $quantity = $_POST['quantity'];
    $transaction_status = $_POST['transaction_status'];
    $supplier_id = !empty($_POST['supplier_id']) ? $_POST['supplier_id'] : null;
    $user_id = $_SESSION['user_id']; // Use session user ID
    $notes = $_POST['notes'] ?? '';

    try {
        $pdo->beginTransaction();

        // Insert into Transactions
        $stmt = $pdo->prepare("
            INSERT INTO Transactions 
            (TransactionDate, TransactionStatus, Quantity, ItemID, SupplierID, UserID, Notes)
            VALUES (NOW(), :status, :qty, :item, :supplier, :user, :notes)
        ");
        $stmt->execute([
            ':status' => $transaction_status,
            ':qty' => $quantity,
            ':item' => $item_id,
            ':supplier' => $supplier_id,
            ':user' => $user_id,
            ':notes' => $notes
        ]);

        // Update Inventory
        if ($transaction_status === 'Received') {
            $sql = "UPDATE Inventory SET Quantity = Quantity + :qty WHERE ItemID = :item";
        } elseif (in_array($transaction_status, ['Used', 'Sold', 'Returned'])) {
            // Check current inventory first
            $check = $pdo->prepare("SELECT Quantity FROM Inventory WHERE ItemID = :item");
            $check->execute([':item' => $item_id]);
            $current = $check->fetch(PDO::FETCH_ASSOC);
            
            if ($current && $current['Quantity'] < $quantity) {
                throw new Exception("Not enough inventory for this transaction");
            }
            
            $sql = "UPDATE Inventory SET Quantity = Quantity - :qty WHERE ItemID = :item";
        } else {
            throw new Exception("Invalid transaction type");
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':qty' => $quantity,
            ':item' => $item_id
        ]);

        $pdo->commit();
        $success = "Transaction recorded and inventory updated successfully!";
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Error: " . $e->getMessage();
    }
}

// Fetch items for dropdown
$items = $pdo->query("SELECT id, name FROM item")->fetchAll(PDO::FETCH_ASSOC);
// Fetch suppliers
$suppliers = $pdo->query("SELECT SupplierID, CompanyName FROM Suppliers")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AJ Graphics - Inventory Transactions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
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
      
      .transaction-container {
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
      
      .form-label {
        font-weight: 500;
        color: #4a4a4a;
        margin-bottom: 0.5rem;
      }
      
      .form-control, .form-select {
        border-radius: 0.35rem;
        padding: 0.5rem 1rem;
        border: 1px solid #d1d3e2;
      }
      
      .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
      }
      
      .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        padding: 0.5rem 1.5rem;
        font-weight: 500;
      }
      
      .btn-primary:hover {
        background-color: #3a5ccc;
        border-color: #3a5ccc;
      }
      
      .alert {
        border-radius: 0.35rem;
        padding: 1rem 1.5rem;
      }
      
      .alert-success {
        background-color: rgba(28, 200, 138, 0.1);
        border-color: rgba(28, 200, 138, 0.3);
        color: #0f6848;
      }
      
      .alert-danger {
        background-color: rgba(231, 74, 59, 0.1);
        border-color: rgba(231, 74, 59, 0.3);
        color: #be2617;
      }
      
      .back-link {
        display: inline-flex;
        align-items: center;
        color: var(--primary-color);
        font-weight: 500;
        margin-top: 1rem;
      }
      
      .back-link:hover {
        color: #3a5ccc;
        text-decoration: none;
      }
      
      @media (max-width: 768px) {
        .transaction-container {
          padding: 1.5rem;
        }
        
        .page-header {
          flex-direction: column;
          align-items: flex-start;
        }
      }
    </style>
</head>
<body>
    <?php include_once "navbar.php"?>

    <div class="transaction-container">
        <div class="page-header">
            <div>
                <h1 class="page-title">Inventory Transactions</h1>
                <p class="page-subtitle">Record new inventory movements and transactions</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <i class="bi bi-arrow-left-right me-2"></i>New Transaction
            </div>
            <div class="card-body">
                <?php if (!empty($success)) : ?>
                    <div class="alert alert-success mb-4">
                        <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($success) ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($error)) : ?>
                    <div class="alert alert-danger mb-4">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="item_id" class="form-label">Item</label>
                            <select class="form-select" name="item_id" id="item_id" required>
                                <?php foreach ($items as $item) : ?>
                                    <option value="<?= $item['id'] ?>"><?= htmlspecialchars($item['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" name="quantity" id="quantity" required min="1">
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="transaction_status" class="form-label">Transaction Type</label>
                            <select class="form-select" name="transaction_status" id="transaction_status" required>
                                <option value="Received">Received (Add to Inventory)</option>
                                <option value="Used">Used (Remove from Inventory)</option>
                                <option value="Sold">Sold (Remove from Inventory)</option>
                                <option value="Returned">Returned (Remove from Inventory)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="supplier_id" class="form-label">Supplier (Optional)</label>
                            <select class="form-select" name="supplier_id" id="supplier_id">
                                <option value="">-- Select Supplier --</option>
                                <?php foreach ($suppliers as $sup) : ?>
                                    <option value="<?= $sup['SupplierID'] ?>"><?= htmlspecialchars($sup['CompanyName']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" name="notes" id="notes" rows="3"></textarea>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Record Transaction
                        </button>
                    </div>
                </form>

                <a href="admin_dashboard.php" class="back-link">
                    <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add real-time inventory validation
        document.getElementById('transaction_status').addEventListener('change', function() {
            const type = this.value;
            const quantityInput = document.getElementById('quantity');
            
            if (type !== 'Received') {
                // For deduction types, we should validate against current inventory
                // You could add AJAX call here to check current inventory levels
                quantityInput.setAttribute('min', '1');
            } else {
                quantityInput.setAttribute('min', '1');
            }
        });
    </script>
</body>
</html>

<?php else: ?>
<?php header('Location: home.php'); ?>
<?php endif; ?>