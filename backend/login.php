<?php
// backend/login.php
session_start();
header('Content-Type: application/json');
require_once 'connect.php';

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

$_SESSION['user_id']    = $user['idUtilisateur'];
$_SESSION['user_login'] = $user['login'];
$_SESSION['user_role']  = $user['role'];
$_SESSION['user_nom']   = $user['nomUtilisateur'];

echo json_encode([
    'success' => true,
    'role'    => $user['role'],
    'nom'     => $user['nomUtilisateur']
]);
?>