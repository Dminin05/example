<?php
session_start();

$host = 'localhost';
$db = 'ukmhniji_m1';
$user = 'ukmhniji'; 
$pass = 'cjj91i'; 

$conn = new mysqli($host, $user, $pass, $db);

// Проверка соединения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Получение хэшированного пароля из базы данных
    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Проверка пароля
        if (password_verify($password, $hashed_password)) {
            // Успешный вход
            $_SESSION['username'] = $username; // Сохраняем имя пользователя в сессии
            echo "Добро пожаловать, " . $username . "!";
        } else {
            echo "Неверный пароль!";
        }
    } else {
        echo "Пользователь не найден!";
    }

    $stmt->close();
}
$conn->close();

// Обработка выхода
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {
    // Завершаем сессию
    session_destroy();
    header("Location: login.php"); // Перенаправляем на страницу входа
    exit();
}

?>

<form method="POST" action="">
    <input type="text" name="username" placeholder="Имя пользователя" required>
    <input type="password" name="password" placeholder="Пароль" required>
    <button type="submit">Войти</button>
</form>
