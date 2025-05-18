<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$first_name = trim($data['first_name'] ?? '');
$last_name  = trim($data['last_name'] ?? '');
$email      = trim($data['email'] ?? '');
$password   = $data['password'] ?? '';

if (!$first_name || !$last_name || !$email || !$password) {
    http_response_code(400);
    echo json_encode(['error' => 'Все поля обязательны']);
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);

try {
    // Проверка уникальности email
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    if ($stmt->fetch()) {
        http_response_code(400);
        echo json_encode(['error' => 'Пользователь с таким email уже существует']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password, role) VALUES (?, ?, ?, ?, 'USER')");
    $stmt->execute([$first_name, $last_name, $email, $hash]);
    $user_id = $pdo->lastInsertId(); // Получаем ID нового пользователя
    $_SESSION['user_id'] = $user_id;
    http_response_code(200);
    echo json_encode(['message' => 'Регистрация успешна']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Ошибка при регистрации: ' . $e->getMessage()]);
}