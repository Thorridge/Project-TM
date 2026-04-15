<?php
// backend/retour_pret.php
session_start();
header('Content-Type: application/json');
require_once 'connect.php';

$idPret = intval($_POST['idPret'] ?? 0);

if ($idPret === 0) {
    echo json_encode(['success' => false, 'message' => 'ID prêt manquant.']);
    exit;
}

// Marquer le prêt comme retourné
$stmt = $pdo->prepare("
    UPDATE pret 
    SET date_retour_reelle = NOW() 
    WHERE idPret = :idPret
");
$stmt->execute(['idPret' => $idPret]);

// Mettre le statut de l'objet à "Disponible"
$stmt = $pdo->prepare("
    UPDATE objet 
    SET idStatut = (SELECT idStatut FROM statut_reference WHERE libelle = 'Disponible' LIMIT 1)
    WHERE idObjet = (SELECT idObjet FROM pret WHERE idPret = :idPret)
");
$stmt->execute(['idPret' => $idPret]);

echo json_encode(['success' => true, 'message' => 'Retour enregistré !']);
?>
