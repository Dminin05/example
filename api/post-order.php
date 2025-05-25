<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$items = $input['items'] ?? [];
$totalPrice = $input['totalPrice'] ?? 0;

if (empty($items)) {
    http_response_code(400);
    echo json_encode(['error' => 'No items provided']);
    exit;
}

require_once 'config.php'; // подключение к БД

try {
    $pdo->beginTransaction();

    // 1. Вставляем заказ
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_price) VALUES (?, ?)");
    $stmt->execute([$_SESSION['user_id'], $totalPrice]);
    $orderId = $pdo->lastInsertId();

    // 2. Вставляем товары в заказ
    $stmt = $pdo->prepare("INSERT INTO order_item (order_id, good_id, quantity) VALUES (?, ?, ?)");

    foreach ($items as $item) {
        $stmt->execute([$orderId, $item['id'], $item['quantity']]);
    }

    $pdo->commit();

    $stmt = $pdo->prepare("SELECT id FROM cart WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $cartId = $stmt->fetchColumn();

    if ($cartId) {
        // Удаляем товары из корзины
        $stmt = $pdo->prepare("DELETE FROM cart_goods WHERE cart_id = ?");
        $stmt->execute([$cartId]);
    }

    echo json_encode(['success' => true, 'orderId' => $orderId]);

} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['error' => 'Ошибка при создании заказа: ' . $e->getMessage()]);
}