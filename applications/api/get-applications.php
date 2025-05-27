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
    $userId = $_SESSION['user_id'];

    // Получение заявок пользователя
    $stmt = $pdo->prepare("
        SELECT 
            service_date, 
            service_time, 
            service_type, 
            other_service_text, 
            address, 
            phone, 
            payment_method,
            created_at
        FROM applications
        WHERE user_id = :user_id
        ORDER BY created_at DESC
    ");
    $stmt->execute(['user_id' => $userId]);

    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($requests);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Ошибка базы данных: ' . $e->getMessage()]);
}