<?php
require_once 'db.php'; // Өгөгдлийн сантай холбох

// Баркодын өгөгдлийг авах
$stmt = $pdo->query("SELECT * FROM barcodes ORDER BY created_at DESC");
$barcodes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Баркод нэмэх
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $barcode = $_POST['barcode'] ?? '';
    $product = $_POST['product'] ?? '';
    $quantity = $_POST['quantity'] ?? '';

    if (!empty($barcode) && !empty($product) && !empty($quantity)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO barcodes (barcode, product, quantity) VALUES (:barcode, :product, :quantity)");
            $stmt->execute([
                ':barcode' => $barcode,
                ':product' => $product,
                ':quantity' => $quantity
            ]);
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
        echo '</div>';
    }
}

// Баркод устгах
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $deleteIds = $_POST['delete_ids'] ?? [];

    if (is_array($deleteIds) && !empty($deleteIds)) {
        try {
            $placeholders = implode(',', array_fill(0, count($deleteIds), '?'));
            $stmt = $pdo->prepare("DELETE FROM barcodes WHERE id IN ($placeholders)");
            $stmt->execute($deleteIds);
            header('Location: barcode.php?deleted=1');
            exit;
        } catch (PDOException $e) {
            echo '<div style="text-align: center; margin-top: 50px;">';
            echo '<h2>データベースエラー: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES) . '</h2>';
            echo '</div>';
        }
    } else {
        echo '<div style="text-align: center; margin-top: 50px;">';
        echo '<h2>削除するデータが選択されていません。</h2>';
        echo '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>バーコード入力</title>
  <link rel="stylesheet" href="css/style.css" />
</head>

<body>
  <div class="sales-page">
    <h1>バーコード入力</h1>

    <?php if (isset($_GET['success'])): ?>
      <div style="text-align: center; color: green; margin-top: 20px;">
        <strong>バーコードデータが正常に追加されました。</strong>
      </div>
    <?php elseif (isset($_GET['deleted'])): ?>
      <div style="text-align: center; color: green; margin-top: 20px;">
        <strong>選択したデータが正常に削除されました。</strong>
      </div>
    <?php endif; ?>

    <!-- Баркод нэмэх форм -->
    <form method="post" action="barcode.php" class="barcode-form">
      <input type="hidden" name="action" value="add" />
      <input type="text" name="barcode" placeholder="バーコードを入力" class="barcode-input" required />
      <input type="text" name="product" placeholder="商品名を入力" class="barcode-input" required />
      <input type="number" name="quantity" placeholder="数量を入力" class="barcode-input" min="1" required />
      <button type="submit" class="btn-red">追加</button>
    </form>

    <!-- Баркодын өгөгдлийг харуулах -->
    <form method="post" action="barcode.php">
      <input type="hidden" name="action" value="delete" />
      <table class="sales-table">
        <thead>
          <tr>
            <th><input type="checkbox" id="select-all" /></th>
            <th>バーコード</th>
            <th>商品名</th>
            <th>数量</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if (!empty($barcodes)) {
            foreach ($barcodes as $barcode) {
              echo '<tr>';
              echo '<td><input type="checkbox" name="delete_ids[]" value="' . htmlspecialchars($barcode['id'], ENT_QUOTES) . '" /></td>';
              echo '<td>' . htmlspecialchars($barcode['barcode'], ENT_QUOTES) . '</td>';
              echo '<td>' . htmlspecialchars($barcode['product'], ENT_QUOTES) . '</td>';
              echo '<td>' . htmlspecialchars($barcode['quantity'], ENT_QUOTES) . '</td>';
              echo '</tr>';
            }
          } else {
            echo '<tr><td colspan="4">該当するバーコードデータがありません。</td></tr>';
          }
          ?>
        </tbody>
      </table>
      <button type="submit" class="btn-red">選択したデータを削除</button>
    </form>

    <a href="index.php" class="btn-red">戻る</a>
  </div>

  <script>
    // Бүх checkbox-ийг сонгох/цуцлах функц
    document.getElementById('select-all').addEventListener('change', function () {
      const checkboxes = document.querySelectorAll('input[name="delete_ids[]"]');
      checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });
  </script>
</body>

</html>