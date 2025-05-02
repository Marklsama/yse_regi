<?php
require_once 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $barcode = $_POST['barcode'] ?? '';
    $product = $_POST['product'] ?? '';
    $quantity = $_POST['quantity'] ?? '';

    if (!empty($barcode) && !empty($product) && !empty($quantity)) {
        // Өгөгдлийн сан руу өгөгдлийг нэмэх
        // Жишээ:
        // $pdo = new PDO(...);
        // $stmt = $pdo->prepare("INSERT INTO barcodes (barcode, product, quantity) VALUES (?, ?, ?)");
        // $stmt->execute([$barcode, $product, $quantity]);

        echo '<div style="text-align: center; margin-top: 50px;">';
        echo '<h2>バーコードデータが追加されました。</h2>';
        echo '<a href="barcode.php" class="btn-red" style="display: inline-block; margin-top: 20px; padding: 10px 20px; font-size: 16px; color: #fff; background-color: #007bff; text-decoration: none; border-radius: 5px;">戻る</a>';
        echo '</div>';
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
?>