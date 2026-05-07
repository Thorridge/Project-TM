<?php
session_start();
header('Content-Type: application/json');

try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=hardware_db;charset=utf8mb4",
        "root",
        "",
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    $user = $_GET['user'] ?? '';
    $status = $_GET['status'] ?? '';

    $sql = "
    SELECT 
        p.idPret,
        o.idObjet,
        o.nom AS produit,
        CONCAT(u.prenomUtilisateur, ' ', u.nomUtilisateur) AS user,
        p.date_debut AS date,
        CASE 
            WHEN p.date_retour_reelle IS NULL THEN 'en_cours'
            ELSE 'termine'
        END AS statut
    FROM pret p
    JOIN objet o ON p.idObjet = o.idObjet
    JOIN utilisateur u ON p.idEmprunteur = u.idUtilisateur
    WHERE 1=1
    ";

    $params = [];

    if (!empty($user)) {
        $sql .= " AND CONCAT(u.prenomUtilisateur, ' ', u.nomUtilisateur) LIKE :user";
        $params['user'] = "%$user%";
    }

    if (!empty($status)) {
        if ($status === "en_cours") {
            $sql .= " AND p.date_retour_reelle IS NULL";
        }
        if ($status === "termine") {
            $sql .= " AND p.date_retour_reelle IS NOT NULL";
        }
    }

    $sql .= " ORDER BY p.date_debut DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    echo json_encode($stmt->fetchAll());

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}


