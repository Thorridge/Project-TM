<?php
// connect.php

// Paramètres de connexion (à modifier selon votre hébergeur)
$host = 'localhost';
$dbname = 'bd.sql';
$user = $config['username'];                    //Le nom d'utilisateur
$password = $config['password'];                //Le mot de passe de l'utilisateur
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

// Options PDO pour la sécurité et le débogage
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Active les erreurs
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Retourne des tableaux associatifs
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Désactive l'émulation pour plus de sécurité
];

try {
    $pdo = new PDO($dsn, $user, $password, $options);
} catch (\PDOException $e) {
    // En cas d'erreur, on renvoie un message JSON pour que l'AJAX puisse le lire
    header('Content-Type: application/json');
    echo json_encode(['error' => "Erreur de connexion : " . $e->getMessage()]);
    exit;
}
?>