<?php

$host = 'localhost';
$db = 'test_php';
$user = 'root'; 
$pass = ''; 

$conn = new mysqli($host, $user, $pass, $db);

// Проверка соединения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Хэширование пароля
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Подготовка и выполнение запроса
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashed_password);

    if ($stmt->execute()) {
        // Перенаправление на страницу входа после успешной регистрации
        header("Location: login.php");
        exit(); // Завершаем выполнение скрипта после перенаправления
    } else {
        echo "Ошибка: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>

<form method="POST" action="">
    <input type="text" name="username" placeholder="Имя пользователя" required>
    <input type="password" name="password" placeholder="Пароль" required>
    <button type="submit">Зарегистрироваться</button>
</form>

