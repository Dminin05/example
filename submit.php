<?php

require 'db.php';

$name = $_POST['product_name'];
$ingredients = $_POST['product_ingredients'];
$price = $_POST['product_price'];

// Подготовка запроса
$stmt = $pdo->prepare("INSERT INTO products (product_name, product_ingredients, product_price) VALUES (:name, :ingredients, :price)");

// Выполнение запроса с привязкой параметров
$stmt->execute([
    ':name' => $name,
    ':ingredients' => $ingredients,
    ':price' => $price
]);

header('Location: index.php');
exit;

?>