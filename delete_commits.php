<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deleteIds = $_POST['delete_ids'] ?? [];

    if (!empty($deleteIds)) {
        try {
            $placeholders = implode(',', array_fill(0, count($deleteIds), '?'));
            $stmt = $pdo->prepare("DELETE FROM commits WHERE id IN ($placeholders)");
            $stmt->execute($deleteIds);

            header('Location: commit.php?deleted=1');
            exit;
        } catch (PDOException $e) {
            echo '<div style="text-align: center; margin-top: 50px;">';
            echo '<h2>データベースエラー: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES) . '</h2>';
            echo '</div>';
        }
    } else {
        header('Location: commit.php?error=1');
        exit;
    }
}
?>