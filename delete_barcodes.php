<?php
require_once 'db.php'; // Өгөгдлийн сантай холбох

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deleteIds = $_POST['delete_ids'] ?? [];

    if (is_array($deleteIds) && !empty($deleteIds)) {
        try {
            // Сонгосон өгөгдлийг устгах
            $placeholders = implode(',', array_fill(0, count($deleteIds), '?'));
            $stmt = $pdo->prepare("DELETE FROM barcodes WHERE id IN ($placeholders)");
            $stmt->execute($deleteIds);

            // Амжилттай устгасны дараа буцах
            header('Location: barcode.php?deleted=1');
            exit;
        } catch (PDOException $e) {
            echo '<div style="text-align: center; margin-top: 50px;">';
            echo '<h2>データベースエラー: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES) . '</h2>';
            echo '</div>';
        }
    } else {
        // Сонгосон өгөгдөл байхгүй үед
        header('Location: barcode.php?error=1');
        exit;
    }
} else {
    echo '<div style="text-align: center; margin-top: 50px;">';
    echo '<h2>無効なリクエストです。</h2>';
    echo '<a href="barcode.php" class="btn-red" style="display: inline-block; margin-top: 20px; padding: 10px 20px; font-size: 16px; color: #fff; background-color: #007bff; text-decoration: none; border-radius: 5px;">戻る</a>';
    echo '</div>';
}
?>