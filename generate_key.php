<?php
$plain_key = bin2hex(random_bytes(16));
$api_key_hash = password_hash($plain_key, PASSWORD_DEFAULT);

$user_id = 1;

$db = new PDO("mysql:host=localhost;dbname=api_db", "root", "");
$stmt = $db->prepare("INSERT INTO api_keys (api_key, user_id, is_active) VALUES (:hash, :user_id, 1)");
$stmt->execute(['hash' => $api_key_hash, 'user_id' => $user_id]);

echo "<h2>Ваш новый API-ключ:</h2>";
echo "<div style='font-weight: bold; color: green; font-size: 20px;'>$plain_key</div>";
echo "<hr>";
echo "<i>Скопируйте ключ и храните в надёжном месте! Восстановить нельзя!</i>";
