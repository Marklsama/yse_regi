<?php
require_once 'db.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $barcode = $_POST['barcode'] ?? '';
    $product = $_POST['product'] ?? '';
    $quantity = $_POST['quantity'] ?? '';
    $price = $_POST['price'] ?? '';

    if (!empty($barcode) && !empty($product) && !empty($quantity) && $price !== '') {
        try {
            $stmt = $pdo->prepare("INSERT INTO barcodes (barcode, product, quantity, price) VALUES (:barcode, :product, :quantity, :price)");
            $stmt->execute([
                ':barcode' => $barcode,
                ':product' => $product,
                ':quantity' => $quantity,
                ':price' => $price
            ]);
            $message = "登録が完了しました。";
        } catch (PDOException $e) {
            $error = "データベースエラー: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
        }
    } else {
        $error = "すべてのフィールドを入力してください。";
    }
}

// Баркодын өгөгдлийг авах
$stmt = $pdo->query("SELECT * FROM barcodes ORDER BY created_at DESC");
$barcodes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <title>バーコード管理</title>
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>
  <div class="main-container">
    <h2>バーコード登録</h2>
    <?php if ($message): ?>
      <div style="color:green;"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
      <div style="color:red;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <div style="margin-bottom:1rem;">
      <button type="button" onclick="startScan()" class="btn-red">カメラでスキャン</button>
      <div id="qr-reader" style="width:300px; margin-top:1rem; display:none;"></div>
    </div>
    <form method="post" style="margin-bottom:2rem;">
      <input type="text" name="barcode" placeholder="バーコード" class="barcode-input" required>
      <input type="text" name="product" placeholder="商品名" class="barcode-input" required>
      <input type="number" name="unit_price" id="unit_price" placeholder="単価" class="barcode-input" min="0" required>
      <input type="number" name="quantity" id="quantity" placeholder="数量" class="barcode-input" min="1" required>
      <input type="number" name="price" id="price" placeholder="金額" class="barcode-input" min="0" required readonly>
      <button type="submit" class="btn-red">登録</button>
    </form>

    <h3>バーコード一覧</h3>
    <form action="delete.php" method="post">
      <table class="sales-table">
        <thead>
          <tr>
            <th><input type="checkbox" id="select-all"></th>
            <th>ID</th>
            <th>バーコード</th>
            <th>商品名</th>
            <th>数量</th>
            <th>金額</th>
            <th>登録日</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($barcodes)): ?>
            <?php foreach ($barcodes as $row): ?>
              <tr>
                <td><input type="checkbox" name="delete_ids[]" value="<?= htmlspecialchars($row['id']) ?>"></td>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['barcode']) ?></td>
                <td><?= htmlspecialchars($row['product']) ?></td>
                <td><?= htmlspecialchars($row['quantity']) ?></td>
                <td><?= htmlspecialchars($row['price']) ?></td>
                <td><?= htmlspecialchars($row['created_at']) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="7">データがありません。</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
      <input type="hidden" name="target" value="barcodes">
      <button type="submit" class="btn-red" style="margin-top:1rem;">選択したデータを削除</button>
    </form>
    <div class="actions" style="margin-top:2rem;">
      <a href="index.php" class="btn-red">戻る</a>
    </div>
  </div>
</body>
</html>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
let qrScanner = null;
function startScan() {
  document.getElementById('qr-reader').style.display = 'block';
  if (!qrScanner) {
    qrScanner = new Html5Qrcode("qr-reader");
  }
  qrScanner.start(
    { facingMode: "environment" },
    { fps: 10, qrbox: 250 },
    qrCodeMessage => {
      document.querySelector('input[name="barcode"]').value = qrCodeMessage;
      // Баркод уншмагц автоматаар барааны мэдээллийг бөглөх
      fetch('get_product.php?barcode=' + encodeURIComponent(qrCodeMessage))
        .then(res => res.json())
        .then(data => {
          if (data && data.product) {
            document.querySelector('input[name="product"]').value = data.product;
            document.getElementById('unit_price').value = data.unit_price;
            document.getElementById('quantity').value = 1;
            document.getElementById('price').value = data.unit_price;
          }
        });
      qrScanner.stop();
      document.getElementById('qr-reader').style.display = 'none';
    }
  );
}

// Бүх checkbox-ийг сонгох/цуцлах
document.getElementById('select-all').addEventListener('change', function () {
  const checkboxes = document.querySelectorAll('input[name="delete_ids[]"]');
  checkboxes.forEach(checkbox => checkbox.checked = this.checked);
});

document.getElementById('unit_price').addEventListener('input', updateTotalPrice);
document.getElementById('quantity').addEventListener('input', updateTotalPrice);

function updateTotalPrice() {
  const unit = parseInt(document.getElementById('unit_price').value) || 0;
  const qty = parseInt(document.getElementById('quantity').value) || 0;
  document.getElementById('price').value = unit * qty;
}
</script>