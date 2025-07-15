<?php
session_start();
if ($_SESSION['username'] !== 'admin') {
    header('Location: home.php');
    exit;
}

include_once "get_db.inc.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $order_id = intval($_POST['order_id']);
        $status = trim($_POST['status']);
        
        // Validate status
        $allowedStatuses = ['pending', 'processing', 'completed', 'cancelled'];
        if (!in_array($status, $allowedStatuses)) {
            throw new Exception('Invalid status');
        }

        // Get current status before updating
        $query = "SELECT status FROM orders WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$order_id]);
        $current_status = $stmt->fetchColumn();

        // Inventory adjustment logic
        if ($status == 'completed' && $current_status != 'completed') {
            // Deduct inventory
            $query = "SELECT item_id, quantity FROM order_items WHERE order_id = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$order_id]);
            $order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($order_items as $row) {
                $item_id = $row['item_id'];
                $quantity = $row['quantity'];
                $query = "UPDATE inventory SET Quantity = Quantity - ? WHERE ItemID = ?";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$quantity, $item_id]);
            }
        } elseif ($current_status == 'completed' && $status != 'completed') {
            // Restore inventory
            $query = "SELECT item_id, quantity FROM order_items WHERE order_id = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$order_id]);
            $order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($order_items as $row) {
                $item_id = $row['item_id'];
                $quantity = $row['quantity'];
                $query = "UPDATE inventory SET Quantity = Quantity + ? WHERE ItemID = ?";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$quantity, $item_id]);
            }
        }

        // Update order status
        $query = "UPDATE orders SET status = ? WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$status, $order_id]);
        
        if ($stmt->rowCount() > 0) {
            header('Location: manage_orders.php?success=1');
        } else { 
            throw new Exception('Order not found or no changes made');
        }
        
    } catch (Exception $e) {
        header('Location: manage_orders.php?error=' . urlencode($e->getMessage()));
    }
} else {
    header('Location: manage_orders.php');
}
exit;