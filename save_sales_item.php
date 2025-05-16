<?php
require_once 'db.php'; // Өгөгдлийн сантай холбох

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $saleDate = $_POST['sale_date'] ?? null;
    $productName = $_POST['product_name'] ?? null;
    $quantity = $_POST['quantity'] ?? null;
    $amount = $_POST['amount'] ?? null;

    if ($saleDate && $productName && $quantity && $amount) {
        try {
            $stmt = $pdo->prepare("INSERT INTO sales_items (sale_date, product_name, quantity, amount) VALUES (:sale_date, :product_name, :quantity, :amount)");
            $stmt->execute([
                ':sale_date' => $saleDate,
                ':product_name' => $productName,
                ':quantity' => $quantity,
                ':amount' => $amount
            ]);

            header('Location: sales.php?success=1');
            exit;
        } catch (PDOException $e) {
            echo '<div style="text-align: center; margin-top: 50px;">';
            echo '<h2>データベースエラー: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES) . '</h2>';
            echo '</div>';
        }
    } else {
        header('Location: sales.php?error=1');
        exit;
    }
}
?>