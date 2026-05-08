<?php
header('Content-Type: application/json');

// Chemin correct vers connect.php
require_once __DIR__ . '/../../config/connect.php';

try {
    $stmt = $pdo->query("SELECT idCategorie, nom FROM categorie ORDER BY nom ASC");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($categories);
} catch (Exception $e) {
    echo json_encode([]);
}
