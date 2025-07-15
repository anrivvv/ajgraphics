<?php

$pdo = new PDO("mysql:host=localhost;dbname=db", "root", "");

// Process form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $item_id = $_POST['item_id'];
    $quantity = $_POST['quantity'];
    $transaction_status = $_POST['transaction_status'];
    $supplier_id = !empty($_POST['supplier_id']) ? $_POST['supplier_id'] : null;
    $user_id = 1; // Example: logged-in user
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
        $success = "Transaction recorded and inventory updated.";
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
<html>
<head>
    <title>Inventory Transaction</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f6f8;
        }
        .transaction-container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        h2 {
            margin-bottom: 1.5rem;
            font-weight: 600;
        }
        form label {
            font-weight: 500;
            margin-top: 1rem;
        }
        form select, form input[type="number"], form textarea {
            width: 100%;
            padding: 0.5rem;
            border-radius: 4px;
            border: 1px solid #dee2e6;
            margin-bottom: 1rem;
            font-size: 1rem;
        }
        form button[type="submit"] {
            background: #198754;
            color: white;
            border: none;
            padding: 0.6rem 2rem;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            transition: background 0.2s;
        }
        form button[type="submit"]:hover {
            background: #145c32;
        }
        .alert-success, .alert-danger {
            margin-bottom: 1rem;
        }
        a {
            color: #198754;
            text-decoration: none;
            font-weight: 500;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h2>Log Inventory Transaction</h2>

    <?php if (!empty($success)) echo "<p style='color:green;'>$success</p>"; ?>
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST">
        <label>Item:</label>
        <select name="item_id" required>
            <?php foreach ($items as $item): ?>
                <option value="<?= $item['id'] ?>"><?= $item['name'] ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Quantity:</label>
        <input type="number" name="quantity" required><br><br>

        <label>Transaction Type:</label>
        <select name="transaction_status" required>
            <option value="Received">Received</option>
            <option value="Used">Used</option>
            <option value="Sold">Sold</option>
            <option value="Returned">Returned</option>
        </select><br><br>

        <label>Supplier:</label>
        <select name="supplier_id">
            <option value="">-- Optional --</option>
            <?php foreach ($suppliers as $sup): ?>
                <option value="<?= $sup['SupplierID'] ?>"><?= $sup['CompanyName'] ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Notes:</label>
        <textarea name="notes"></textarea><br><br>

        <button type="submit">Submit Transaction</button>
    </form>
    <br>
    <a href="admin_dashboard.php">Go to Dashboard</a>
</body>
</html>
