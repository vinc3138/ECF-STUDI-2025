-- PRIVILEGE (IDs fixes)
INSERT INTO `privilege` (`privilege_id`, `privilege`) VALUES
  (1, 'ADMIN'),
  (2, 'EMPLOYE'),
  (3, 'USER'),
  (4, 'SUSPENDU');

-- ROLE (IDs fixes)
INSERT INTO `role` (`role_id`, `libelle`) VALUES
  (1, 'CHAUFFEUR'),
  (2, 'PASSAGER'),
  (3, 'CHAUFFEUR PASSAGER');

-- STATUT_COMMENTAIRE (IDs fixes)
INSERT INTO `statut_commentaire` (`id_statut_commentaire`, `statut_commentaire`) VALUES
  (1, 'NON_RENSEIGNE'),
  (2, 'A VALIDER'),
  (3, 'STANDBY'),
  (4, 'REFUSE'),
  (5, 'VALIDE');

-- STATUT_COVOITURAGE (IDs fixes)
INSERT INTO `statut_covoiturage` (`statut_covoiturage_id`, `statut_covoiturage`) VALUES
  (1, 'A VENIR'),
  (2, 'EN COURS'),
  (3, 'TERMINE'),
  (4, 'ANNULE');

-- STATUT_RESERVATION (IDs fixes)
INSERT INTO `statut_reservation` (`statut_reservation_id`, `statut_reservation`) VALUES
  (1, 'EN ATTENTE'),
  (2, 'CONFIRMEE'),
  (3, 'ANNULEE'),
  (4, 'REFUSEE');

-- MARQUE (IDs à partir de 1000)
INSERT INTO `marque` (`marque_id`, `marque`) VALUES
  (1000, 'Toyota'),
  (1001, 'Renault'),
  (1002, 'Peugeot'),
  (1003, 'Ford'),
  (1004, 'BMW'),
  (1005, 'Audi'),
  (1006, 'Tesla'),
  (1007, 'Volkswagen'),
  (1008, 'Hyundai'),
  (1009, 'Nissan');

-- PREFERENCE (IDs à partir de 1000)
INSERT INTO `preference` (`preference_id`, `preference`, `statut_preference`) VALUES
  (1000, 'MUSIQUE', 'FIXE'),
  (1001, 'CONVERSATION', 'FIXE'),
  (1002, 'ANIMAL', 'FIXE'),
  (1003, 'FUMEUR', 'FIXE'),
  (1004, 'CLIMATISATION', 'OPTIONNEL'),
  (1005, 'BAGAGES', 'OPTIONNEL'),
  (1006, 'ANCIENNETE', 'OPTIONNEL'),
  (1007, 'SILENCE', 'OPTIONNEL'),
  (1008, 'VOITURE_PROPRE', 'OPTIONNEL'),
  (1009, 'CHAUFFEUR_EXPERIMENTE', 'OPTIONNEL');

-- UTILISATEUR (IDs à partir de 1000, tous password = 'ADMIN')
INSERT INTO `utilisateur` (`utilisateur_id`, `role_id`, `privilege_id`, `photo`, `date_inscription`, `nom`, `prenom`, `date_naissance`, `pseudo`, `email`, `password`, `telephone`, `adresse`, `credit`, `note`, `date_creation_utilisateur`) VALUES
  (1000, 1, 1, 'photo1.jpg', '2024-01-01', 'Dupont', 'Jean', '1980-05-12', 'jdupont', 'jdupont@example.com', 'ADMIN', '0600000001', '1 rue A, Paris', 100, 4, '2024-01-01'),
  (1001, 2, 3, 'photo2.jpg', '2024-02-01', 'Martin', 'Claire', '1990-07-23', 'cmartin', 'cmartin@example.com', 'ADMIN', '0600000002', '2 rue B, Lyon', 150, 5, '2024-02-01'),
  (1002, 3, 2, 'photo3.jpg', '2024-03-01', 'Bernard', 'Luc', '1985-03-15', 'lbernard', 'lbernard@example.com', 'ADMIN', '0600000003', '3 rue C, Marseille', 50, 3, '2024-03-01'),
  (1003, 1, 1, 'photo4.jpg', '2024-01-10', 'Lemoine', 'Sophie', '1995-11-11', 'slemoine', 'slemoine@example.com', 'ADMIN', '0600000004', '4 rue D, Lille', 200, 4, '2024-01-10'),
  (1004, 2, 3, 'photo5.jpg', '2024-02-15', 'Moreau', 'Paul', '1978-12-22', 'pmoreau', 'pmoreau@example.com', 'ADMIN', '0600000005', '5 rue E, Toulouse', 75, 2, '2024-02-15'),
  (1005, 3, 2, 'photo6.jpg', '2024-03-05', 'Garcia', 'Isabelle', '1988-09-09', 'igarcia', 'igarcia@example.com', 'ADMIN', '0600000006', '6 rue F, Nantes', 60, 5, '2024-03-05'),
  (1006, 1, 1, 'photo7.jpg', '2024-01-20', 'Petit', 'Marc', '1992-08-30', 'mpetit', 'mpetit@example.com', 'ADMIN', '0600000007', '7 rue G, Strasbourg', 120, 3, '2024-01-20'),
  (1007, 2, 3, 'photo8.jpg', '2024-02-25', 'Roux', 'Julie', '1983-04-14', 'jroux', 'jroux@example.com', 'ADMIN', '0600000008', '8 rue H, Bordeaux', 90, 4, '2024-02-25'),
  (1008, 3, 2, 'photo9.jpg', '2024-03-15', 'Faure', 'Nicolas', '1975-07-07', 'nfaure', 'nfaure@example.com', 'ADMIN', '0600000009', '9 rue I, Montpellier', 130, 5, '2024-03-15'),
  (1009, 1, 1, 'photo10.jpg', '2024-04-01', 'Marchand', 'Emma', '1998-06-20', 'emarchand', 'emarchand@example.com', 'ADMIN', '0600000010', '10 rue J, Rennes', 80, 3, '2024-04-01');

