<?php
// backend/login.php
session_start();
header('Content-Type: application/json');
require_once 'connect.php';

// Récupérer les données envoyées en POST
$login = trim($_POST['login'] ?? '');
$mdp   = trim($_POST['mdp']   ?? '');

// Validation basique
if (empty($login) || empty($mdp)) {
    echo json_encode(['success' => false, 'message' => 'Champs obligatoires manquants.']);
    exit;
}

// Chercher l'utilisateur par son login
$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE login = :login LIMIT 1");
$stmt->execute(['login' => $login]);
$user = $stmt->fetch();

// Vérifier le mot de passe
if (!$user || !password_verify($mdp, $user['mdp'])) {
    echo json_encode(['success' => false, 'message' => 'Login ou mot de passe incorrect.']);
    exit;
}

// Stocker les infos en session
$_SESSION['user_id']    = $user['idUtilisateur'];
$_SESSION['user_login'] = $user['login'];
$_SESSION['user_pseudo'] = $user['pseudoUtilisateur'];
$_SESSION['user_role']  = $user['role'];

echo json_encode([
    'success' => true,
    'role'    => $user['role'],
    'pseudo'  => $user['pseudoUtilisateur']
]);
?>