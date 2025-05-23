<?php
// filepath: c:\MAMP\htdocs\yseregi\get_product.php
require_once 'db.php';
$barcode = $_GET['barcode'] ?? '';
if ($barcode) {
    $stmt = $pdo->prepare("SELECT product, price as unit_price FROM barcodes WHERE barcode = ?");
    $stmt->execute([$barcode]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        echo json_encode($row);
        exit;
    }
}
echo json_encode([]);