<?php
ini_set('session.cookie_path', '/');
session_start();
header('Content-Type: application/json');

// Chemin corrigé
require_once __DIR__ . '/../../config/connect.php';

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
