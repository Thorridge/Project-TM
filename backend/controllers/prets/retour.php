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

    $data = json_decode(file_get_contents("php://input"), true);

    $idPret = $data['idPret'] ?? null;
    $idObjet = $data['idObjet'] ?? null;

    if (!$idPret || !$idObjet) {
        echo json_encode([
            "success" => false,
            "message" => "ID prêt manquant"
        ]);
        exit;
    }

    // Marquer le prêt comme terminé
    $stmt = $pdo->prepare("
        UPDATE pret
        SET date_retour_reelle = NOW()
        WHERE idPret = ?
    ");
    $stmt->execute([$idPret]);

    // Remettre l’objet en disponible
    $stmt = $pdo->prepare("
        UPDATE objet
        SET idStatut = 1
        WHERE idObjet = ?
    ");
    $stmt->execute([$idObjet]);

    echo json_encode(["success" => true]);

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
