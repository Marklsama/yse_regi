<?php
require_once 'db.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'cashier';
    if ($username && $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            $stmt->execute([$username, $hash, $role]);
            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = "このユーザー名は既に登録されています。";
            } else {
                $error = "登録時にエラーが発生しました: " . $e->getMessage();
            }
        }
    } else {
        $error = "全ての項目を入力してください。";
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <title>新規登録</title>
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>
  <div class="main-container">
    <h2>新規登録</h2>
    <?php if (!empty($error)): ?>
      <div style="color:red;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post">
      <input type="text" name="username" placeholder="ユーザー名" class="barcode-input" required>
      <input type="password" name="password" placeholder="パスワード" class="barcode-input" required>
      <select name="role" class="barcode-input">
        <option value="admin">Admin</option>
        <option value="cashier">User</option>
      </select>
      <button type="submit" class="btn-red">登録</button>
    </form>
    <div style="margin-top:1rem;">
      <a href="index.php" class="btn-red">戻る</a>
    </div>
  </div>
</body>
</html>