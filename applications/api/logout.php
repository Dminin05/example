<?php
session_start();
session_unset();     // Удаляет все переменные сессии
session_destroy();   // Уничтожает саму сессию

http_response_code(200);
echo json_encode(['message' => 'Вы успешно вышли']);