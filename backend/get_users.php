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
            idUtilisateur,
            CONCAT(prenomUtilisateur, ' ', nomUtilisateur) AS nomComplet
        FROM utilisateur
        ORDER BY nomComplet ASC
    ";

    $stmt = $pdo->query($sql);
    echo json_encode($stmt->fetchAll());

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}

