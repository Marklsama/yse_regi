<?php
require_once 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submitType = $_POST['submit_type'] ?? '';

    switch ($submitType) {
        case 'barcode':
            // バーコード入力の処理
            $barcode = $_POST['barcode'] ?? '';
            if (!empty($barcode)) {
                echo "バーコード: " . htmlspecialchars($barcode, ENT_QUOTES) . " を処理しました。";
            } else {
                echo "バーコードが入力されていません。";
            }
            break;

        case 'commit':
            // 計上の処理
            $price = $_POST['price'] ?? '';
            if (!empty($price)) {
                echo "金額: " . htmlspecialchars($price, ENT_QUOTES) . " を計上しました。";
            } else {
                echo "金額が入力されていません。";
            }
            break;

        default:
            echo "不明な操作です。";
            break;
    }
} else {
    echo "無効なリクエストです。";
}
?>