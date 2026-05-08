<?php
// backend/process.php
session_start();
header('Content-Type: application/json');
require_once 'connect.php';

// Exemple : Récupérer l'ID de l'utilisateur stocké lors du login
$idUtilisateurConnecte = $_SESSION['user_id'] ?? null;

if (!$idUtilisateurConnecte) {
    echo json_encode(['error' => 'Vous devez être connecté']);
    exit;
}
// On récupère l'action à réaliser
$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    case 'get_user_info':
        // On vérifie si l'utilisateur est connecté en session
        if (isset($_SESSION['user_id'])) {
            try {
                // On interroge la base de données pour avoir les infos fraîches
                $stmt = $pdo->prepare("SELECT pseudoUtilisateur FROM utilisateur WHERE idUtilisateur = ?");
                $stmt->execute([$_SESSION['user_id']]);
                $user = $stmt->fetch();

                if ($user) {
                    echo json_encode(['success' => true, 'pseudo' => $user['pseudoUtilisateur']]);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Utilisateur introuvable']);
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Non connecté']);
        }
        break;

    case 'logout':
        // Destruction de la session
        session_unset();
        session_destroy();
        // Redirection vers la page de connexion
        header('Location: ../index.html');
        exit;
        break;

    default:
        echo json_encode(['error' => 'Action non reconnue']);
        break;
}
?>