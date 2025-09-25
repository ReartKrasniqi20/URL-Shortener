<?php
require 'db.php';

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM short_urls WHERE id = :id");
    $stmt->execute([':id' => $id]);

  
    header('Content-Type: application/json');
    echo json_encode(["success" => true, "id" => $id]);
} else {
    echo json_encode(["success" => false, "error" => "No ID provided"]);
}
?>
