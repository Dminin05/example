<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

try {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Пользователь не авторизован']);
        exit;
    }

    $user_id = $_SESSION['user_id'];

    // 1. Найдём корзину пользователя
    $stmt = $pdo->prepare("SELECT id FROM cart WHERE user_id = :user_id");
    $stmt->execute([':user_id' => $user_id]);
    $cart = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$cart) {
        // Корзина пуста — нет записи
        echo json_encode([]);
        exit;
    }

    $cart_id = $cart['id'];

    // 2. Получим товары с их количеством из cart_goods + данные о товаре из goods
    $stmt = $pdo->prepare("
        SELECT g.id, g.title, g.price, g.image, cg.quantity
        FROM cart_goods cg
        JOIN goods g ON cg.good_id = g.id
        WHERE cg.cart_id = :cart_id
    ");

    $stmt->execute([':cart_id' => $cart_id]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($items);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Ошибка работы с базой: ' . $e->getMessage()]);
}