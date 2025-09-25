<?php
require 'db.php';

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    // Get short_code before deleting
    $stmt = $pdo->prepare("SELECT short_code FROM short_urls WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $shortUrl = $stmt->fetch(PDO::FETCH_ASSOC);

    // Delete from local DB
    $stmt = $pdo->prepare("DELETE FROM short_urls WHERE id = :id");
    $stmt->execute([':id' => $id]);

    if ($shortUrl) {
        $shortCode = $shortUrl['short_code'];
        $apiUrl = "https://short.link/myapi/urls/$shortCode";

        
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_exec($ch);
        curl_close($ch);
    }

    header('Content-Type: application/json');
    echo json_encode(["success" => true, "id" => $id]);
} else {
    echo json_encode(["success" => false, "error" => "No ID provided"]);
}
