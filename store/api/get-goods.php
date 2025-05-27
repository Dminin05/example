<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json'); // Указываем, что ответ — JSON

try {
    $stmt = $pdo->query("SELECT * FROM goods");
    $goods = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($goods);
} catch (PDOException $e) {
    http_response_code(500); // Устанавливаем код ошибки
    echo json_encode(['error' => $e->getMessage()]);
}