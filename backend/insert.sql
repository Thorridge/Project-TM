-- Statuts
INSERT INTO statut_reference (libelle) VALUES 
('Disponible'), ('En maintenance'), ('Prêté à un membre'), ('Hors service');

-- Catégories
INSERT INTO categorie (nom, infoPlus) VALUES 
('Réseau', 'Switchs, routeurs et câblage'),
('Micro-ordinateurs', 'Raspberry Pi, Arduino, NUC'),
('Composants', 'GPU, RAM, Disques durs'),
('Portables', 'Laptops et tablettes de prêt');

-- Sites
INSERT INTO site (nom, adresse, localite) VALUES 
("Siège de l'asso", "10 Rue de l'Innovation", 'Mons'),
('Domicile Président', '5 Avenue des Tests', 'Charleroi'),
('Espace de stockage', 'Z.I du Parc', 'Namur');

-- Locaux
INSERT INTO local (nom, infoLocal, idSite) VALUES 
('Salle serveur', 'Accès sécurisé badge A', 1),
('Atelier de réparation', 'Étage 1, porte droite', 1),
('Bureau', 'Espace administratif', 2);

-- Rangements
INSERT INTO rangement (nom, infoRangement, idLocal) VALUES 
('Baie de brassage', 'Rack 19 pouces principal', 1),
('Armoire blindée', 'Stockage matériel sensible', 2),
('Rack de stockage', 'Rayonnage métallique lourd', 3);

-- Niveaux
INSERT INTO niveau (nom, infoNiveau, idRangement) VALUES 
('Unité 1U', 'Emplacement rack n°1', 1),
('Unité 2U', 'Emplacement rack n°2', 1),
('Étagère n°3', 'Niveau intermédiaire', 2);

-- Utilisateurs avec mots de passe hashés
INSERT INTO utilisateur (nomUtilisateur, prenomUtilisateur, role, login, mdp) VALUES 
('Groot', 'Admin', 'admin', 'admin@asso.be', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('User', 'Owner', 'owner', 'owner@asso.be', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Simple', 'Membre', 'user', 'user@asso.be', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Objets
INSERT INTO objet (nom, infoRangement, idCategorie, idNiveau, infoPlus, FK_idUser, idStatut) VALUES 
('Switch 24 ports Cisco', 'Face avant', 1, 1, 'Modèle Catalyst 2960-X', 1, 1),
('Raspberry Pi 4', 'Boîtier rouge', 2, 3, '8GB RAM, MicroSD 64GB', 2, 1),
('RTX 3080', "Boîte d'origine", 3, 3, 'GPU de test pour benchmarks', 2, 2),
('Laptop Dell Latitude', 'Sacoche n°5', 4, 3, 'i7, 16GB RAM, SSD 512GB', 2, 3);

-- Prêt
INSERT INTO pret (idObjet, idEmprunteur, date_debut, date_retour_prevue) VALUES 
(4, 3, '2026-04-01 10:00:00', '2026-04-15 17:00:00');