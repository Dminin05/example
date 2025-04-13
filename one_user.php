<?php
require_once 'db.php';

if (!isset($_GET['id'])) {
    die("ID не указан");
}

$id = (int)$_GET['id'];

$stmt = $pdo->prepare("SELECT id, username, password, image FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Пользователь не найден");
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Пользователь #<?= $user['id'] ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1><?= htmlspecialchars($user['username']) ?></h1>
    <p>ID: <?= $user['id'] ?></p>
    <p>Email: <?= htmlspecialchars($user['password']) ?></p>
    <img class="image" src="data:image/png;base64,<?= base64_encode($user['image']) ?>" alt="Фото">
</body>
</html>