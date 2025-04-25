<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>売上</title>
  <link rel="stylesheet" href="css/style.css" />
</head>

<body>
  <div class="sales-page">
    <h1>売上</h1>

    <!-- Фильтр форм -->
    <form method="get" action="sales.php" class="filter-form">
      <div>
        <label for="date">日付で絞り込む:</label>
        <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($_GET['date'] ?? '', ENT_QUOTES); ?>" />
      </div>
      <div>
        <label for="month">月で絞り込む:</label>
        <input type="month" id="month" name="month" value="<?php echo htmlspecialchars($_GET['month'] ?? '', ENT_QUOTES); ?>" />
      </div>
      <button type="submit">フィルタ</button>
    </form>

    <!-- 売上データの表示 -->
    <form method="post" action="delete_sales.php">
      <table class="sales-table">
        <thead>
          <tr>
            <th><input type="checkbox" id="select-all" /></th>
            <th>日付</th>
            <th>商品名</th>
            <th>数量</th>
            <th>金額</th>
          </tr>
        </thead>
        <tbody>
          <?php
          // Жишээ борлуулалтын өгөгдөл
          $sales = [
            ['id' => 1, 'date' => '2025-04-25', 'product' => '商品A', 'quantity' => 2, 'amount' => 2000],
            ['id' => 2, 'date' => '2025-04-24', 'product' => '商品B', 'quantity' => 1, 'amount' => 1000],
          ];

          // Фильтр боловсруулах
          $dateFilter = $_GET['date'] ?? '';
          $monthFilter = $_GET['month'] ?? '';

          $filteredSales = array_filter($sales, function ($sale) use ($dateFilter, $monthFilter) {
            if ($dateFilter && $sale['date'] !== $dateFilter) {
              return false;
            }
            if ($monthFilter && strpos($sale['date'], $monthFilter) !== 0) {
              return false;
            }
            return true;
          });

          // Борлуулалтын өгөгдлийг харуулах
          if (!empty($filteredSales)) {
            foreach ($filteredSales as $sale) {
              echo '<tr>';
              echo '<td><input type="checkbox" name="delete_ids[]" value="' . htmlspecialchars($sale['id'], ENT_QUOTES) . '" /></td>';
              echo '<td>' . htmlspecialchars($sale['date'], ENT_QUOTES) . '</td>';
              echo '<td>' . htmlspecialchars($sale['product'], ENT_QUOTES) . '</td>';
              echo '<td>' . htmlspecialchars($sale['quantity'], ENT_QUOTES) . '</td>';
              echo '<td>' . htmlspecialchars($sale['amount'], ENT_QUOTES) . '円</td>';
              echo '</tr>';
            }
          } else {
            echo '<tr><td colspan="5">該当する売上データがありません。</td></tr>';
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