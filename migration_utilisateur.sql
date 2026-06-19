-- Ajouter la table Utilisateur
CREATE TABLE IF NOT EXISTS Utilisateur (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    login VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'visiteur',
    dateCreation DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Insérer un administrateur par défaut
INSERT INTO Utilisateur (login, password, role) VALUES 
    ('admin', 'admin123', 'administrateur'),
    ('editeur1', 'edit123', 'editeur'),
    ('visiteur1', 'visit123', 'visiteur');

-- Ajouter la colonne utilisateur_id dans Article (pour savoir qui a créé l'article)
ALTER TABLE Article ADD COLUMN utilisateur_id INTEGER;
ALTER TABLE Article ADD FOREIGN KEY (utilisateur_id) REFERENCES Utilisateur(id);