<head>
    <link rel="stylesheet" href="style.css">
</head>

<?php

require_once 'db.php';

try {
    // Выполнение SQL-запроса
    $stmt = $pdo->query("SELECT id, username, password FROM users");

    echo "<h2>Список пользователей:</h2>";
    echo "<ul class='without_point'>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<li>ID: {$row['id']} | Имя: {$row['username']} | Email: {$row['password']}</li>";
    }
    echo "</ul>";

} catch (PDOException $e) {
    echo "Ошибка подключения к БД: " . $e->getMessage();
}
?>
