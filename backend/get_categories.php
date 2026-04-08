<?php
// backend/get_categories.php
session_start();
header('Content-Type: application/json');
require_once 'connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Non connecté']);
    exit;
}

$stmt = $pdo->query("SELECT idCategorie, nom FROM categorie ORDER BY nom");
echo json_encode($stmt->fetchAll());
?>