<?php
require_once 'db.php';

// Огноогоор шүүх
$where = '';
$params = [];
if (!empty($_GET['date'])) {
    $where = 'WHERE DATE(sale_date) = :date';
    $params[':date'] = $_GET['date'];
}

$sql = "SELECT * FROM sales_items $where ORDER BY sale_date DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$salesItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>売上</title>
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>
  <div class="main-container">
    <div class="big-title">売上</div>
    <!-- Filter Form -->
    <form method="get" action="sales.php" class="filter-form">
      <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($_GET['date'] ?? '', ENT_QUOTES); ?>" class="barcode-input" style="max-width:200px;" />
      <button type="submit" class="btn-red" style="margin-top:0;">検索</button>
      <a href="sales.php" class="btn-red">リセット</a>
    </form>
    <!-- sales_items жагсаалт -->
    <form action="delete.php" method="post">
      <table class="sales-table">
        <thead>
          <tr>
            <th><input type="checkbox" id="select-all"></th>
            <th>ID</th>
            <th>商品名</th>
            <th>数量</th>
            <th>金額</th>
            <th>登録日</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($salesItems)): ?>
            <?php foreach ($salesItems as $sale): ?>
              <tr>
                <td>
                  <input type="checkbox" name="delete_ids[]" value="<?= $sale['id'] ?>">
                </td>
                <td><?= $sale['id'] ?></td>
                <td><?= htmlspecialchars($sale['product_name']) ?></td>
                <td><?= $sale['quantity'] ?></td>
                <td><?= $sale['price'] ?></td>
                <td><?= $sale['sale_date'] ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="6">データがありません。</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
      <input type="hidden" name="target" value="sales">
      <button type="submit" class="btn-red">選択したデータを削除</button>
    </form>
    <div class="actions">
      <a href="index.php" class="btn-red">戻る</a>
    </div>
  </div>
  <script>
    // Бүх checkbox-ийг сонгох/цуцлах
    document.getElementById('select-all').addEventListener('change', function () {
      const checkboxes = document.querySelectorAll('input[name="delete_ids[]"]');
      checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });
  </script>
</body>
</html>