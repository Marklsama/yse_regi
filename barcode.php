<?php
// Өгөгдлийн сангаас баркодын өгөгдлийг авах
require_once 'db.php';
$stmt = $pdo->query("SELECT * FROM barcodes ORDER BY created_at DESC");
$barcodes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>バーコード入力</title>
  <link rel="stylesheet" href="css/style.css" />
  <style>
    /* commit.php эсвэл index.php-гийн CSS-ийг энд хуулна */
    .commit-page {
      max-width: 600px;
      margin: 2rem auto;
      background: #fff;
      padding: 2rem;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      text-align: center;
      font-family: Arial, sans-serif;
    }
    h1 {
      color: #333;
      margin-bottom: 1.5rem;
    }
    .barcode-form {
      margin-bottom: 1.5rem;
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }
    .barcode-input {
      padding: 0.75rem;
      font-size: 1rem;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    .btn-red {
      display: inline-block;
      padding: 0.75rem 1.5rem;
      font-size: 1rem;
      color: #fff;
      background-color: #dc3545;
      text-decoration: none;
      border-radius: 4px;
      text-align: center;
    }
    .btn-red:hover {
      background-color: #c82333;
    }
    .sales-table {
      width: 100%;
      margin-top: 2rem;
      border-collapse: collapse;
    }
    .sales-table th,
    
    .sales-table td {
      padding: 0.75rem;
      border: 1px solid #ccc;
      text-align: left;
    }
    /* ... бусад CSS ... */
  </style>
</head>

<body>
  <div class="commit-page">
    <h1>バーコード入力</h1>

    <?php if (isset($_GET['success'])): ?>
      <div style="text-align: center; color: green; margin-top: 20px;">
        <strong>バーコードデータが正常に追加されました。</strong>
      </div>
    <?php endif; ?>

    <!-- Баркод оруулах хэсэг -->
    <button type="button" class="btn-red" id="scan-barcode-btn" style="margin-bottom:1rem;">バーコードをスキャン</button>
    <div id="barcode-scanner" style="display:none; margin-bottom:1rem;">
      <div id="reader" style="width:300px; margin:0 auto;"></div>
      <button type="button" class="btn-red" id="close-scanner-btn" style="margin-top:1rem;">閉じる</button>
    </div>
    <form method="post" action="add_barcode.php" class="barcode-form">
      <input type="text" name="barcode" id="barcode-input" placeholder="バーコードを入力" class="barcode-input" required />
      <input type="text" name="product" placeholder="商品名を入力" class="barcode-input" required />
      <input type="number" name="quantity" placeholder="数量を入力" class="barcode-input" min="1" required />
      <button type="submit" class="btn-red">追加</button>
    </form>

    <!-- Баркодын өгөгдлийг харуулах -->
    <form method="post" action="delete_barcodes.php">
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

  <script src="https://unpkg.com/html5-qrcode"></script>
  <script src="js/barcode-scanner.js"></script>
  <script>
    // Бүх checkbox-ийг сонгох/цуцлах функц
    document.getElementById('select-all').addEventListener('change', function () {
      const checkboxes = document.querySelectorAll('input[name="delete_ids[]"]');
      checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });
  </script>
</body>

</html>