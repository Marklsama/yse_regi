<?php
require_once 'db.php';

// Бараа нэмэх
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $barcode = $_POST['barcode'] ?? '';
    $product = $_POST['product'] ?? '';
    $price = $_POST['price'] ?? 0;
    $stock = $_POST['stock'] ?? 0;
    if ($barcode && $product) {
        $stmt = $pdo->prepare("INSERT INTO products (barcode, product, price, stock) VALUES (?, ?, ?, ?)");
        $stmt->execute([$barcode, $product, $price, $stock]);
        header("Location: manage.php?success=1");
        exit;
    }
}

// Бүх барааны жагсаалт
$products = [];
try {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // products хүснэгт байхгүй бол алдаа гарна
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>管理ページ</title>
  <link rel="stylesheet" href="css/style.css" />
</head>

<body>
  <div class="manage-page">
    <h1>管理ページ</h1>

    <h2>計上データ</h2>
    <table class="sales-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>金額</th>
          <th>日時</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // データベース接続
        $dsn = 'mysql:host=localhost;dbname=ysereji;charset=utf8';
        $user = 'root';
        $password = '';
        try {
            $pdo = new PDO($dsn, $user, $password);
        } catch (PDOException $e) {
            echo 'データベース接続失敗: ' . $e->getMessage();
            exit;
        }

        // 計上データを取得
        $stmt = $pdo->query('SELECT * FROM commits ORDER BY created_at DESC');
        foreach ($stmt as $row) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['id'], ENT_QUOTES) . '</td>';
            echo '<td>' . htmlspecialchars($row['price'], ENT_QUOTES) . '円</td>';
            echo '<td>' . htmlspecialchars($row['created_at'], ENT_QUOTES) . '</td>';
            echo '</tr>';
        }
        ?>
      </tbody>
    </table>

    <h2>バーコード入力データ</h2>
    <table class="sales-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>商品名</th>
          <th>数量</th>
          <th>日時</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // バーコード入力データを取得
        $stmt = $pdo->query('SELECT * FROM barcodes ORDER BY created_at DESC');
        foreach ($stmt as $row) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['id'], ENT_QUOTES) . '</td>';
            echo '<td>' . htmlspecialchars($row['product'], ENT_QUOTES) . '</td>';
            echo '<td>' . htmlspecialchars($row['quantity'], ENT_QUOTES) . '</td>';
            echo '<td>' . htmlspecialchars($row['created_at'], ENT_QUOTES) . '</td>';
            echo '</tr>';
        }
        ?>
      </tbody>
    </table>

    <h2>商品管理</h2>
    <?php if (isset($_GET['success'])): ?>
      <div style="color:#4caf50;text-align:center;margin-bottom:1rem;">商品が追加されました。</div>
    <?php endif; ?>

    <!-- Шинэ бараа нэмэх форм -->
    <form method="post" class="barcode-form" style="margin-bottom:2rem;">
      <input type="hidden" name="add_product" value="1">
      <input type="text" name="barcode" placeholder="バーコード" class="barcode-input" required>
      <input type="text" name="product" placeholder="商品名" class="barcode-input" required>
      <input type="number" name="price" placeholder="金額" class="barcode-input" min="0" required>
      <input type="number" name="stock" placeholder="在庫数" class="barcode-input" min="0" required>
      <button type="submit" class="btn-red">商品を追加</button>
    </form>

    <!-- Бүх барааны жагсаалт -->
    <table class="sales-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>バーコード</th>
          <th>商品名</th>
          <th>金額</th>
          <th>在庫数</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($products)): ?>
          <?php foreach ($products as $row): ?>
            <tr>
              <td><?= htmlspecialchars($row['id']) ?></td>
              <td><?= htmlspecialchars($row['barcode']) ?></td>
              <td><?= htmlspecialchars($row['product']) ?></td>
              <td><?= htmlspecialchars($row['price']) ?>円</td>
              <td><?= htmlspecialchars($row['stock']) ?></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="5">商品データがありません。</td></tr>
        <?php endif; ?>
      </tbody>
    </table>

    <div class="actions" style="margin-top:2rem;">
      <a href="index.php" class="btn-red">レジ画面へ戻る</a>
    </div>
  </div>
</body>

</html>