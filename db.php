<?php
require_once 'env.php';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8;", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("データベース接続に失敗しました: " . $e->getMessage());
}
?>
