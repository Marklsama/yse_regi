<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deleteIds = $_POST['delete_ids'] ?? [];
    $target = $_POST['target'] ?? '';

    // Зөвшөөрөгдсөн хүснэгтүүдийг энд тодорхойлно
    $allowed = [
        'sales' => ['table' => 'sales_items', 'redirect' => 'sales.php'],
        'barcodes' => ['table' => 'barcodes', 'redirect' => 'barcode.php'],
        'commits' => ['table' => 'commits', 'redirect' => 'commit.php'],
        // Хэрвээ products хүснэгт хэрэгтэй бол энд нэмнэ
        // 'products' => ['table' => 'products', 'redirect' => 'index.php'],
    ];

    // target шалгах
    if (!isset($allowed[$target])) {
        echo '<div style="text-align: center; margin-top: 50px;">';
        echo '<h2>無効なリクエストです。</h2>';
        echo '<a href="index.php" class="btn-red" style="display: inline-block; margin-top: 20px; padding: 10px 20px; font-size: 16px; color: #fff; background-color: #007bff; text-decoration: none; border-radius: 5px;">戻る</a>';
        echo '</div>';
        exit;
    }

    // Сонгосон id-ууд байгаа эсэхийг шалгах
    if (is_array($deleteIds) && !empty($deleteIds)) {
        try {
            // id-уудыг бүхэл тоо болгож шалгах (SQL injection-ээс хамгаална)
            $deleteIds = array_map('intval', $deleteIds);
            $placeholders = implode(',', array_fill(0, count($deleteIds), '?'));
            $stmt = $pdo->prepare("DELETE FROM {$allowed[$target]['table']} WHERE id IN ($placeholders)");
            $stmt->execute($deleteIds);

            // Амжилттай устгасны дараа буцах
            header('Location: ' . $allowed[$target]['redirect'] . '?deleted=1');
            exit;
        } catch (PDOException $e) {
            echo '<div style="text-align: center; margin-top: 50px;">';
            echo '<h2>データベースエラー: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES) . '</h2>';
            echo '</div>';
        }
    } else {
        // Сонгосон өгөгдөл байхгүй үед
        header('Location: ' . $allowed[$target]['redirect'] . '?error=1');
        exit;
    }
} else {
    echo '<div style="text-align: center; margin-top: 50px;">';
    echo '<h2>無効なリクエストです。</h2>';
    echo '<a href="index.php" class="btn-red" style="display: inline-block; margin-top: 20px; padding: 10px 20px; font-size: 16px; color: #fff; background-color: #007bff; text-decoration: none; border-radius: 5px;">戻る</a>';
    echo '</div>';
}
?>