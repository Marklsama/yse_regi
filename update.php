<?php
require_once 'db.php'; // Өгөгдлийн сантай холбох

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
            $productName = $_POST['product_name'] ?? '';
            $price = $_POST['price'] ?? '';
            if (!empty($productName) && !empty($price) && is_numeric($price)) {
                try {
                    $stmt = $pdo->prepare("INSERT INTO commits (product_name, price) VALUES (:product_name, :price)");
                    $stmt->execute([
                        ':product_name' => $productName,
                        ':price' => $price
                    ]);

                    // Амжилттай нэмсэн мессежтэйгээр буцах
                    header('Location: commit.php?success=1');
                    exit;
                } catch (PDOException $e) {
                    echo '<div style="text-align: center; margin-top: 50px;">';
                    echo '<h2>データベースエラー: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES) . '</h2>';
                    echo '</div>';
                }
            } else {
                header('Location: commit.php?error=1');
                exit;
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