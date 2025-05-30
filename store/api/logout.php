<?php
session_start();
session_unset();     // Удаляет все переменные сессии
session_destroy();   // Уничтожает саму сессию

// Для надёжности удалим сессионную cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

http_response_code(200);
echo json_encode(['message' => 'Вы успешно вышли']);