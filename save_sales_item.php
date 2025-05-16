<?php
require_once 'db.php';
$data = json_decode(file_get_contents('php://input'), true);

if (is_array($data) && count($data) > 0) {
    try {
        $pdo->beginTransaction();
        foreach ($data as $item) {
            $stmt = $pdo->prepare("INSERT INTO sales_items (sale_date, product_name, quantity, amount) VALUES (CURDATE(), :product_name, :quantity, :amount)");
            $stmt->execute([
                ':product_name' => $item['name'],
                ':quantity' => $item['qty'],
                ':amount' => $item['amount']
            ]);
        }
        $pdo->commit();
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'No data']);
}
?>