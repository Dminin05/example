<?php
session_start();
require_once 'config.php'; 
header('Content-Type: application/json');

// Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Пользователь не авторизован']);
    exit;
}

try {
    // Получение заявок пользователя
    $stmt = $pdo->query("
        SELECT 
            u.fio,
            a.id,
            a.service_date, 
            a.service_time, 
            a.service_type, 
            a.other_service_text, 
            a.address, 
            a.phone, 
            a.payment_method,
            a.created_at,
            a.status,
            a.cancel_reason 
        FROM applications a
        JOIN users u on u.id = a.user_id
        ORDER BY created_at DESC
    ");
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($requests);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Ошибка базы данных: ' . $e->getMessage()]);
}