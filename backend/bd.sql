-- 1. TABLES DE LA HIÉRARCHIE GÉOGRAPHIQUE
-- ------------------------------------------------------------

CREATE TABLE site (
    idSite INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    adresse VARCHAR(255),
    code_postal VARCHAR(10),
    localite VARCHAR(100),
    photo VARCHAR(255)
) ENGINE=InnoDB;

CREATE TABLE local (
    idLocal INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    infoLocal TEXT,
    idSite INT NOT NULL,
    photo VARCHAR(255)
) ENGINE=InnoDB;

CREATE TABLE rangement (
    idRangement INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    infoRangement TEXT,
    idLocal INT NOT NULL,
    photo VARCHAR(255)
) ENGINE=InnoDB;

CREATE TABLE niveau (
    idNiveau INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    infoNiveau TEXT,
    idRangement INT NOT NULL,
    photo VARCHAR(255)
) ENGINE=InnoDB;

-- 2. TABLES UTILISATEURS ET CATÉGORIES
-- ------------------------------------------------------------

CREATE TABLE categorie (
    idCategorie INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    infoPlus TEXT
) ENGINE=InnoDB;

CREATE TABLE utilisateur (
    idUtilisateur INT AUTO_INCREMENT PRIMARY KEY,
    nomUtilisateur VARCHAR(100) NOT NULL,
    prenomUtilisateur VARCHAR(100) NOT NULL,
    role ENUM('admin', 'owner', 'user') NOT NULL,
    login VARCHAR(50) UNIQUE NOT NULL,
    mdp VARCHAR(255) NOT NULL
) ENGINE=InnoDB;


CREATE TABLE statut_reference (
    idStatut INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL -- ex: 'Disponible', 'Prêté', 'En réparation'
) ENGINE=InnoDB;

-- 3. TABLE PRINCIPALE DES OBJETS
-- ------------------------------------------------------------

CREATE TABLE objet (
    idObjet INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    infoRangement TEXT, 
    photo VARCHAR(255),
    idCategorie INT NOT NULL,
    idNiveau INT NOT NULL,
    infoPlus TEXT, 
    date_acquisition DATE,
    FK_idUser INT NOT NULL,
    idStatut INT NOT NULL
) ENGINE=InnoDB;

-- 4. TABLE ASSOCIATIVE POUR LE PRÊT (Relation N-N) [cite: 27, 28]
-- ------------------------------------------------------------

CREATE TABLE pret (
    idPret INT AUTO_INCREMENT PRIMARY KEY,
    idObjet INT NOT NULL,
    idEmprunteur INT NOT NULL,
    date_debut DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_retour_prevue DATETIME,
    date_retour_reelle DATETIME
) ENGINE=InnoDB;

-- 5. DÉFINITION DES RELATIONS (CLÉS ÉTRANGÈRES) VIA ALTER TABLE
-- ------------------------------------------------------------


ALTER TABLE local 
    ADD CONSTRAINT FK_Local_Site FOREIGN KEY (idSite) REFERENCES site(idSite) ON DELETE CASCADE;

ALTER TABLE rangement 
    ADD CONSTRAINT FK_Rangement_Local FOREIGN KEY (idLocal) REFERENCES local(idLocal) ON DELETE CASCADE;

ALTER TABLE niveau 
    ADD CONSTRAINT FK_Niveau_Rangement FOREIGN KEY (idRangement) REFERENCES rangement(idRangement) ON DELETE CASCADE;


ALTER TABLE objet 
    ADD CONSTRAINT FK_Objet_Niveau FOREIGN KEY (idNiveau) REFERENCES niveau(idNiveau),
    ADD CONSTRAINT FK_Objet_Categorie FOREIGN KEY (idCategorie) REFERENCES categorie(idCategorie),
    ADD CONSTRAINT FK_Objet_User FOREIGN KEY (FK_idUser) REFERENCES utilisateur(idUtilisateur),
    ADD CONSTRAINT FK_Objet_Statut FOREIGN KEY (idStatut) REFERENCES statut_reference(idStatut);


ALTER TABLE pret 
    ADD CONSTRAINT FK_Pret_Objet FOREIGN KEY (idObjet) REFERENCES objet(idObjet),
    ADD CONSTRAINT FK_Pret_Emprunteur FOREIGN KEY (idEmprunteur) REFERENCES utilisateur(idUtilisateur);