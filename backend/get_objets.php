<?php
// backend/get_objets.php
session_start();
header('Content-Type: application/json');
require_once 'connect.php';

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Non connecté']);
    exit;
}

$nom = "%" . ($_GET['nom'] ?? '') . "%";
$cat = $_GET['cat'] ?? '';

// Requête avec toutes les jointures pour avoir les infos complètes
$sql = "SELECT 
            o.idObjet,
            o.nom,
            o.infoPlus,
            o.photo,
            c.nom AS categorie,
            sr.libelle AS statut,
            n.nom AS niveau_nom,
            r.nom AS rangement_nom,
            l.nom AS local_nom,
            s.nom AS site_nom
        FROM objet o
        JOIN categorie c ON o.idCategorie = c.idCategorie
        JOIN statut_reference sr ON o.idStatut = sr.idStatut
        JOIN niveau n ON o.idNiveau = n.idNiveau
        JOIN rangement r ON n.idRangement = r.idRangement
        JOIN local l ON r.idLocal = l.idLocal
        JOIN site s ON l.idSite = s.idSite
        WHERE o.nom LIKE :nom";

$params = ['nom' => $nom];

if (!empty($cat)) {
    $sql .= " AND o.idCategorie = :cat";
    $params['cat'] = $cat;
}

$sql .= " ORDER BY o.nom";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
echo json_encode($stmt->fetchAll());
?>