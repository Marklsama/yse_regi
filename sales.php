<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>売上</title>
  <link rel="stylesheet" href="css/style.css" />
  <style>
    /* Custom styles for sales page */
    .sales-page {
      max-width: 800px;
      margin: 0 auto;
      padding: 20px;
      font-family: Arial, sans-serif;
    }

    h1 {
      text-align: center;
      margin-bottom: 20px;
    }

    .filter-form {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
    }

    .filter-form div {
      display: flex;
      flex-direction: column;
    }

    .sales-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    .sales-table th,
    .sales-table td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: center;
    }

    .sales-table th {
      background-color: #e74c3c;
    }

    .btn-red {
      display: inline-block;
      padding: 10px 20px;
      color: #fff;
      background-color: #e74c3c;
      text-decoration: none;
      text-align: center;
      border-radius: 5px;
    }

    .btn-red:hover {
      background-color: #c0392b;
    }
  </style>
</head>

<body>
  <div class="sales-page">
    <h1>売上</h1>

    <!-- Filter Form -->
    <form method="get" action="sales.php" class="filter-form">
      <div>
        <label for="date">日付で絞り込む:</label>
        <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($_GET['date'] ?? '', ENT_QUOTES); ?>" />
      </div>
      <div>
        <label for="month">月で絞り込む:</label>
        <input type="month" id="month" name="month" value="<?php echo htmlspecialchars($_GET['month'] ?? '', ENT_QUOTES); ?>" />
      </div>
      <button type="submit" class="btn-red">検索</button>
    </form>

    <!-- Sales Data Table -->
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
          <?php if (!empty($sales)): ?>
            <?php foreach ($sales as $sale): ?>
              <tr>
                <td><input type="checkbox" name="delete_ids[]" value="<?= htmlspecialchars($sale['id'], ENT_QUOTES) ?>" /></td>
                <td><?= htmlspecialchars($sale['sale_at'], ENT_QUOTES) ?></td>
                <td><?= htmlspecialchars($sale['receipt_no'], ENT_QUOTES) ?></td>
                <td><?= htmlspecialchars($sale['amount'], ENT_QUOTES) ?>円</td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="5">該当する売上データがありません。</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
      <button type="submit" class="btn-red">選択したデータを削除</button>
    </form>

    <a href="index.php" class="btn-red">戻る</a>
  </div>

  <script>
    document.getElementById('select-all').addEventListener('change', function () {
      const checkboxes = document.querySelectorAll('input[name="delete_ids[]"]');
      checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });
  </script>
</body>

</html>