-- VOITURE (IDs à partir de 1000)
INSERT INTO `voiture` (`voiture_id`, `marque_id`, `modele`, `immatriculation`, `energie`, `couleur`, `date_immatriculation`, `nb_places_voiture`, `utilisateur_id`) VALUES
  (1000, 1000, 'Corolla', 'AB-123-CD', 'Essence', 'Rouge', '2015-06-01', 5, 1000),
  (1001, 1001, 'Clio', 'EF-456-GH', 'Diesel', 'Bleu', '2017-08-15', 5, 1001),
  (1002, 1002, '208', 'IJ-789-KL', 'Essence', 'Blanc', '2019-11-20', 5, 1002),
  (1003, 1003, 'Focus', 'MN-234-OP', 'Diesel', 'Noir', '2016-04-10', 5, 1003),
  (1004, 1004, '320i', 'QR-567-ST', 'Essence', 'Gris', '2018-07-25', 5, 1004),
  (1005, 1005, 'A3', 'UV-890-WX', 'Diesel', 'Blanc', '2020-01-05', 5, 1005),
  (1006, 1006, 'Model 3', 'YZ-123-AB', 'Electrique', 'Rouge', '2021-09-30', 5, 1006),
  (1007, 1007, 'Golf', 'CD-456-EF', 'Essence', 'Bleu', '2014-03-15', 5, 1007),
  (1008, 1008, 'i30', 'GH-789-IJ', 'Diesel', 'Vert', '2013-12-01', 5, 1008),
  (1009, 1009, 'Qashqai', 'KL-012-MN', 'Essence', 'Noir', '2017-05-18', 5, 1009);

-- COVOITURAGE (IDs à partir de 1000)
INSERT INTO `covoiturage` (`covoiturage_id`, `date_creation`, `date_depart`, `heure_depart`, `date_debut`, `lieu_depart`, `adresse_depart`, `date_arrivee`, `adresse_arrivee`, `heure_arrivee`, `date_fin`, `lieu_arrivee`, `duree_trajet`, `nb_place`, `nb_place_reservee`, `prix_personne`, `utilisateur_id`, `voiture_id`, `statut_covoiturage`) VALUES
  (1000, '2024-04-10', '2024-04-15', '08:00:00', '2024-04-15', 'Paris', '1 rue A, Paris', '2024-04-15', '10 rue Z, Lyon', '12:00:00', '2024-04-15', 'Lyon', '4h', 4, 2, 20.00, 1000, 1000, 1),
  (1001, '2024-04-11', '2024-04-20', '09:00:00', '2024-04-20', 'Lyon', '2 rue B, Lyon', '2024-04-20', '15 rue Y, Marseille', '13:00:00', '2024-04-20', 'Marseille', '4h', 3, 1, 15.00, 1001, 1001, 1),
