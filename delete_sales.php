<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deleteIds = $_POST['delete_ids'] ?? [];

    if (!empty($deleteIds)) {
        // Өгөгдлийн сангаас сонгосон өгөгдлийг устгах
        // Жишээ:
        // $pdo = new PDO(...);
        // $stmt = $pdo->prepare("DELETE FROM sales WHERE id IN (" . implode(',', array_map('intval', $deleteIds)) . ")");
        // $stmt->execute();

        echo '<div style="text-align: center; margin-top: 50px;">';
        echo '<h2>選択したデータが削除されました。</h2>';
        echo '<a href="sales.php" class="btn-red" style="display: inline-block; margin-top: 20px; padding: 10px 20px; font-size: 16px; color: #fff; background-color: #007bff; text-decoration: none; border-radius: 5px;">戻る</a>';
        echo '</div>';
    } else {
        echo '<div style="text-align: center; margin-top: 50px;">';
        echo '<h2>削除するデータが選択されていません。</h2>';
        echo '<a href="sales.php" class="btn-red" style="display: inline-block; margin-top: 20px; padding: 10px 20px; font-size: 16px; color: #fff; background-color: #007bff; text-decoration: none; border-radius: 5px;">戻る</a>';
        echo '</div>';
    }
} else {
    echo '<div style="text-align: center; margin-top: 50px;">';
    echo '<h2>無効なリクエストです。</h2>';
    echo '<a href="sales.php" class="btn-red" style="display: inline-block; margin-top: 20px; padding: 10px 20px; font-size: 16px; color: #fff; background-color: #007bff; text-decoration: none; border-radius: 5px;">戻る</a>';
    echo '</div>';
}
?>