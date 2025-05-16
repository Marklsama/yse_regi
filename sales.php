<?php
require_once 'db.php'; // Өгөгдлийн сантай холбох

// Огноогоор шүүх
$where = '';
$params = [];
if (!empty($_GET['date'])) {
    $where = 'WHERE sale_date = :sale_date';
    $params[':sale_date'] = $_GET['date'];
}

$sql = "SELECT * FROM sales_items $where ORDER BY sale_date DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$salesItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Устгах үйлдэл
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_ids'])) {
    $ids = $_POST['delete_ids'];
    if (!empty($ids)) {
        $in = str_repeat('?,', count($ids) - 1) . '?';
        $pdo->prepare("DELETE FROM sales_items WHERE id IN ($in)")->execute($ids);
        header("Location: sales.php" . (!empty($_GET['date']) ? '?date=' . urlencode($_GET['date']) : ''));
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>売上</title>
  <link rel="stylesheet" href="css/style.css" />
  <style>
    .sales-page {
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
    .filter-form {
      margin-bottom: 1.5rem;
      display: flex;
      justify-content: center;
      gap: 1rem;
      align-items: center;
    }
    .filter-form label {
      margin-right: 0.5rem;
    }
    .filter-form input[type="date"] {
      padding: 0.5rem;
      border-radius: 4px;
      border: 1px solid #ccc;
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
      border: none;
      cursor: pointer;
    }
    .btn-red:hover {
      background-color: #c82333;
    }
    .sales-table {
      width: 100%;
      margin-top: 1.5rem;
      border-collapse: collapse;
    }
    .sales-table th, .sales-table td {
      border: 1px solid #ccc;
      padding: 0.75rem;
      text-align: center;
    }
    .sales-table th {
      background-color: #f4f4f4;
    }
    .back-link {
      margin-top: 2rem;
      display: inline-block;
    }
  </style>
</head>

<body>
  <div class="sales-page">
    <h1>売上</h1>

    <!-- Filter Form -->
    <form method="get" action="sales.php" class="filter-form">
      <label for="date">日付:</label>
      <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($_GET['date'] ?? '', ENT_QUOTES); ?>" />
      <button type="submit" class="btn-red" style="margin-top:0;">検索</button>
    </form>

    <!-- Sales Data Table with delete -->
    <form method="post" action="sales.php<?php echo !empty($_GET['date']) ? '?date=' . urlencode($_GET['date']) : ''; ?>">
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
          if (!empty($salesItems)) {
              foreach ($salesItems as $item) {
                  echo '<tr>';
                  echo '<td><input type="checkbox" name="delete_ids[]" value="' . htmlspecialchars($item['id'], ENT_QUOTES) . '" /></td>';
                  echo '<td>' . htmlspecialchars($item['sale_date'], ENT_QUOTES) . '</td>';
                  echo '<td>' . htmlspecialchars($item['product_name'], ENT_QUOTES) . '</td>';
                  echo '<td>' . htmlspecialchars($item['quantity'], ENT_QUOTES) . '</td>';
                  echo '<td>' . htmlspecialchars($item['amount'], ENT_QUOTES) . '</td>';
                  echo '</tr>';
              }
          } else {
              echo '<tr><td colspan="5">データがありません。</td></tr>';
          }
          ?>
        </tbody>
      </table>
      <button type="submit" class="btn-red">選択したデータを削除</button>
    </form>

    <a href="index.php" class="btn-red back-link">戻る</a>
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