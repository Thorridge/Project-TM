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

    $user = $_GET['user'] ?? '';
    $status = $_GET['status'] ?? '';

    // BASE SQLS
// Dans get_pret.php
    $sql = "
    SELECT 
        p.idPret AS idPret,
        o.idObjet AS idObjet,
        o.nom AS produit,
        u.pseudoUtilisateur AS user,
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

    // 🔎 filtre user
    if (!empty($user)) {
        $sql .= " AND u.pseudoUtilisateur LIKE :user";
        $params['user'] = "%$user%";
    }

    // 🔎 filtre statut
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

    echo json_encode([
        "error" => "Erreur serveur",
        "details" => $e->getMessage()
    ]);
}