-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : jeu. 19 nov. 2026 à 20:28
-- Version du serveur : 5.7.24
-- Version de PHP : 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `phonestore`
--

-- --------------------------------------------------------

--
-- Structure de la table `articles`
--

CREATE TABLE `articles` (
  `id_art` int(11) NOT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `quantite` int(11) DEFAULT NULL,
  `prix` float DEFAULT NULL,
  `url_photo` varchar(255) DEFAULT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `articles`
--

INSERT INTO `articles` (`id_art`, `nom`, `quantite`, `prix`, `url_photo`, `description`) VALUES
(1, 'iPhone 18', 495, 750, 'images/iphone18.png', 'Écran Super Retina XDR 6.3 pouces, puce A18, module photo avancé. 256 Go, Neuf - Débloqué tout opérateur.'),
(2, 'iPhone 17', 138, 680, 'images/iphone17.png', 'Écran Super Retina XDR 6.1 pouces, puce A17 Pro, double appareil photo 48 MP, USB-C. 128 Go, Reconditionné - Très bon état.'),
(3, 'iPhone 16', 152, 650, 'images/iphone16.webp', 'Écran Super Retina XDR 6.1 pouces, puce A16 Bionic, double appareil photo 48 MP, USB-C. 128 Go, Reconditionné - Très bon état.'),
(4, 'Samsung Galaxy S24 Ultra', 85, 1250, 'images/samsung_s24.jpg', 'Écran Dynamic AMOLED 2X 6.8 pouces, processeur Snapdragon de pointe, capteur principal 200 MP, S-Pen intégré. 512 Go, Neuf - Débloqué tout opérateur.'),
(5, 'iPhone 15', 8, 759, 'images/iphone15.webp', 'Écran Super Retina XDR 6.1 pouces, puce A16 Bionic, double appareil photo 48 MP, USB-C. 128 Go, Reconditionné - Très bon état.'),
(6, 'Galaxy Z Fold 8', 3, 1899, 'images/zfold8.png', 'Smartphone pliable, écran AMOLED dynamique 7.6 pouces, processeur Snapdragon, stylet intégré. 256 Go, Neuf - Débloqué tout opérateur.'),
(7, 'Google Pixel 10', 12, 799, 'images/googlepixel10.jpg', 'Modèle : Google Pixel 10 | Espace de stockage : 128 Go | Coloris : Noir Volcanique | État : Neuf (Débloqué). Écran OLED Actua lumineux, propulsé par la nouvelle puce Tensor de Google avec des fonctionnalités avancées d\'intelligence artificielle pour la photo et la traduction en temps réel, et un double capteur photo de pointe.'),
(8, 'Google Pixel 11', 8, 999, 'images/googlepixel11.jpg', 'Modèle : Google Pixel 11 | Espace de stockage : 256 Go | Coloris : Gris Porcelaine | État : Neuf (Version 5G). Écran haute résolution 120 Hz, performances graphiques optimisées, capacités photo professionnelles en conditions de basse luminosité et autonomie renforcée pour toute la journée.'),
(9, 'Xiaomi Redmi Note 16', 20, 269, 'images/xiaomiredminote16.jpg', 'Modèle : Xiaomi Redmi Note 16 | Espace de stockage : 128 Go | Coloris : Bleu Glacier | État : Neuf. Excellent rapport qualité-prix, écran AMOLED fluide FHD+, processeur performant pour un usage quotidien fluide, triple capteur photo polyvalent et batterie longue durée avec charge rapide.'),
(10, 'Xiaomi Redmi Note 17', 15, 299, 'images/xiaomiredminote17.jpg', 'Modèle : Xiaomi Redmi Note 17 | Espace de stockage : 256 Go | Coloris : Noir Minuit | État : Neuf. Écran AMOLED ultra-lumineux, taux de rafraîchissement élevé, processeur optimisé pour le multimédia et le jeu, capteur principal haute définition stabilisé et charge rapide améliorée.'),
(11, 'Samsung Galaxy S25 Ultra', 15, 1099, 'images/samsunggalaxys25ultra.webp', 'Modèle : Samsung Galaxy S25 Ultra | Espace de stockage : 256 Go | Mémoire vive : 12 Go | Coloris : Noir Titane | État : Neuf - Débloqué tout opérateur. Grand écran Dynamic AMOLED 2X de 6,9 pouces avec définition QHD+ et rafraîchissement adaptatif de 1 à 120 Hz pour une navigation fluide. Système photo comprenant un capteur principal de 200 MP stabilisé, un ultra grand-angle de 50 MP et deux téléobjectifs offrant des zooms optiques 3x et 5x. Batterie de 5 000 mAh, recharge filaire avec adaptateur compatible de 45 W vendu séparément et recharge sans fil. Compatible 5G et Wi-Fi 7.'),
(12, 'OnePlus 13', 20, 899, 'images/oneplus13.png', 'Modèle : OnePlus 13 | Espace de stockage : 512 Go | Mémoire vive : 16 Go | Coloris : Noir Black Eclipse | État : Neuf - Débloqué tout opérateur. Écran ProXDR LTPO de 6,82 pouces avec définition QHD+ et rafraîchissement adaptatif de 1 à 120 Hz. Processeur Snapdragon 8 Elite pour les applications exigeantes, le multitâche et les jeux. Trois appareils photo arrière de 50 MP, comprenant un capteur principal stabilisé, un ultra grand-angle et un téléobjectif avec zoom optique 3x. Batterie de 6 000 mAh compatible avec la recharge filaire SUPERVOOC 100 W et la recharge sans fil AIRVOOC 50 W, avec chargeurs compatibles. Connectivité 5G et Wi-Fi 7.'),
(13, 'Xiaomi 14T Pro', 25, 599, 'images/xiaomi14tpro.webp', 'Modèle : Xiaomi 14T Pro | Espace de stockage : 512 Go | Mémoire vive : 12 Go | Coloris : Noir Titane | État : Neuf - Débloqué tout opérateur. Écran AMOLED de définition 2 712 x 1 220 pixels avec un taux de rafraîchissement pouvant atteindre 144 Hz et une compatibilité HDR10+ et Dolby Vision. Processeur MediaTek Dimensity 9300+ adapté au multitâche et aux jeux. Triple appareil photo Leica : capteur principal de 50 MP avec stabilisation optique, téléobjectif de 50 MP et ultra grand-angle de 12 MP. Batterie de 5 000 mAh compatible avec la recharge HyperCharge 120 W et la recharge sans fil 50 W. Chargeurs compatibles vendus séparément. Compatible 5G.'),
(14, 'Google Pixel 9 Pro', 12, 749, 'images/googlepixel9pro.webp', 'Modèle : Google Pixel 9 Pro | Espace de stockage : 256 Go | Mémoire vive : 16 Go | Coloris : Noir | État : Neuf - Débloqué tout opérateur. Écran Super Actua OLED LTPO de 6,3 pouces avec définition de 1 280 x 2 856 pixels et rafraîchissement adaptatif de 1 à 120 Hz. Puce Google Tensor G4 accompagnée du coprocesseur de sécurité Titan M2. Triple appareil photo arrière comprenant un capteur principal de 50 MP, un ultra grand-angle de 48 MP avec mise au point macro et un téléobjectif de 48 MP avec zoom optique 5x. Caméra avant de 42 MP avec autofocus. Batterie de 4 700 mAh, recharge USB-C et recharge sans fil Qi. Fonctions photo Vision de nuit, Gomme magique et Meilleure prise.'),
(15, 'iPhone 16 Pro', 15, 899, 'images/iphone16pro.webp', 'Modèle : iPhone 16 Pro | Espace de stockage : 256 Go | Coloris : Titane noir | État : Neuf - Débloqué tout opérateur. Écran OLED Super Retina XDR de 6,3 pouces avec technologie ProMotion et rafraîchissement adaptatif jusqu’à 120 Hz. Puce A18 Pro adaptée aux applications exigeantes, aux jeux et au traitement des photos. Système photo professionnel avec caméra principale Fusion de 48 MP, ultra grand-angle de 48 MP et téléobjectif de 12 MP avec zoom optique 5x. Enregistrement vidéo Dolby Vision 4K jusqu’à 120 images par seconde avec la caméra principale. Design en titane, reconnaissance faciale Face ID, port USB-C, recharge sans fil MagSafe et connectivité 5G.'),
(16, 'Samsung Galaxy A56 5G', 25, 399, 'images/samsunggalaxya56.webp', 'Modèle : Samsung Galaxy A56 5G | Espace de stockage : 256 Go | Coloris : Graphite | État : Neuf - Débloqué tout opérateur. Grand écran Super AMOLED de 6,7 pouces avec définition Full HD+ et taux de rafraîchissement de 120 Hz pour une navigation fluide. Triple appareil photo arrière comprenant un capteur principal de 50 MP avec stabilisation optique, un ultra grand-angle de 12 MP et un capteur macro de 5 MP. Caméra avant de 12 MP pour les selfies et les appels vidéo. Batterie de 5 000 mAh compatible avec la charge rapide filaire de 45 W, avec adaptateur compatible vendu séparément. Connectivité 5G, lecteur d’empreintes digitales et résistance à l’eau et à la poussière certifiée IP67.');

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE `clients` (
  `id_client` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `adresse` text NOT NULL,
  `numero` varchar(15) NOT NULL,
  `mail` varchar(100) NOT NULL,
  `mdp` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `clients`
--

INSERT INTO `clients` (`id_client`, `nom`, `prenom`, `adresse`, `numero`, `mail`, `mdp`) VALUES
(1, 'Ikdoumi', 'Ilian', 'Montpellier', '07553816', 'ikdoumi.ilian@gmail.com', '$2y$10$aZKN30HtjBzp9evpg23MvOMoJ7j11FcZbrwLZt35vx1BjB4SKtSMS'),
(2, 'Marcaux', 'Clemence', 'Lyon', '0348520', 'clem@mail', '$2y$10$tDiYPuLW6EoHu8ETGIq5NOb.YuoifdcSM8pu4NKXQ1xphZY8p/oaW'),
(3, 'Michel', 'Mich', 'Londres', '0402805', 'M.m@mail', '$2y$10$vqLBdcj/8UY8mNKKl2s8Au5DsFyJCNI.HTphWvcG/PnPWEkDaeCEa'),
(4, 'Guerrin', 'Raphael', 'Paris', '07315368316', 'r@mail', '$2y$10$VI.XYlBBf/fVukoeqbB4K.JTwDmtvGGwBk79XjQnBqBfY2Bw.4iLS'),
(5, 'Jack', 'Lock', 'Londre', '14573485', 'j@mail', '$2y$10$w45sXmRe/WBcI4rx2R45oeSYhCff0oDavxNiRlse.LBUxmISKpyiO'),
(6, 'Ikdoumi', 'Ilian', 'd', '0', 'ikdoumi.ilian1@gmail.com', '$2y$10$8YcXi9rPQxpYeIVoZNZ3v.Pj6e3IL3W9l8mEpMDmHea1hny5Hav/2');

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

CREATE TABLE `commandes` (
  `id_commande` int(11) NOT NULL,
  `id_art` int(11) DEFAULT NULL,
  `id_client` int(11) DEFAULT NULL,
  `quantite` int(11) DEFAULT NULL,
  `envoi` tinyint(1) DEFAULT '0',
  `prix` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `commandes`
--

INSERT INTO `commandes` (`id_commande`, `id_art`, `id_client`, `quantite`, `envoi`, `prix`) VALUES
(1, 2, 1, 1, 1, '680.00'),
(2, 3, 1, 1, 1, '650.00'),
(3, 5, 1, 2, 0, '759.00'),
(4, 6, 4, 1, 0, '1899.00'),
(5, 6, 4, 2, 0, '1899.00'),
(6, 6, 4, 3, 0, '1899.00'),
(7, 1, 1, 1, 0, '750.00'),
(8, 1, 1, 1, 0, '750.00'),
(9, 3, 5, 1, 0, '650.00'),
(10, 1, 1, 1, 0, '750.00'),
(11, 3, 1, 1, 0, '650.00');

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id_message` int(11) NOT NULL,
  `id_client` int(11) DEFAULT NULL,
  `contenu` varchar(256) NOT NULL,
  `date_envoi` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `panier_tampon`
--

CREATE TABLE `panier_tampon` (
  `id` int(11) NOT NULL,
  `id_art` int(11) NOT NULL,
  `id_client` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  `date_ajout` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `panier_tampon`
--

INSERT INTO `panier_tampon` (`id`, `id_art`, `id_client`, `quantite`, `date_ajout`) VALUES
(1, 2, 1, 1, '2026-11-12 19:48:11'),
(2, 1, 1, 1, '2026-11-12 19:54:00');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id_art`);

--
-- Index pour la table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id_client`),
  ADD UNIQUE KEY `unique_clients_mail` (`mail`);

--
-- Index pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`id_commande`),
  ADD KEY `id_art` (`id_art`),
  ADD KEY `id_client` (`id_client`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id_message`),
  ADD KEY `id_client` (`id_client`);

--
-- Index pour la table `panier_tampon`
--
ALTER TABLE `panier_tampon`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_art` (`id_art`),
  ADD KEY `id_client` (`id_client`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `articles`
--
ALTER TABLE `articles`
  MODIFY `id_art` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `clients`
--
ALTER TABLE `clients`
  MODIFY `id_client` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id_commande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id_message` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `panier_tampon`
--
ALTER TABLE `panier_tampon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD CONSTRAINT `commandes_ibfk_1` FOREIGN KEY (`id_art`) REFERENCES `articles` (`id_art`),
  ADD CONSTRAINT `commandes_ibfk_2` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id_client`);

--
-- Contraintes pour la table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id_client`);

--
-- Contraintes pour la table `panier_tampon`
--
ALTER TABLE `panier_tampon`
  ADD CONSTRAINT `panier_tampon_ibfk_1` FOREIGN KEY (`id_art`) REFERENCES `articles` (`id_art`),
  ADD CONSTRAINT `panier_tampon_ibfk_2` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id_client`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
