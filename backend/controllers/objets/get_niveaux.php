<?php
// backend/get_niveaux.php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/connect.php';
$sql = "SELECT 
            n.idNiveau,
            CONCAT(s.nom, ' › ', l.nom, ' › ', r.nom, ' › ', n.nom) AS label
        FROM niveau n
        JOIN rangement r ON n.idRangement = r.idRangement
        JOIN local l ON r.idLocal = l.idLocal
        JOIN site s ON l.idSite = s.idSite
        ORDER BY s.nom, l.nom, r.nom, n.nom";

$stmt = $pdo->query($sql);
echo json_encode($stmt->fetchAll());
?>