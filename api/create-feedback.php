<?php
require_once 'config.php';

header('Content-Type: application/json');

try {
    $email = $_POST['email'] ?? '';
    $text  = $_POST['message'] ?? '';

    if ($email && $text) {
        $stmt = $pdo->prepare("INSERT INTO feedbacks (email, text) VALUES (:email, :text)");
        $stmt->execute([
            ':email' => $email,
            ':text'  => $text
        ]);
        echo "Спасибо! Ваше сообщение отправлено.";
    } else {
        echo "Пожалуйста, заполните все поля.";
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo "Ошибка при подключении к базе данных: " . $e->getMessage();
}