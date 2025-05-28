<?php
session_start();
require_once 'config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Неавторизованный доступ']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? null;
$status = $data['status'] ?? null;
$reason = $data['cancel_reason'] ?? null;

if (!$id || !$status) {
    http_response_code(400);
    echo json_encode(['error' => 'Некорректные данные']);
    exit;
}

$allowedStatuses = ['создана', 'в работе', 'выполнено', 'отменено'];
if (!in_array($status, $allowedStatuses, true)) {
    http_response_code(400);
    echo json_encode(['error' => 'Недопустимый статус']);
    exit;
}

if ($status === 'отменено' && empty($reason)) {
    http_response_code(400);
    echo json_encode(['error' => 'Причина отмены обязательна']);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE applications SET status = ?, cancel_reason = ? WHERE id = ?");
    $stmt->execute([$status, $status === 'отменено' ? $reason : null, $id]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Ошибка базы данных: ' . $e->getMessage()]);
}