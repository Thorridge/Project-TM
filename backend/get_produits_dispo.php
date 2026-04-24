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
    SELECT o.idObjet, o.nom
    FROM objet o

    LEFT JOIN pret p 
        ON o.idObjet = p.idObjet 
        AND p.date_retour_reelle IS NULL

    WHERE p.idPret IS NULL
    ";

    $stmt = $pdo->query($sql);

    echo json_encode($stmt->fetchAll());

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}