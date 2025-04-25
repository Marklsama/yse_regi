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

    .price-input {
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
  </style>
</head>

<body>
  <div class="commit-page">
    <h1>計上</h1>
    <form action="update.php" method="post">
      <input type="text" name="price" placeholder="金額を入力" class="price-input" />
      <button type="submit" name="submit_type" value="commit" class="btn-red">送信</button>
    </form>
    <a href="index.php" class="btn-red">戻る</a>
  </div>
</body>

</html>