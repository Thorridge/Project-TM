<?php
// backend/check_session.php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['connected' => false]);
    exit;
}

echo json_encode([
    'connected' => true,
    'user_id'   => $_SESSION['user_id'],
    'user_nom'  => $_SESSION['user_nom'] ?? '',
    'user_role' => $_SESSION['user_role'] ?? 'user'
]);
?>