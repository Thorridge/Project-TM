<?php
session_start();
header('Content-Type: application/json');
require_once 'connect.php';

function traiterImage(array $fichier, string $dossier): string|false {

    $maxWidth  = 1200;
    $qualite   = 80;
    $maxOctets = 300_000;

    $ext = strtolower(pathinfo($fichier['name'], PATHINFO_EXTENSION));
    $dest = $dossier . uniqid('img_') . '.webp';

    $src = match($ext) {
        'jpg', 'jpeg' => imagecreatefromjpeg($fichier['tmp_name']),
        'png'         => imagecreatefrompng($fichier['tmp_name']),
        'webp'        => imagecreatefromwebp($fichier['tmp_name']),
        default       => false
    };

    if (!$src) return false;

    [$w, $h] = [imagesx($src), imagesy($src)];

    if ($w > $maxWidth) {
        $newH = intval($h * $maxWidth / $w);
        $dst = imagecreatetruecolor($maxWidth, $newH);
        imagecopyresampled($dst, $src, 0,0,0,0, $maxWidth, $newH, $w, $h);
        imagedestroy($src);
        $src = $dst;
    }

    imagewebp($src, $dest, $qualite);
    imagedestroy($src);

    return filesize($dest) <= $maxOctets ? $dest : false;
}


// Récupération POST
$nom           = trim($_POST['nom'] ?? '');
$idCategorie   = intval($_POST['idCategorie'] ?? 0);
$idNiveau      = intval($_POST['idNiveau'] ?? 0);
$idStatut      = intval($_POST['idStatut'] ?? 0);
$infoPlus      = trim($_POST['infoPlus'] ?? '');
$infoRangement = trim($_POST['infoRangement'] ?? '');
$FK_idUser     = intval($_POST['FK_idUser'] ?? 0);

if (empty($nom) || !$idCategorie || !$idNiveau || !$idStatut || !$FK_idUser) {
    echo json_encode(['success' => false, 'message' => 'Champs obligatoires manquants.']);
    exit;
}


// Gestion image
$photo = null;

if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {

    $dossier = __DIR__ . '/../uploads/';

    if (!is_dir($dossier)) {
        mkdir($dossier, 0755, true);
    }

    $cheminComplet = traiterImage($_FILES['photo'], $dossier);

    if ($cheminComplet !== false) {
        // On renvoie un chemin relatif pour la DB
        $photo = 'uploads/' . basename($cheminComplet);
    }
}


// INSERT SQL
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
    'photo'         => $photo
]);

echo json_encode(['success' => true, 'message' => 'Objet ajouté avec succès !']);
?>