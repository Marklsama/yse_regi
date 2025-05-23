<?php
session_start();
require_once 'db.php';

// Нэвтрэх логик
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'role' => $user['role']
        ];
        header("Location: index.php");
        exit;
    } else {
        $login_error = "ユーザー名またはパスワードが間違っています。";
    }
}

// Гарах
if (isset($_GET['logout'])) {
    unset($_SESSION['user']);
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Byte&Bill</title>
  <link rel="stylesheet" href="css/style.css" />
</head>

<body>
  <div class="main-container">
    <?php if (empty($_SESSION['user'])): ?>
      <div class="login-box">
        <h2>ログイン</h2>
        <?php if (!empty($login_error)): ?>
          <div style="color:red;"><?= htmlspecialchars($login_error) ?></div>
        <?php endif; ?>
        <form method="post" autocomplete="off">
          <label for="login-username" style="color:#ffe082;">ユーザー名</label>
          <input type="text" id="login-username" name="username" placeholder="ユーザー名" class="barcode-input" required autocomplete="username">
          <label for="login-password" style="color:#ffe082;">パスワード</label>
          <input type="password" id="login-password" name="password" placeholder="パスワード" class="barcode-input" required autocomplete="current-password">
          <button type="submit" name="login" class="btn-red">ログイン</button>
        </form>
        <div style="margin-top:1rem;">
          <a href="register.php" class="btn-red">新規登録（Admin/User）</a>
        </div>
      </div>
    <?php else: ?>
      <div style="text-align:right; margin-bottom:1rem;">
        ユーザー: <?= htmlspecialchars($_SESSION['user']['username']) ?> (<?= htmlspecialchars($_SESSION['user']['role']) ?>)
        <a href="index.php?logout=1" class="btn-red" style="margin-left:1rem;">ログアウト</a>
      </div>
      <div class="big-title">💵Byte&Bill💵</div>
      <form action="update.php" method="post" autocomplete="off">
        <!-- Дэлгэц дээрх тоог харуулах хэсэг -->
        <textarea id="display" name="price" class="display" readonly></textarea>

        <div class="buttons">
          <?php
          $buttons = [
            '1', '2', '3', 'AC',
            '4', '5', '6', '+',
            '7', '8', '9', '×',
            '0', '00', '/', '=',
            '.', '-', 'DEL', 'Tax'
          ];
          foreach ($buttons as $val) {
            $safeVal = htmlspecialchars($val, ENT_QUOTES);
            echo "<button type='button' onclick=\"handleClick('$safeVal', event)\">$val</button>";
          }
          ?>
        </div>

        <div class="actions">
          <a href="barcode.php" class="btn-red">バーコード入力</a>
          <a href="commit.php" class="btn-red">計上</a>
          <a href="sales.php" class="btn-red">売上</a>
        </div>
      </form>
    <?php endif; ?>
  </div>
  <script src="js/calc.js"></script>
</body>
</html>