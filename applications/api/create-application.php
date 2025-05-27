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

// Проверка метода
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Метод не разрешён']);
    exit;
}

// Получение данных из запроса
$input = json_decode(file_get_contents('php://input'), true);

// Валидация телефона
if (!preg_match('/^\\+7\\(\\d{3}\\)-\\d{3}-\\d{2}-\\d{2}$/', $input['phone'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Неверный формат телефона']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO applications (
        user_id, address, phone, service_date, service_time, service_type, other_service_text, payment_method, created_at
    ) VALUES (
        :user_id, :address, :phone, :service_date, :service_time, :service_type, :other_service_text, :payment_method, NOW()
    )");

    $stmt->execute([
        'user_id' => $_SESSION['user_id'],
        'address' => $input['address'],
        'phone' => $input['phone'],
        'service_date' => $input['date'],
        'service_time' => $input['time'],
        'service_type' => $input['service'],
        'other_service_text' => $input['service'] === 'other' ? $input['otherServiceText'] : null,
        'payment_method' => $input['payment']
    ]);

    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Ошибка при сохранении заявки: ' . $e->getMessage()]);
}