(1002, '2024-04-12', '2024-04-25', '07:30:00', '2024-04-25', 'Marseille', '3 rue C, Marseille', '2024-04-25', '20 rue X, Nice', '11:30:00', '2024-04-25', 'Nice', '4h', 4, 3, 25.00, 1002, 1002, 2),
(1003, '2024-04-13', '2024-04-18', '10:00:00', '2024-04-18', 'Nice', '4 rue D, Nice', '2024-04-18', '25 rue W, Toulouse', '14:00:00', '2024-04-18', 'Toulouse', '4h', 5, 4, 18.00, 1003, 1003, 1),
(1004, '2024-04-14', '2024-04-22', '06:00:00', '2024-04-22', 'Toulouse', '5 rue E, Toulouse', '2024-04-22', '30 rue V, Bordeaux', '10:00:00', '2024-04-22', 'Bordeaux', '4h', 4, 1, 22.00, 1004, 1004, 3),
(1005, '2024-04-15', '2024-04-27', '11:00:00', '2024-04-27', 'Bordeaux', '6 rue F, Bordeaux', '2024-04-27', '35 rue U, Nantes', '15:00:00', '2024-04-27', 'Nantes', '4h', 3, 2, 20.00, 1005, 1005, 2),
(1006, '2024-04-16', '2024-04-29', '12:30:00', '2024-04-29', 'Nantes', '7 rue G, Nantes', '2024-04-29', '40 rue T, Strasbourg', '16:30:00', '2024-04-29', 'Strasbourg', '4h', 5, 4, 19.00, 1006, 1006, 1),
(1007, '2024-04-17', '2024-05-01', '14:00:00', '2024-05-01', 'Strasbourg', '8 rue H, Strasbourg', '2024-05-01', '45 rue S, Lille', '18:00:00', '2024-05-01', 'Lille', '4h', 4, 3, 21.00, 1007, 1007, 4),
(1008, '2024-04-18', '2024-05-03', '15:00:00', '2024-05-03', 'Lille', '9 rue I, Lille', '2024-05-03', '50 rue R, Paris', '19:00:00', '2024-05-03', 'Paris', '4h', 5, 5, 23.00, 1008, 1008, 1),
(1009, '2024-04-19', '2024-05-05', '16:00:00', '2024-05-05', 'Paris', '10 rue J, Paris', '2024-05-05', '55 rue Q, Lyon', '20:00:00', '2024-05-05', 'Lyon', '4h', 4, 2, 17.00, 1009, 1009, 1);

-- RESERVATION (IDs à partir de 1000)
INSERT INTO reservation (reservation_id, covoiturage_id, utilisateur_id, date_reservation, nb_places_reservees, statut_reservation) VALUES
(1000, 1000, 1001, '2024-04-02', 1, 1),
(1001, 1000, 1002, '2024-04-03', 1, 2),
(1002, 1001, 1003, '2024-04-04', 2, 1),
(1003, 1002, 1004, '2024-04-05', 1, 3),
(1004, 1003, 1005, '2024-04-06', 1, 2),
(1005, 1004, 1006, '2024-04-07', 3, 1),
(1006, 1005, 1007, '2024-04-08', 1, 1),
(1007, 1006, 1008, '2024-04-09', 2, 2),
(1008, 1007, 1009, '2024-04-10', 1, 1),
(1009, 1008, 1000, '2024-04-11', 1, 4);

-- COMMENTAIRE (IDs à partir de 1000)
INSERT INTO commentaire (commentaire_id, utilisateur_id, covoiturage_id, note, commentaire, date_commentaire, id_statut_commentaire) VALUES
(1000, 1001, 1000, 5, 'Très bon trajet, chauffeur sympa.', '2024-04-16', 5),
(1001, 1002, 1000, 4, 'Trajet correct.', '2024-04-16', 5),
(1002, 1003, 1001, 3, 'Ponctualité moyenne.', '2024-04-17', 4),
(1003, 1004, 1002, 5, 'Super expérience.', '2024-04-18', 5),
(1004, 1005, 1003, 2, 'Voiture un peu sale.', '2024-04-19', 3),
(1005, 1006, 1004, 4, 'Chauffeur très agréable.', '2024-04-20', 5),
(1006, 1007, 1005, 5, 'Tout parfait.', '2024-04-21', 5),
(1007, 1008, 1006, 3, 'Bruit dans la voiture.', '2024-04-22', 4),
(1008, 1009, 1007, 4, 'Bon trajet.', '2024-04-23', 5),
(1009, 1000, 1008, 5, 'Je recommande !', '2024-04-24', 5);
