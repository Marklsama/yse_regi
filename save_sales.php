<?php
require 'db.php'; // Өгөгдлийн сантай холбох

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $saleAt = $_POST['sale_at'] ?? null;
    $amount = $_POST['amount'] ?? null;
    $receiptNo = $_POST['receipt_no'] ?? null;

    if ($saleAt && $amount && $receiptNo) {
        try {
            $stmt = $pdo->prepare("INSERT INTO sales (sale_at, amount, receipt_no) VALUES (:sale_at, :amount, :receipt_no)");
            $stmt->execute([
                ':sale_at' => $saleAt,
                ':amount' => $amount,
                ':receipt_no' => $receiptNo,
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
} else {
    echo '<div style="text-align: center; margin-top: 50px;">';
    echo '<h2>無効なリクエストです。</h2>';
    echo '<a href="index.php" class="btn-red" style="display: inline-block; margin-top: 20px; padding: 10px 20px; font-size: 16px; color: #fff; background-color: #007bff; text-decoration: none; border-radius: 5px;">戻る</a>';
    echo '</div>';
}
?>