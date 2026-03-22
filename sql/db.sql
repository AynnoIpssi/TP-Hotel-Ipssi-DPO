CREATE DATABASE hotel_reservation;
USE hotel_reservation;
CREATE TABLE client (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    date_naissance DATE,
    adresse TEXT,
    code_postal VARCHAR(20),
    ville VARCHAR(255),
    email VARCHAR(255) UNIQUE NOT NULL,
    telephone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE chambre (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero VARCHAR(20) UNIQUE NOT NULL,
    type VARCHAR(50) NOT NULL,
    occuper BOOLEAN NOT NULL
);

CREATE TABLE reservation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    date_arrivee DATE NOT NULL,
    date_depart DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES client(id) ON DELETE CASCADE
);

CREATE TABLE reservation_chambre (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT NOT NULL,
    chambre_id INT NOT NULL,
    client_id INT NOT NULL,
    UNIQUE(reservation_id, chambre_id, client_id),
    FOREIGN KEY (reservation_id) REFERENCES reservation(id) ON DELETE CASCADE,
    FOREIGN KEY (chambre_id) REFERENCES chambre(id) ON DELETE CASCADE,
    FOREIGN KEY (client_id) REFERENCES client(id) ON DELETE CASCADE
);

INSERT INTO client (nom, prenom, date_naissance, adresse, code_postal, ville, email, telephone)
VALUES ('Dupont', 'Jean', '1980-05-15', '12 rue des Lilas', '75001', 'Paris', 'jean.dupont@email.com', '0102030405');

INSERT INTO chambre (numero, type) 
VALUES 
    ('101', 'simple'),
    ('102', 'double'),
    ('103', 'suite');

INSERT INTO reservation (client_id, date_arrivee, date_depart)
VALUES (1, '2023-04-01', '2023-04-05');
    
INSERT INTO reservation_chambre (reservation_id, chambre_id)
VALUES 
    (1, 2),  -- Réservation 1, chambre 2
    (1, 3);  -- Réservation 1, chambre 3

INSERT INTO client (nom, prenom, date_naissance, adresse, code_postal, ville, email, telephone) 
VALUES 
    ('Dupont', 'Jean', '1985-06-15', '12 rue des Lilas', '75001', 'Paris', 'jean.dupont2@email.com', '0102030405');

INSERT INTO chambre (numero, type, occuper) 
VALUES 
    ('101', 'simple', 0);

    -- On supprime la colonne "occuper" qui ne sert plus à rien
ALTER TABLE chambre DROP COLUMN occuper;