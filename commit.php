<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>計上</title>
  <link rel="stylesheet" href="css/style.css" />
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f9f9f9;
      margin: 0;
      padding: 0;
    }

    .commit-page {
      max-width: 600px;
      margin: 2rem auto;
      background: #fff;
      padding: 2rem;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    h1 {
      color: #333;
    }

    .input-field {
      width: 100%;
      padding: 0.75rem;
      font-size: 1rem;
      margin-top: 1rem;
      border: 1px solid #ccc;
      border-radius: 4px;
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

    table {
      width: 100%;
      margin-top: 2rem;
      border-collapse: collapse;
    }

    table th, table td {
      border: 1px solid #ccc;
      padding: 0.75rem;
      text-align: center;
    }

    table th {
      background-color: #f4f4f4;
    }
  </style>
</head>

<body>
  <div class="commit-page">
    <h1>計上</h1>
    <?php if (isset($_GET['success'])): ?>
      <div style="text-align: center; color: green; margin-top: 20px;">
        <strong>データが正常に追加されました。</strong>
      </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
      <div style="text-align: center; color: red; margin-top: 20px;">
        <strong>正しいデータを入力してください。</strong>
      </div>
    <?php endif; ?>

    <!-- Дүн болон барааны нэр оруулах хэсэг -->
    <form action="update.php" method="post">
      <input type="text" name="product_name" placeholder="商品名を入力" class="input-field" required />
      <input type="text" name="price" placeholder="金額を入力" class="input-field" required />
      <button type="submit" name="submit_type" value="commit" class="btn-red">送信</button>
    </form>

    <!-- Оруулсан дүнгүүдийг харуулах болон устгах хэсэг -->
    <form method="post" action="delete_commits.php">
      <table>
        <thead>
          <tr>
            <th><input type="checkbox" id="select-all" /></th>
            <th>商品名</th>
            <th>金額</th>
            <th>作成日時</th>
          </tr>
        </thead>
        <tbody>
          <?php
          require_once 'db.php';
          $stmt = $pdo->query("SELECT * FROM commits ORDER BY created_at DESC");
          $commits = $stmt->fetchAll(PDO::FETCH_ASSOC);

          if (!empty($commits)) {
            foreach ($commits as $commit) {
              echo '<tr>';
              echo '<td><input type="checkbox" name="delete_ids[]" value="' . htmlspecialchars($commit['id'], ENT_QUOTES) . '" /></td>';
              echo '<td>' . htmlspecialchars($commit['product_name'], ENT_QUOTES) . '</td>';
              echo '<td>' . htmlspecialchars($commit['price'], ENT_QUOTES) . '</td>';
              echo '<td>' . htmlspecialchars($commit['created_at'], ENT_QUOTES) . '</td>';
              echo '</tr>';
            }
          } else {
            echo '<tr><td colspan="4">データがありません。</td></tr>';
          }
          ?>
        </tbody>
      </table>
      <button type="submit" class="btn-red">選択したデータを削除</button>
    </form>

    <a href="index.php" class="btn-red">戻る</a>
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