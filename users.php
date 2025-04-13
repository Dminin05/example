<?php
require_once 'db.php';

try {
    $stmt = $pdo->query("SELECT id, username, password, image FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    include 'users_view.php';
} catch (PDOException $e) {
    echo "Ошибка подключения к БД: " . $e->getMessage();
}
?>