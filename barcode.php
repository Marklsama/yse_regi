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

    <!-- Баркод оруулах хэсэг -->
    <form method="post" action="add_barcode.php" class="barcode-form">
      <input type="text" name="barcode" placeholder="バーコードを入力" class="barcode-input" required />
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
          // Жишээ баркодын өгөгдөл
          $barcodes = [
            ['id' => 1, 'barcode' => '123456789', 'product' => '商品A', 'quantity' => 2],
            ['id' => 2, 'barcode' => '987654321', 'product' => '商品B', 'quantity' => 1],
          ];

          // Баркодын өгөгдлийг харуулах
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