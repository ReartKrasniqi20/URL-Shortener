<?php
require 'db.php';


$expiredLinks = $pdo->query("
    SELECT id, short_code 
    FROM short_urls 
    WHERE expires_at IS NOT NULL AND expires_at <= NOW()
")->fetchAll(PDO::FETCH_ASSOC);

foreach ($expiredLinks as $link) {
    $shortCode = $link['short_code'];

    $apiUrl = "https://short.link/myapi/urls/$shortCode"; 

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_exec($ch);
    curl_close($ch);
}

$stmt = $pdo->query("
    SELECT *, 
           (expires_at IS NOT NULL AND expires_at <= NOW()) AS is_expired
    FROM short_urls 
    ORDER BY created_at DESC
");
$urls = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($urls);
