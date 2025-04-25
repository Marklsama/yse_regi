<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>売上</title>
  <link rel="stylesheet" href="css/style.css" />
  <style>
    /* Загварын өөрчлөлт */
    body {
      font-family: Arial, sans-serif;
      background-color: #f9f9f9;
      margin: 0;
      padding: 0;
    }

    .sales-page {
      max-width: 800px;
      margin: 2rem auto;
      background: #fff;
      padding: 2rem;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    h1 {
      text-align: center;
      color: #333;
    }

    .filter-form {
      display: flex;
      justify-content: space-between;
      margin-bottom: 1.5rem;
    }

    .filter-form label {
      margin-right: 0.5rem;
      font-weight: bold;
    }

    .filter-form input {
      padding: 0.5rem;
      font-size: 1rem;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    .filter-form button {
      padding: 0.5rem 1rem;
      font-size: 1rem;
      color: #fff;
      background-color: #007bff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    .filter-form button:hover {
      background-color: #0056b3;
    }

    .sales-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 1rem;
    }

    .sales-table th,
    .sales-table td {
      border: 1px solid #ddd;
      padding: 0.75rem;
      text-align: center;
    }

    .sales-table th {
      background-color: #007bff;
      color: #fff;
    }

    .sales-table tr:nth-child(even) {
      background-color: #f2f2f2;
    }

    .sales-table tr:hover {
      background-color: #e9ecef;
    }

    .btn-red {
      display: inline-block;
      margin-top: 1rem;
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
  </style>
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
    <table class="sales-table">
      <thead>
        <tr>
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
          ['date' => '2025-04-25', 'product' => '商品A', 'quantity' => 2, 'amount' => 2000],
          ['date' => '2025-04-24', 'product' => '商品B', 'quantity' => 1, 'amount' => 1000],
          ['date' => '2025-04-23', 'product' => '商品C', 'quantity' => 3, 'amount' => 3000],
          ['date' => '2025-04-01', 'product' => '商品D', 'quantity' => 1, 'amount' => 1500],
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
            echo '<td>' . htmlspecialchars($sale['date'], ENT_QUOTES) . '</td>';
            echo '<td>' . htmlspecialchars($sale['product'], ENT_QUOTES) . '</td>';
            echo '<td>' . htmlspecialchars($sale['quantity'], ENT_QUOTES) . '</td>';
            echo '<td>' . htmlspecialchars($sale['amount'], ENT_QUOTES) . '円</td>';
            echo '</tr>';
          }
        } else {
          echo '<tr><td colspan="4">該当する売上データがありません。</td></tr>';
        }
        ?>
      </tbody>
    </table>

    <a href="index.php" class="btn-red">戻る</a>
  </div>
</body>

</html>