<?php
require_once 'db.php'; // Өгөгдлийн сантай холбох

// Борлуулалтын өгөгдлийг авах
$stmt = $pdo->query("SELECT * FROM sales_items ORDER BY sale_date DESC");
$salesItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Борлуулалт хадгалах
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save') {
    $saleDate = $_POST['sale_date'] ?? null;
    $productName = $_POST['product_name'] ?? null;
    $quantity = $_POST['quantity'] ?? null;
    $amount = $_POST['amount'] ?? null;

    if ($saleDate && $productName && $quantity && $amount) {
        try {
            $stmt = $pdo->prepare("INSERT INTO sales_items (sale_date, product_name, quantity, amount) VALUES (:sale_date, :product_name, :quantity, :amount)");
            $stmt->execute([
                ':sale_date' => $saleDate,
                ':product_name' => $productName,
                ':quantity' => $quantity,
                ':amount' => $amount
            ]);
            header('Location: sales.php?success=1');
            exit;
        } catch (PDOException $e) {
            echo '<div style="text-align: center; margin-top: 50px;">';
            echo '<h2>データベースエラー: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES) . '</h2>';
            echo '</div>';
        }
    } else {
        header('Location: sales.php?error=1');
        exit;
    }
}

// Борлуулалт устгах
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $deleteIds = $_POST['delete_ids'] ?? [];

    if (!empty($deleteIds)) {
        try {
            $stmt = $pdo->prepare("DELETE FROM sales_items WHERE id IN (" . implode(',', array_map('intval', $deleteIds)) . ")");
            $stmt->execute();
            header('Location: sales.php?deleted=1');
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

    <!-- Борлуулалт нэмэх форм -->
    <form method="post" action="sales.php">
      <input type="hidden" name="action" value="save" />
      <label>日付: <input type="date" name="sale_date" required /></label>
      <label>商品名: <input type="text" name="product_name" required /></label>
      <label>数量: <input type="number" name="quantity" required /></label>
      <label>金額: <input type="number" name="amount" required /></label>
      <button type="submit">保存</button>
    </form>
    <!-- Sales Data Table -->
    <table class="sales-table">
      <thead>
        <tr>
          <th>日付</th>
          <th>商品名</th>
          <th>数量</th>
          <th>金額</th>
          <th>削除</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if (!empty($salesItems)) {
            foreach ($salesItems as $item) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($item['sale_date'], ENT_QUOTES) . '</td>';
                echo '<td>' . htmlspecialchars($item['product_name'], ENT_QUOTES) . '</td>';
                echo '<td>' . htmlspecialchars($item['quantity'], ENT_QUOTES) . '</td>';
                echo '<td>' . htmlspecialchars($item['amount'], ENT_QUOTES) . '</td>';
                echo '<td>';
                echo '<form method="post" action="sales.php" style="display:inline;">';
                echo '<input type="hidden" name="action" value="delete" />';
                echo '<input type="hidden" name="delete_ids[]" value="' . $item['id'] . '" />';
                echo '<button type="submit">削除</button>';
                echo '</form>';
                echo '</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="5">データがありません。</td></tr>';
        }
        ?>
      </tbody>
    </table>

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