<?php
// backend/register.php
session_start();
header('Content-Type: application/json');
require_once 'connect.php';

$nom     = trim($_POST['nom']     ?? '');
$prenom  = trim($_POST['prenom']  ?? '');
$pseudo  = trim($_POST['pseudo']  ?? '');
$login   = trim($_POST['login']   ?? '');
$mdp     = trim($_POST['mdp']     ?? '');
$mdpConf = trim($_POST['mdpConf'] ?? '');

if (empty($nom) || empty($prenom) || empty($login) || empty($mdp)) {
    echo json_encode(['success' => false, 'message' => 'Tous les champs obligatoires doivent être remplis.']);
    exit;
}

if ($mdp !== $mdpConf) {
    echo json_encode(['success' => false, 'message' => 'Les mots de passe ne correspondent pas.']);
    exit;
}

if (strlen($mdp) < 6) {
    echo json_encode(['success' => false, 'message' => 'Le mot de passe doit faire au moins 6 caractères.']);
    exit;
}

$stmt = $pdo->prepare("SELECT idUtilisateur FROM utilisateur WHERE login = :login LIMIT 1");
$stmt->execute(['login' => $login]);
if ($stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'Ce login est déjà utilisé.']);
    exit;
}

$mdpHash = password_hash($mdp, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("
    INSERT INTO utilisateur (nomUtilisateur, prenomUtilisateur, pseudoUtilisateur, role, login, mdp)
    VALUES (:nom, :prenom, :pseudo, 'user', :login, :mdp)
");

$stmt->execute([
    'nom'    => $nom,
    'prenom' => $prenom,
    'pseudo' => $pseudo, // Ajout ici
    'login'  => $login,
    'mdp'    => $mdpHash
]);

echo json_encode(['success' => true, 'message' => 'Compte créé avec succès !']);
?>