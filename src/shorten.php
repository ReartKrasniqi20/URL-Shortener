<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $originalUrl = trim($_POST['original_url']);

    
    $expiryInterval = !empty($_POST['expiry']) ? $_POST['expiry'] : "7 DAY";
    $expiryDate = date("Y-m-d H:i:s", strtotime("+" . $expiryInterval));

    
    $ch = curl_init("https://short.link/myapi/urls/shorten"); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        "url" => $originalUrl
    ]));
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);

    if (isset($data['result'])) {
        $shortCode = $data['result'];

        
        $sql = "INSERT INTO short_urls (original_url, short_code, expires_at, click_count)
                VALUES (:original_url, :short_code, :expiry, 0)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':original_url', $originalUrl);
        $stmt->bindParam(':short_code', $shortCode);
        $stmt->bindParam(':expiry', $expiryDate);
        $stmt->execute();
    } else {
        die("Failed to shorten with short.link API");
    }

    header("Location: index.php");
    exit;
}
