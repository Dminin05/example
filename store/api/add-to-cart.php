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
    $good_id = $_POST['good_id'] ?? null;

    if (!$good_id) {
        http_response_code(400);
        echo json_encode(['error' => 'Не передан ID товара']);
        exit;
    }

    // 1. Найти или создать корзину
    $stmt = $pdo->prepare("SELECT id FROM cart WHERE user_id = :user_id");
    $stmt->execute([':user_id' => $user_id]);
    $cart = $stmt->fetch();

    if (!$cart) {
        $stmt = $pdo->prepare("INSERT INTO cart (user_id) VALUES (:user_id)");
        $stmt->execute([':user_id' => $user_id]);
        $cart_id = $pdo->lastInsertId();
    } else {
        $cart_id = $cart['id'];
    }

    // 2. Проверка — есть ли уже этот товар в корзине
    $stmt = $pdo->prepare("SELECT quantity FROM cart_goods WHERE cart_id = :cart_id AND good_id = :good_id");
    $stmt->execute([':cart_id' => $cart_id, ':good_id' => $good_id]);
    $existing = $stmt->fetch();

    if ($existing) {
        // 3. Обновляем количество
        $stmt = $pdo->prepare("UPDATE cart_goods SET quantity = quantity + 1 WHERE cart_id = :cart_id AND good_id = :good_id");
        $stmt->execute([':cart_id' => $cart_id, ':good_id' => $good_id]);
    } else {
        // 4. Вставляем новый товар
        $stmt = $pdo->prepare("INSERT INTO cart_goods (cart_id, good_id, quantity) VALUES (:cart_id, :good_id, 1)");
        $stmt->execute([':cart_id' => $cart_id, ':good_id' => $good_id]);
    }

    echo json_encode(['success' => true, 'message' => 'Товар добавлен в корзину']);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Ошибка при работе с базой: ' . $e->getMessage()]);
}