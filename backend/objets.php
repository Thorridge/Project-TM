<?php
header('Content-Type: application/json');

$pdo = new PDO("mysql:host=localhost;dbname=hardware_db;charset=utf8", "root", "");

// filtre optionnel
$search = $_GET['search'] ?? '';

$sql = "
SELECT 
    o.idObjet,
    o.nom,
    o.infoPlus,

    c.nom AS categorie,
    s.libelle AS statut,

    u.pseudoUtilisateur AS emprunteur,

    CONCAT(site.nom, ' > ', l.nom, ' > ', r.nom, ' > ', n.nom) AS emplacement

FROM objet o

JOIN categorie c ON o.idCategorie = c.idCategorie
JOIN statut_reference s ON o.idStatut = s.idStatut

JOIN niveau n ON o.idNiveau = n.idNiveau
JOIN rangement r ON n.idRangement = r.idRangement
JOIN local l ON r.idLocal = l.idLocal
JOIN site ON l.idSite = site.idSite

LEFT JOIN pret p ON o.idObjet = p.idObjet AND p.date_retour_reelle IS NULL
LEFT JOIN utilisateur u ON p.idEmprunteur = u.idUtilisateur

WHERE o.nom LIKE :search
";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    "search" => "%$search%"
]);

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));