<?php
require 'db.php';


if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare("UPDATE short_urls SET click_count = click_count + 1 WHERE id = :id");
    $stmt->execute([':id' => $id]);
    exit;
}

echo "No ID provided.";
?>