<?php
require 'db.php';

$pdo->exec("DELETE FROM short_urls WHERE expires_at IS NOT NULL AND expires_at <= NOW()");

$stmt = $pdo->query("SELECT * FROM short_urls 
                     WHERE expires_at IS NULL OR expires_at > NOW()
                     ORDER BY created_at DESC");
$urls = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($urls);
