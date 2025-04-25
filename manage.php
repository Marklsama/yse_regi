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
  </div>
</body>

</html>