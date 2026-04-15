<?php
// backend/edit_objet.php — modifie un objet existant
session_start();
header('Content-Type: application/json');
require_once 'connect.php';

$idObjet       = intval($_POST['idObjet']       ?? 0);
$nom           = trim($_POST['nom']             ?? '');
$idCategorie   = intval($_POST['idCategorie']   ?? 0);
$idNiveau      = intval($_POST['idNiveau']      ?? 0);
$idStatut      = intval($_POST['idStatut']      ?? 0);
$infoPlus      = trim($_POST['infoPlus']        ?? '');
$infoRangement = trim($_POST['infoRangement']   ?? '');

if ($idObjet === 0 || empty($nom) || $idCategorie === 0 || $idNiveau === 0 || $idStatut === 0) {
    echo json_encode(['success' => false, 'message' => 'Champs obligatoires manquants.']);
    exit;
}

$stmt = $pdo->prepare("
    UPDATE objet 
    SET nom = :nom,
        idCategorie = :idCategorie,
        idNiveau = :idNiveau,
        idStatut = :idStatut,
        infoPlus = :infoPlus,
        infoRangement = :infoRangement
    WHERE idObjet = :idObjet
");

$stmt->execute([
    'nom'           => $nom,
    'idCategorie'   => $idCategorie,
    'idNiveau'      => $idNiveau,
    'idStatut'      => $idStatut,
    'infoPlus'      => $infoPlus,
    'infoRangement' => $infoRangement,
    'idObjet'       => $idObjet
]);

echo json_encode(['success' => true, 'message' => 'Objet modifié avec succès !']);
?>