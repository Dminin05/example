<?php
session_start();
require_once '../../api/config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Не авторизован']);
    exit;
}

try {
    $stmt = $pdo->query("
        SELECT id, created_at, status, total_price
        FROM orders
    ");
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($orders);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Ошибка сервера: ' . $e->getMessage()]);
}