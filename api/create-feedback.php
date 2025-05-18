<?php
require_once 'config.php';

header('Content-Type: application/json');

try {
    $email = $_POST['email'] ?? '';
    $text  = $_POST['message'] ?? '';

    if (!$email || !$text) {
        http_response_code(400);
        echo 'Пожалуйста, заполните все поля.';
        exit;
    }

    // Найти пользователя по email
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if (!$user) {
        http_response_code(404);
        echo 'Пользователь с таким email не найден.';
        exit;
    }

    // Вставить feedback с user_id
    $stmt = $pdo->prepare("INSERT INTO feedbacks (user_id, text) VALUES (:user_id, :text)");
    $stmt->execute([
        ':user_id' => $user['id'],
        ':text'    => $text
    ]);

    echo 'Спасибо! Ваше сообщение отправлено.';

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Ошибка при работе с базой: ' . $e->getMessage()]);
}