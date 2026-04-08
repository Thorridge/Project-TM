<?php
// backend/get_tree.php
session_start();
header('Content-Type: application/json');
require_once 'connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Non connecté']);
    exit;
}

// Récupérer toute l'arborescence Site > Local > Rangement
$sql = "SELECT 
            s.idSite, s.nom AS site_nom,
            l.idLocal, l.nom AS local_nom,
            r.idRangement, r.nom AS rangement_nom
        FROM site s
        LEFT JOIN local l ON s.idSite = l.idSite
        LEFT JOIN rangement r ON l.idLocal = r.idLocal
        ORDER BY s.nom, l.nom, r.nom";

$stmt = $pdo->query($sql);
$rows = $stmt->fetchAll();

// Organiser en structure arborescente
$tree = [];
foreach ($rows as $row) {
    $siteId = $row['idSite'];
    $localId = $row['idLocal'];

    if (!isset($tree[$siteId])) {
        $tree[$siteId] = [
            'idSite' => $siteId,
            'nom' => $row['site_nom'],
            'locaux' => []
        ];
    }

    if ($localId && !isset($tree[$siteId]['locaux'][$localId])) {
        $tree[$siteId]['locaux'][$localId] = [
            'idLocal' => $localId,
            'nom' => $row['local_nom'],
            'rangements' => []
        ];
    }

    if ($row['idRangement'] && $localId) {
        $tree[$siteId]['locaux'][$localId]['rangements'][] = [
            'idRangement' => $row['idRangement'],
            'nom' => $row['rangement_nom']
        ];
    }
}

// Réindexer les tableaux
$result = [];
foreach ($tree as $site) {
    $site['locaux'] = array_values($site['locaux']);
    $result[] = $site;
}

echo json_encode($result);
?>