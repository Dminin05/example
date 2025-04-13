<?php

$host = 'localhost';
$db = 'test_php';
$user = 'root'; 
$pass = ''; 

try {
    // Создание подключения к базе данных
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Ошибка подключения к БД: " . $e->getMessage();
}

?>