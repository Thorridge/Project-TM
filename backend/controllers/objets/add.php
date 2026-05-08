<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/connect.php';

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

// -----------------------------
//  UPLOAD DE L'IMAGE
// -----------------------------
$photoName = null;

if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {

    $uploadDir = __DIR__ . "/../../uploads/objets/";

    // Créer le dossier si nécessaire
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Extension
    $extension = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (!in_array($extension, $allowed)) {
        echo json_encode(['success' => false, 'message' => 'Format d\'image non autorisé.']);
        exit;
    }

    // Nom unique
    $photoName = uniqid("obj_", true) . "." . $extension;

    // Déplacement
    $destination = $uploadDir . $photoName;

    if (!move_uploaded_file($_FILES['photo']['tmp_name'], $destination)) {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'upload de l\'image.']);
        exit;
    }
}

// -----------------------------
//  INSERTION EN BASE
// -----------------------------
$stmt = $pdo->prepare("
    INSERT INTO objet (nom, idCategorie, idNiveau, idStatut, infoPlus, infoRangement, FK_idUser, photo)
    VALUES (:nom, :idCategorie, :idNiveau, :idStatut, :infoPlus, :infoRangement, :FK_idUser, :photo)
");

$stmt->execute([
    'nom'           => $nom,
    'idCategorie'   => $idCategorie,
    'idNiveau'      => $idNiveau,
    'idStatut'      => $idStatut,
    'infoPlus'      => $infoPlus,
    'infoRangement' => $infoRangement,
    'FK_idUser'     => $FK_idUser,
    'photo'         => $photoName
]);

echo json_encode(['success' => true, 'message' => 'Objet ajouté avec succès !']);
?>
