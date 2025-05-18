<?php
session_start();
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

    // Вставить feedback с user_id
    $stmt = $pdo->prepare("INSERT INTO feedbacks (user_id, email, text) VALUES (:user_id, :email, :text)");
    $stmt->execute([
        ':user_id' => $_SESSION['user_id'],
        ':email'    => $email,
        ':text'    => $text
    ]);

    echo 'Спасибо! Ваше сообщение отправлено.';

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Ошибка при работе с базой: ' . $e->getMessage()]);
}