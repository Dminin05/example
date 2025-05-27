<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(['error' => $_SESSION['role']]);
    exit;
}

http_response_code(200);
echo json_encode(['role' => $_SESSION['role']]);
