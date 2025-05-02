<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $barcode = $_POST['barcode'] ?? '';
    $product = $_POST['product'] ?? '';
    $quantity = $_POST['quantity'] ?? '';

    if (!empty($barcode) && !empty($product) && !empty($quantity)) {
        try {
            // Өгөгдлийн сан руу өгөгдөл нэмэх
            $stmt = $pdo->prepare("INSERT INTO barcodes (barcode, product, quantity) VALUES (:barcode, :product, :quantity)");
            $stmt->execute([
                ':barcode' => $barcode,
                ':product' => $product,
                ':quantity' => $quantity
            ]);

            // Амжилттай нэмсэн мессежтэйгээр буцах
            header('Location: barcode.php?success=1');
            exit;
        } catch (PDOException $e) {
            echo '<div style="text-align: center; margin-top: 50px;">';
            echo '<h2>データベースエラー: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES) . '</h2>';
            echo '</div>';
        }
    } else {
        echo '<div style="text-align: center; margin-top: 50px;">';
        echo '<h2>すべてのフィールドを入力してください。</h2>';
        echo '<a href="barcode.php" class="btn-red" style="display: inline-block; margin-top: 20px; padding: 10px 20px; font-size: 16px; color: #fff; background-color: #007bff; text-decoration: none; border-radius: 5px;">戻る</a>';
        echo '</div>';
    }
} else {
    echo '<div style="text-align: center; margin-top: 50px;">';
    echo '<h2>無効なリクエストです。</h2>';
    echo '<a href="barcode.php" class="btn-red" style="display: inline-block; margin-top: 20px; padding: 10px 20px; font-size: 16px; color: #fff; background-color: #007bff; text-decoration: none; border-radius: 5px;">戻る</a>';
    echo '</div>';
}

// Баркодын өгөгдлийг авах
$stmt = $pdo->query("SELECT * FROM barcodes ORDER BY created_at DESC");
$barcodes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>