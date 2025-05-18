<?php
require_once 'config.php';
require_once 'functions/get-user-role-function.php';
session_start();

// Предположим, ID пользователя хранится в сессии
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Неавторизован']);
    exit;
}

$userId = (int)$_SESSION['user_id'];

$role = getUserRoleById($pdo, $userId);

if ($role === null) {
    http_response_code(404);
    echo json_encode(['error' => 'Пользователь не найден']);
    exit;
}

header('Content-Type: application/json');
echo json_encode(['role' => $role]);