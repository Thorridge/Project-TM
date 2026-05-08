<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/connect.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'owner') {
    echo json_encode(['success' => false, 'message' => 'Accès refusé.']);
    exit;
}

$sql = "SELECT idUtilisateur, nomUtilisateur, prenomUtilisateur, login, role 
        FROM utilisateur 
        ORDER BY FIELD(role, 'owner','admin','user'), nomUtilisateur ASC";

$users = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['success' => true, 'users' => $users]);


