<?php
session_start();
header('Content-Type: application/json');
require_once 'connect.php';

$idObjet       = intval($_POST['idObjet'] ?? 0);
$nom           = trim($_POST['nom'] ?? '');
$idCategorie   = intval($_POST['idCategorie'] ?? 0);
$idNiveau      = intval($_POST['idNiveau'] ?? 0);
$idStatut      = intval($_POST['idStatut'] ?? 0);
$infoRangement = trim($_POST['infoRangement'] ?? '');
$infoPlus      = trim($_POST['infoPlus'] ?? '');

if ($idObjet === 0 || empty($nom) || $idCategorie === 0 || $idNiveau === 0 || $idStatut === 0) {
    echo json_encode(['success' => false, 'message' => 'Champs obligatoires manquants.']);
    exit;
}

// Récupérer l'ancienne image
$stmt = $pdo->prepare("SELECT photo FROM objet WHERE idObjet = ?");
$stmt->execute([$idObjet]);
$oldPhoto = $stmt->fetchColumn();

$newPhotoName = $oldPhoto;

// Vérifier si une image a été envoyée
if (isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {

    // Gestion des erreurs d'upload
    if ($_FILES['photo']['error'] !== UPLOAD_ERR_OK) {

        if ($_FILES['photo']['error'] === UPLOAD_ERR_INI_SIZE) {
            echo json_encode([
                'success' => false,
                'message' => 'L’image dépasse la taille maximale autorisée (2 Mo).'
            ]);
            exit;
        }

        echo json_encode([
            'success' => false,
            'message' => 'Erreur lors de l’upload de l’image (code ' . $_FILES['photo']['error'] . ').'
        ]);
        exit;
    }

    // Si on arrive ici → l'image est bien uploadée
    $uploadDir = __DIR__ . "/uploads/objets/";
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $extension = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (!in_array($extension, $allowed)) {
        echo json_encode(['success' => false, 'message' => 'Format image non autorisé']);
        exit;
    }

    // Nouveau nom unique
    $newPhotoName = uniqid("obj_", true) . "." . $extension;
    $destination = $uploadDir . $newPhotoName;

    // Upload brut
    if (!move_uploaded_file($_FILES['photo']['tmp_name'], $destination)) {
        echo json_encode(['success' => false, 'message' => 'Erreur upload image']);
        exit;
    }

    // 🔥 COMPRESSION DE L'IMAGE
    $info = getimagesize($destination);
    $mime = $info['mime'];

    switch ($mime) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($destination);
            imagejpeg($image, $destination, 70);
            break;

        case 'image/png':
            $image = imagecreatefrompng($destination);
            imagepng($image, $destination, 6);
            break;

        case 'image/webp':
            $image = imagecreatefromwebp($destination);
            imagewebp($image, $destination, 70);
            break;
    }

    // Supprimer l'ancienne image
    if (!empty($oldPhoto)) {
        $oldPath = $uploadDir . $oldPhoto;
        if (file_exists($oldPath)) unlink($oldPath);
    }
}

// Mise à jour DB
$stmt = $pdo->prepare("
    UPDATE objet
    SET nom = :nom,
        idCategorie = :idCategorie,
        idNiveau = :idNiveau,
        idStatut = :idStatut,
        infoRangement = :infoRangement,
        infoPlus = :infoPlus,
        photo = :photo
    WHERE idObjet = :idObjet
");

$stmt->execute([
    'nom'           => $nom,
    'idCategorie'   => $idCategorie,
    'idNiveau'      => $idNiveau,
    'idStatut'      => $idStatut,
    'infoRangement' => $infoRangement,
    'infoPlus'      => $infoPlus,
    'photo'         => $newPhotoName,
    'idObjet'       => $idObjet
]);

echo json_encode(['success' => true, 'message' => 'Objet mis à jour !']);
?>
