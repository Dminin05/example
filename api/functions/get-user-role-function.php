<?php
require_once 'config.php'; 

// Функция получения роли пользователя по ID
function getUserRoleById(PDO $pdo, int $userId): ?string {
    $stmt = $pdo->prepare('SELECT role FROM users WHERE id = :id');
    $stmt->execute(['id' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    return $user ? $user['role'] : null;
}