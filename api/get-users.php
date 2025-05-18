<?php
session_start();
require_once 'config.php';
require_once 'functions/get-user-role-function.php';

header('Content-Type: application/json'); // Указываем, что ответ — JSON

try {
    $role = getUserRoleById($pdo, $_SESSION['user_id']);
    if ($role === 'ADMIN') {
        $stmt = $pdo->query("SELECT id, first_name, last_name, email, role FROM users");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($users);
    } else {
        http_response_code(403); // Устанавливаем код ошибки
        echo json_encode(['error' => 'Недостаточно прав']);
    }

    
} catch (PDOException $e) {
    http_response_code(500); // Устанавливаем код ошибки
    echo json_encode(['error' => $e->getMessage()]);
}