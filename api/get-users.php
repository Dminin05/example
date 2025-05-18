<?php
require_once 'config.php';

header('Content-Type: application/json'); // Указываем, что ответ — JSON

try {
    $stmt = $pdo->query("SELECT id, first_name, last_name, email FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($users);
} catch (PDOException $e) {
    http_response_code(500); // Устанавливаем код ошибки
    echo json_encode(['error' => $e->getMessage()]);
}