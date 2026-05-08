<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/connect.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'owner') {
    echo json_encode(['success' => false, 'message' => 'Accès refusé.']);
    exit;
}

$id = intval($_POST['idUtilisateur'] ?? 0);
$newRole = $_POST['role'] ?? '';

$allowed = ['user', 'admin'];

if ($id === 0 || !in_array($newRole, $allowed)) {
    echo json_encode(['success' => false, 'message' => 'Données invalides.']);
    exit;
}

// Récupérer le rôle actuel
$stmt = $pdo->prepare("SELECT role FROM utilisateur WHERE idUtilisateur = :id");
$stmt->execute(['id' => $id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo json_encode(['success' => false, 'message' => 'Utilisateur introuvable.']);
    exit;
}

// On ne modifie jamais un owner
if ($user['role'] === 'owner') {
    echo json_encode(['success' => false, 'message' => 'Impossible de modifier un owner.']);
    exit;
}

// Empêcher de retirer le dernier admin
if ($user['role'] === 'admin' && $newRole === 'user') {
    $count = $pdo->query("SELECT COUNT(*) FROM utilisateur WHERE role = 'admin'")->fetchColumn();
    if ($count <= 1) {
        echo json_encode(['success' => false, 'message' => 'Impossible de retirer le dernier admin.']);
        exit;
    }
}

$stmt = $pdo->prepare("UPDATE utilisateur SET role = :role WHERE idUtilisateur = :id");
$stmt->execute(['role' => $newRole, 'id' => $id]);

echo json_encode(['success' => true, 'message' => 'Rôle mis à jour.']);

