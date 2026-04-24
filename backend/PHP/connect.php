<?php
$host     = 'localhost';
$dbname   = 'hardware_db'; // CORRIGÉ : correspond au nom dans bd.sql
$user     = 'root';
$password = '';
$charset  = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // CORRIGÉ : $password au lieu de $pass
    $pdo = new PDO($dsn, $user, $password, $options); 
} catch (\PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => "Erreur de connexion : " . $e->getMessage()]);
    exit;
}
?>