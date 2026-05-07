<?php
// backend/get_prets.php
session_start();
header('Content-Type: application/json');
require_once 'connect.php';

$sql = "SELECT 
            p.idPret,
            p.date_debut,
            p.date_retour_prevue,
            p.date_retour_reelle,
            o.nom AS objet_nom,
            o.infoPlus AS objet_info,
            c.nom AS categorie,
            u.nomUtilisateur,
            u.prenomUtilisateur,
            u.login,
            s.nom AS site_nom,
            l.nom AS local_nom,
            r.nom AS rangement_nom,
            n.nom AS niveau_nom
        FROM pret p
        JOIN objet o ON p.idObjet = o.idObjet
        JOIN utilisateur u ON p.idEmprunteur = u.idUtilisateur
        JOIN categorie c ON o.idCategorie = c.idCategorie
        JOIN niveau n ON o.idNiveau = n.idNiveau
        JOIN rangement r ON n.idRangement = r.idRangement
        JOIN local l ON r.idLocal = l.idLocal
        JOIN site s ON l.idSite = s.idSite
        ORDER BY p.date_retour_prevue ASC";

$stmt = $pdo->query($sql);
echo json_encode($stmt->fetchAll());
?>
