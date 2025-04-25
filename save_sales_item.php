<?php
require 'db.php'; // Өгөгдлийн сантай холбох

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
                ':amount' => $amount,
            ]);

            echo '<div style="text-align: center; margin-top: 50px;">';
            echo '<h2>データが正常に保存されました。</h2>';
            echo '<a href="index.php" class="btn-red" style="display: inline-block; margin-top: 20px; padding: 10px 20px; font-size: 16px; color: #fff; background-color: #007bff; text-decoration: none; border-radius: 5px;">戻る</a>';
            echo '</div>';
        } catch (PDOException $e) {
            die("データ保存中にエラーが発生しました: " . $e->getMessage());
        }
    } else {
        echo '<div style="text-align: center; margin-top: 50px;">';
        echo '<h2>すべてのフィールドを入力してください。</h2>';
        echo '<a href="index.php" class="btn-red" style="display: inline-block; margin-top: 20px; padding: 10px 20px; font-size: 16px; color: #fff; background-color: #007bff; text-decoration: none; border-radius: 5px;">戻る</a>';
        echo '</div>';
    }
}
?>