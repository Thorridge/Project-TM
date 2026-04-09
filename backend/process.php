<?php
// process.php
session_start();
header('Content-Type: application/json');
require_once 'connect.php';

// Exemple : Récupérer l'ID de l'utilisateur stocké lors du login
$idUtilisateurConnecte = $_SESSION['user_id'] ?? null;

if (!$idUtilisateurConnecte) {
    echo json_encode(['error' => 'Vous devez être connecté']);
    exit;
}
// $action n'est jamais récupérée depuis la requête, ça va planter
switch ($action) {

    case 'register':
        $login = $_POST['login'] ?? '';
        $pseudo = $_POST['pseudo'] ?? '';
        $mdp = $_POST['mdp'] ?? '';

        if (empty($login) || empty($mdp)) {
            echo json_encode(['success' => false, 'error' => 'Veuillez remplir les champs obligatoires.']);
            exit;
        }

        // Sécurité : hachage du mot de passe
        $hash = password_hash($mdp, PASSWORD_DEFAULT);

        try {
            $sql = "INSERT INTO utilisateur (login, pseudoUtilisateur, mdp) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$login, $pseudo, $hash]);
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => 'Ce login est déjà utilisé.']);
        }
        break;

    case 'login':
        $login = $_POST['login'] ?? '';
        $mdp = $_POST['mdp'] ?? '';

        $sql = "SELECT * FROM utilisateur WHERE login = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$login]);
        $user = $stmt->fetch();

        if ($user && password_verify($mdp, $user['mdp'])) {
            // On stocke les propriétés en session
            $_SESSION['user_id'] = $user['idUtilisateur'];
            $_SESSION['pseudo'] = $user['pseudoUtilisateur'];
            $_SESSION['role'] = $user['role'];

            echo json_encode([
                'success' => true,
                'user' => [
                    'pseudo' => $user['pseudoUtilisateur'],
                    'role' => $user['role']
                ]
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Identifiants incorrects.']);
        }
        break;

    case 'logout':
        session_destroy();
        echo json_encode(['success' => true]);
        break;

    default:
        echo json_encode(['error' => 'Action invalide']);
        break;
}
?>