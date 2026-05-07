<?php
// backend/delete_objet.php — supprime un objet
session_start();
header('Content-Type: application/json');
require_once 'connect.php';

$idObjet = intval($_POST['idObjet'] ?? 0);

if ($idObjet === 0) {
    echo json_encode(['success' => false, 'message' => 'ID manquant.']);
    exit;
}

// Supprimer d'abord les prêts liés (contrainte FK)
$stmt = $pdo->prepare("DELETE FROM pret WHERE idObjet = :id");
$stmt->execute(['id' => $idObjet]);

// Supprimer l'objet
$stmt = $pdo->prepare("DELETE FROM objet WHERE idObjet = :id");
$stmt->execute(['id' => $idObjet]);

echo json_encode(['success' => true, 'message' => 'Objet supprimé avec succès !']);
?>