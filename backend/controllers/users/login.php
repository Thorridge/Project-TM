<?php
ini_set('session.cookie_path', '/');
session_start();
header('Content-Type: application/json');

// Chemin corrigé
require_once __DIR__ . '/../../config/connect.php';

$login = trim($_POST['login'] ?? '');
$mdp   = trim($_POST['mdp']   ?? '');

if (empty($login) || empty($mdp)) {
    echo json_encode(['success' => false, 'message' => 'Champs obligatoires manquants.']);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE login = :login LIMIT 1");
$stmt->execute(['login' => $login]);
$user = $stmt->fetch();

if (!$user || !password_verify($mdp, $user['mdp'])) {
    echo json_encode(['success' => false, 'message' => 'Login ou mot de passe incorrect.']);
    exit;
}

// Correction : ta colonne s'appelle "role" dans la DB
$_SESSION['user_id']    = $user['idUtilisateur'];
$_SESSION['user_login'] = $user['login'];
$_SESSION['user_role']  = $user['role'];
$_SESSION['user_nom']   = $user['prenomUtilisateur'];

echo json_encode([
    'success' => true,
    'role'    => $user['role'],
    'nom'     => $user['prenomUtilisateur']
]);
