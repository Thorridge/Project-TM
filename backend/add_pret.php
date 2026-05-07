<?php
header('Content-Type: application/json');

try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=hardware_db;charset=utf8mb4",
        "root",
        "",
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );

    $data = json_decode(file_get_contents("php://input"), true);

    $produit = $data['produit_id'] ?? null;
    $user = $data['user_id'] ?? null;
    $date_debut = $data['date_debut'] ?? null;
    $date_fin = $data['date_fin'] ?? null;

    if (!$produit || !$user || !$date_debut) {
        echo json_encode([
            "success" => false,
            "message" => "Champs manquants"
        ]);
        exit;
    }

    // Vérifier si déjà en prêt
    $check = $pdo->prepare("
        SELECT idPret FROM pret
        WHERE idObjet = ?
        AND date_retour_reelle IS NULL
    ");
    $check->execute([$produit]);

    if ($check->fetch()) {
        echo json_encode([
            "success" => false,
            "message" => "Objet déjà en prêt"
        ]);
        exit;
    }

    // Insertion
    $stmt = $pdo->prepare("
        INSERT INTO pret (idObjet, idEmprunteur, date_debut, date_retour_prevue)
        VALUES (?, ?, ?, ?)
    ");

    $stmt->execute([
        $produit,
        $user,
        $date_debut,
        $date_fin ?: null
    ]);

    // Mettre statut = 2 (En prêt)
    $pdo->prepare("
        UPDATE objet
        SET idStatut = 3
        WHERE idObjet = ?
    ")->execute([$produit]);

    echo json_encode(["success" => true]);

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
