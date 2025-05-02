<?php
$host = 'localhost';
$dbname = 'yse_regi'; // Өгөгдлийн сангийн нэр
$username = 'root'; // MySQL хэрэглэгчийн нэр
$password = 'root'; // MySQL нууц үг

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("データベース接続に失敗しました: " . $e->getMessage());
}
?>