<?php
session_start();
require_once 'config.php';
header('Content-Type: application/json');

// Получение и декодирование JSON
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    http_response_code(400);
    echo json_encode(['message' => 'Неверный JSON']);
    exit;
}

$fio = $input['fio'];
$phone = $input['phone'];
$email = $input['email'];
$login = $input['login'];
$password = password_hash($input['password'], PASSWORD_DEFAULT); // Хешируем пароль

// Проверка на существующего пользователя
$stmt = $pdo->prepare("SELECT id FROM users WHERE login = :login OR email = :email");
$stmt->execute(['login' => $login, 'email' => $email]);
if ($stmt->fetch()) {
    http_response_code(409);
    echo json_encode(['message' => 'Пользователь с таким логином или email уже существует']);
    exit;
}

// Вставка пользователя
$insert = $pdo->prepare("
    INSERT INTO users (fio, phone, email, login, password, role)
    VALUES (:fio, :phone, :email, :login, :password, :role)
");

try {
    $insert->execute([
        'fio' => $fio,
        'phone' => $phone,
        'email' => $email,
        'login' => $login,
        'password' => $password,
        'role' => 'USER'
    ]);

    // Получение ID последней вставки
    $user_id = $pdo->lastInsertId();
    $_SESSION['user_id'] = $user_id;

    // Получение полной записи по ID
    $stmt = $pdo->prepare("SELECT role FROM users WHERE id = :id");
    $stmt->execute(['id' => $user_id]);
    $user = $stmt->fetch();

    $_SESSION['role'] = $user['role'];

    echo json_encode(['message' => 'Пользователь зарегистрирован']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['message' => $e]);
}