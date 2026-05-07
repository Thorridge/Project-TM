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

// 1. Récupérer le nom de la photo AVANT suppression
$stmt = $pdo->prepare("SELECT photo FROM objet WHERE idObjet = :id");
$stmt->execute(['id' => $idObjet]);
$objet = $stmt->fetch(PDO::FETCH_ASSOC);

$photo = $objet['photo'] ?? null;

// 2. Supprimer d'abord les prêts liés (contrainte FK)
$stmt = $pdo->prepare("DELETE FROM pret WHERE idObjet = :id");
$stmt->execute(['id' => $idObjet]);

// 3. Supprimer l'objet
$stmt = $pdo->prepare("DELETE FROM objet WHERE idObjet = :id");
$stmt->execute(['id' => $idObjet]);

// 4. Supprimer l'image du dossier si elle existe
if (!empty($photo)) {
    $filePath = __DIR__ . "/uploads/objets/" . $photo;

    if (file_exists($filePath)) {
        unlink($filePath);
    }
}

echo json_encode(['success' => true, 'message' => 'Objet supprimé avec succès !']);
?>
