<?php
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

    $sql = "
        SELECT 
            o.idObjet,
            o.nom,
            c.nom AS categorie
        FROM objet o
        JOIN categorie c ON o.idCategorie = c.idCategorie
        LEFT JOIN pret p 
            ON o.idObjet = p.idObjet
            AND p.date_retour_reelle IS NULL
        WHERE o.idStatut = 1
        AND o.idStatut = 1
        ORDER BY o.nom ASC
        ";


    $stmt = $pdo->query($sql);
    echo json_encode($stmt->fetchAll());

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}

