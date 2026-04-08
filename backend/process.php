<?php
// process.php
session_start(); // Indispensable pour récupérer les infos de l'utilisateur connecté
header('Content-Type: application/json');
require_once 'connect.php'; 

// Exemple : Récupérer l'ID de l'utilisateur stocké lors du login
$idUtilisateurConnecte = $_SESSION['user_id'] ?? null;

if (!$idUtilisateurConnecte) {
    echo json_encode(['error' => 'Vous devez être connecté']);
    exit;
}
// $action n'est jamais récupérée depuis la requête, ça va planter
switch ($action) {

    // 1. Charger les catégories pour le menu déroulant
    case 'load_categories':
        $stmt = $pdo->query("SELECT idCategorie, nom FROM categorie ORDER BY nom");
        echo json_encode($stmt->fetchAll());
        break;

    // 2. Recherche multifactorielle d'objets (par nom et catégorie) [cite: 32]
    case 'search':
        $nom = "%" . ($_GET['nom'] ?? '') . "%";
        $cat = $_GET['cat'] ?? '';

        // Construction de la requête de base
        $sql = "SELECT o.*, s.libelle as statut_nom 
                FROM objet o 
                JOIN statut_reference s ON o.idStatut = s.idStatut 
                WHERE o.nom LIKE :nom";
        
        $params = ['nom' => $nom];

        // On ajoute le filtre catégorie si sélectionné
        if (!empty($cat)) {
            $sql .= " AND o.idCategorie = :cat";
            $params['cat'] = $cat;
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        echo json_encode($stmt->fetchAll());
        break;

    // 3. Charger l'arborescence (Site > Local) [cite: 33]
    case 'tree':
        $sql = "SELECT s.nom as site_nom, l.nom as local_nom, l.idLocal 
                FROM site s 
                LEFT JOIN local l ON s.idSite = l.idSite 
                ORDER BY s.nom, l.nom";
        $stmt = $pdo->query($sql);
        echo json_encode($stmt->fetchAll());
        break;

    // 4. Statistiques rapides pour le dashboard [cite: 17]
    case 'stats':
        $sql = "SELECT sr.libelle, COUNT(o.idObjet) as total 
                FROM statut_reference sr 
                LEFT JOIN objet o ON sr.idStatut = o.idStatut 
                GROUP BY sr.idStatut";
        $stmt = $pdo->query($sql);
        echo json_encode($stmt->fetchAll());
        break;

    default:
        echo json_encode(['error' => 'Action non reconnue']);
        break;
}
?>