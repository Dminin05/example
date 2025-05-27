<?php
session_start();
require_once 'config.php';
header('Content-Type: application/json');

// Получаем JSON
$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    http_response_code(400);
    echo json_encode(['message' => 'Неверный JSON']);
    exit;
}

$login = $input['login'] ?? '';
$password = $input['password'] ?? '';

if (empty($login) || empty($password)) {
    http_response_code(400);
    echo json_encode(['message' => 'Введите логин и пароль']);
    exit;
}

// Получаем пользователя
$stmt = $pdo->prepare("SELECT * FROM users WHERE login = :login LIMIT 1");
$stmt->execute(['login' => $login]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    http_response_code(401);
    echo json_encode(['message' => 'Неверный логин или пароль']);
    exit;
}

$_SESSION['user_id'] = $user['id'];
$_SESSION['role'] = $user['role'];

echo json_encode(['role' => $user['role']]);