<?php
session_start();
require_once '../../api/config.php';
header('Content-Type: application/json');

// 1. Проверка метода запроса
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Метод не разрешён']);
    exit;
}

// 2. Получение и декодирование JSON-данных
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['id'], $input['status'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Отсутствуют необходимые параметры']);
    exit;
}

$orderId = (int) $input['id'];
$newStatus = trim($input['status']);

// 3. Проверка допустимых статусов
$validStatuses = ['Создан', 'Оплачен', 'Готов к выдаче'];
if (!in_array($newStatus, $validStatuses)) {
    http_response_code(400);
    echo json_encode(['error' => 'Недопустимый статус']);
    exit;
}

// 5. Обновление статуса
$stmt = $pdo->prepare("UPDATE orders SET status = :status WHERE id = :id");
$stmt->bindParam(':status', $newStatus);
$stmt->bindParam(':id', $orderId, PDO::PARAM_INT);

try {
    $stmt->execute();
    echo json_encode(['success' => true, 'message' => 'Статус обновлён']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Ошибка при обновлении статуса', 'details' => $e->getMessage()]);
}