<?php
// backend/get_stats.php
session_start();
header('Content-Type: application/json');
require_once 'connect.php';

// Total objets
$total = $pdo->query("SELECT COUNT(*) as total FROM objet")->fetch()['total'];

// Par statut
$sql = "SELECT sr.libelle, COUNT(o.idObjet) as total 
        FROM statut_reference sr
        LEFT JOIN objet o ON sr.idStatut = o.idStatut
        GROUP BY sr.idStatut, sr.libelle";
$stmt = $pdo->query($sql);
$statuts = $stmt->fetchAll();

$pret = 0;
$maintenance = 0;
$disponible = 0;

foreach ($statuts as $s) {
    if (stripos($s['libelle'], 'prêt') !== false) $pret += $s['total'];
    if (stripos($s['libelle'], 'maintenance') !== false) $maintenance += $s['total'];
    if (stripos($s['libelle'], 'disponible') !== false) $disponible += $s['total'];
}

echo json_encode([
    'total'       => $total,
    'pret'        => $pret,
    'maintenance' => $maintenance,
    'disponible'  => $disponible
]);
?>