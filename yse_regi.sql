CREATE TABLE sales (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    sale_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    amount INT NOT NULL,
    receipt_no VARCHAR(50) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
<?php
// MySQL холболтын тохиргоо
$host = 'localhost';
$dbname = 'yse_regi'; // Өгөгдлийн сангийн нэр
$username = 'root'; // MySQL хэрэглэгчийн нэр
$password = ''; // MySQL нууц үг

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("データベース接続に失敗しました: " . $e->getMessage());
}
?>
