<?php
// backend/get_objet.php — récupère un seul objet par ID
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/connect.php';

$id = intval($_GET['id'] ?? 0);

if ($id === 0) {
    echo json_encode(['error' => 'ID manquant']);
    exit;
}

$stmt = $pdo->prepare("
    SELECT o.*, c.nom AS categorie_nom, sr.libelle AS statut_nom,
           n.nom AS niveau_nom
    FROM objet o
    JOIN categorie c ON o.idCategorie = c.idCategorie
    JOIN statut_reference sr ON o.idStatut = sr.idStatut
    JOIN niveau n ON o.idNiveau = n.idNiveau
    WHERE o.idObjet = :id
");
$stmt->execute(['id' => $id]);
$objet = $stmt->fetch();

if (!$objet) {
    echo json_encode(['error' => 'Objet non trouvé']);
    exit;
}

echo json_encode($objet);
?>