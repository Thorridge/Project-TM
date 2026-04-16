<?php
// backend/add_objet.php
session_start();
header('Content-Type: application/json');
require_once 'connect.php';

// Récupérer les données POST
$nom           = trim($_POST['nom']           ?? '');
$idCategorie   = intval($_POST['idCategorie'] ?? 0);
$idNiveau      = intval($_POST['idNiveau']    ?? 0);
$idStatut      = intval($_POST['idStatut']    ?? 0);
$infoPlus      = trim($_POST['infoPlus']      ?? '');
$infoRangement = trim($_POST['infoRangement'] ?? '');
$FK_idUser     = intval($_POST['FK_idUser']   ?? 0);

// Validation
if (empty($nom) || $idCategorie === 0 || $idNiveau === 0 || $idStatut === 0 || $FK_idUser === 0) {
    echo json_encode(['success' => false, 'message' => 'Champs obligatoires manquants.']);
    exit;
}

$stmt = $pdo->prepare("
    INSERT INTO objet (nom, idCategorie, idNiveau, idStatut, infoPlus, infoRangement, FK_idUser)
    VALUES (:nom, :idCategorie, :idNiveau, :idStatut, :infoPlus, :infoRangement, :FK_idUser)
");

$stmt->execute([
    'nom'           => $nom,
    'idCategorie'   => $idCategorie,
    'idNiveau'      => $idNiveau,
    'idStatut'      => $idStatut,
    'infoPlus'      => $infoPlus,
    'infoRangement' => $infoRangement,
    'FK_idUser'     => $FK_idUser
]);

echo json_encode(['success' => true, 'message' => 'Objet ajouté avec succès !']);
?>