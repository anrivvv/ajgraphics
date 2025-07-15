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
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>
    <h2>Inventory Overview</h2>
    <table border="1" cellpadding="6">
        <tr>
            <th>Item</th>
            <th>Quantity</th>
            <th>Threshold</th>
            <th>Status</th>
        </tr>
        <?php foreach ($inventory as $row): ?>
            <tr>
                <td><?= $row['name'] ?></td>
                <td><?= $row['Quantity'] ?></td>
                <td><?= $row['StockThreshold'] ?></td>
                <td>
                    <?= ($row['Quantity'] <= $row['StockThreshold']) ? "<span style='color:red;'>Low Stock</span>" : "OK" ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h2>Recent Transactions</h2>
    <table border="1" cellpadding="6">
        <tr>
            <th>Date</th>
            <th>Item</th>
            <th>Quantity</th>
            <th>Type</th>
            <th>Handled By</th>
        </tr>
        <?php foreach ($transactions as $txn): ?>
            <tr>
                <td><?= $txn['TransactionDate'] ?></td>
                <td><?= $txn['name'] ?></td>
                <td><?= $txn['Quantity'] ?></td>
                <td><?= $txn['TransactionStatus'] ?></td>
                <td><?= $txn['username'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <br>
    <a href="transaction_form.php">Log a Transaction</a>
</body>
</html>
