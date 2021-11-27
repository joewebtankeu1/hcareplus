-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  sam. 11 juil. 2020 à 07:56
-- Version du serveur :  10.4.6-MariaDB
-- Version de PHP :  7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `hcare`
--

-- --------------------------------------------------------

--
-- Structure de la table `component`
--

/*CREATE TABLE `component` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code_unique` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `code_parent` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;*/

--
-- Déchargement des données de la table `component`
--

INSERT INTO `component` (`id`, `code_unique`, `libelle`, `description`, `code_parent`, `created_at`, `updated_at`) VALUES
(1, 'HOMHOM062617219894', 'home', 'Component de la page Home', NULL, '2020-06-26 16:21:13', '2020-06-26 16:21:13'),
(2, 'HOMHEA062617265067', 'Header', 'component d\'entête de la page Home', 'HOMHOM062617219894', '2020-06-26 16:26:36', '2020-06-26 16:28:29'),
(4, 'HOMTAB062708371290', 'Tabs', 'Component de la liste des menus d\'onglet de la page Home', 'HOMHOM062617219894', '2020-06-27 07:37:47', '2020-06-27 07:37:47'),
(5, 'TABFIC062708413959', 'Fichier', 'Menu d\'onglet de la page Home', 'HOMTAB062708371290', '2020-06-27 07:41:20', '2020-06-27 07:41:20'),
(6, 'TABACC062708426348', 'Accueil', 'Menu d\'onglet de la page Home', 'HOMTAB062708371290', '2020-06-27 07:42:18', '2020-06-27 07:42:18'),
(7, 'TABMED062708428034', 'Medical', 'Menu d\'onglet de la page Home', 'HOMTAB062708371290', '2020-06-27 07:42:37', '2020-06-27 07:42:37'),
(8, 'TABEXA062708431896', 'Examen', 'Menu d\'onglet de la page Home', 'HOMTAB062708371290', '2020-06-27 07:43:33', '2020-06-27 07:43:33'),
(9, 'HOMTAB062713172886', 'TabsList', 'Liste des contenus d\'onglet de la page Home', 'HOMHOM062617219894', '2020-06-27 12:17:41', '2020-06-27 12:17:41'),
(10, 'HOMCAL062713183067', 'Calender', 'Calendrier de la page Home', 'HOMHOM062617219894', '2020-06-27 12:18:56', '2020-06-27 12:18:56'),
(12, 'TABFIC062713391221', 'FichierBox', 'Onglet fichier de la page Home', 'HOMTAB062713172886', '2020-06-27 12:39:32', '2020-06-27 12:39:32'),
(13, 'TABMED062713417522', 'MedicalBox', 'Onglet Medical de la page Home', 'HOMTAB062713172886', '2020-06-27 12:41:35', '2020-06-27 12:41:35'),
(14, 'TABACC062713424027', 'AccueilBox', 'Onglet Accueil de la page Home', 'HOMTAB062713172886', '2020-06-27 12:42:09', '2020-06-27 12:42:09'),
(15, 'TABEXA062713422047', 'ExamenBox', 'Onglet Examen de la page Home', 'HOMTAB062713172886', '2020-06-27 12:42:38', '2020-06-27 12:42:38'),
(16, 'IDEIDE070407561821', 'Identification', '', NULL, '2020-07-04 06:56:42', '2020-07-04 06:56:42'),
(17, 'IDEAPP070408033246', 'Appmenu', '', 'IDEIDE070407561821', '2020-07-04 07:03:53', '2020-07-04 07:03:53'),
(18, 'IDECON070410145822', 'Contenu principal', 'Component qui englobe tous les components de la page identification', 'IDEIDE070407561821', '2020-07-04 09:14:01', '2020-07-04 09:14:01'),
(19, 'IDEENT070410193325', 'Entete', 'Component d\'entête de la page identification', 'IDECON070410145822', '2020-07-04 09:19:44', '2020-07-04 09:19:44'),
(20, 'CONZON070410359020', 'Zone de recherche', 'Component qui contient la zone de recherche de la page identification', 'IDECON070410145822', '2020-07-04 09:35:54', '2020-07-04 09:35:54'),
(21, 'CONTAB070410405883', 'Table patients', 'Component qui contient le tableau des patients récents de la page identification', 'IDECON070410145822', '2020-07-04 09:40:38', '2020-07-04 09:40:38'),
(22, 'CONBOU070410424997', 'Bouton nouveau patient', 'Component qui contient le bouton permettant d\'enregistrer un nouveau patient', 'IDECON070410145822', '2020-07-04 09:42:54', '2020-07-04 09:42:54'),
(23, 'CONPOP070410436161', 'Popup nouveau patient', 'Component qui contient la pop-up permettant d\'enregistrer un nouveau patient', 'IDECON070410145822', '2020-07-04 09:43:50', '2020-07-04 09:43:50'),
(24, 'PATPAT070411003309', 'Patient', 'Page patient', NULL, '2020-07-04 10:00:53', '2020-07-04 10:00:53'),
(25, 'PATAPP070411035747', 'App menu', 'Menu lateral de l\'application dans la page Patient', 'PATPAT070411003309', '2020-07-04 10:03:27', '2020-07-04 10:03:27'),
(26, 'PATCOM070411054211', 'Component principal', 'Component qui englobe tous les autres components de la page Patient propement dite', 'PATPAT070411003309', '2020-07-04 10:05:46', '2020-07-04 10:05:46'),
(27, 'COMENT070411079858', 'Entete de Patient', 'Component de l\'entête de la page Patient', 'PATCOM070411054211', '2020-07-04 10:07:38', '2020-07-04 10:07:38'),
(28, 'COMASI070411093185', 'Aside de Patient', 'Component de la partie gauche de la page Patient', 'PATCOM070411054211', '2020-07-04 10:09:32', '2020-07-04 10:09:32'),
(29, 'COMART070411096361', 'Article de Patient', 'Component de la partie droite de la page Patient', 'PATCOM070411054211', '2020-07-04 10:09:58', '2020-07-04 10:09:58'),
(30, 'ASIRES070412487981', 'Resume patient', 'Component qui affiche les infos du patient à gauche de la page Patient. C\'est dans ce component que se trouve son avatar d\'ailleurs.', 'COMASI070411093185', '2020-07-04 11:48:10', '2020-07-04 11:48:10'),
(31, 'ASILIS07041250347', 'Liste d\'onglet de patient', 'Ce component contient la liste de tous les onglets d\'aside. à l\'instar de Accueil, Fiche, ...', 'COMASI070411093185', '2020-07-04 11:50:42', '2020-07-04 11:50:42'),
(32, 'LISONG070412537788', 'Onglet Accueil', 'Onglet Accueil de la partie gauche de la page Patient.', 'ASILIS07041250347', '2020-07-04 11:53:10', '2020-07-04 11:53:10'),
(33, 'LISONG070412541123', 'Onglet Fiche', 'Onglet Fiche de la partie gauche de la page Patient.', 'ASILIS07041250347', '2020-07-04 11:54:17', '2020-07-04 11:54:17'),
(34, 'LISONG07041255153', 'Onglet Courriers', 'Onglet Courriers de la partie gauche de la page Patient.', 'ASILIS07041250347', '2020-07-04 11:55:50', '2020-07-04 11:55:50'),
(35, 'ASICON070413225447', 'Conteneurs d\'onglet d\'Aside', 'Tous les conteneurs d\'onglet de la partie gauche de la page Patient.', 'COMASI070411093185', '2020-07-04 12:22:01', '2020-07-04 12:22:01'),
(36, 'ARTENT071104187382', 'Entête de Article', 'L\'entête de la partie droite de la page Patient', 'COMART070411096361', '2020-07-11 03:18:42', '2020-07-11 03:18:42'),
(37, 'ARTMEN071104256835', 'Menu d\'onglet de Article', 'Menu d\'onglet de la partie droite de la page Patient. Contact, Paramètres, ...', 'COMART070411096361', '2020-07-11 03:25:01', '2020-07-11 03:25:01'),
(38, 'MENOPT071104277373', 'Option contact du menu d\'onglet de Article', 'Option contact du menu d\'onglet de la partie droite de la page Patient.', 'ARTMEN071104256835', '2020-07-11 03:27:15', '2020-07-11 03:27:15'),
(39, 'MENOPT071104291224', 'Option paramètres du menu d\'onglet de Article', 'Option paramètres du menu d\'onglet de la partie droite de la page Patient.', 'ARTMEN071104256835', '2020-07-11 03:29:33', '2020-07-11 03:29:33'),
(40, 'MENOPT071104311538', 'Option assurance du menu d\'onglet de Article', 'Option assurance du menu d\'onglet de la partie droite de la page Patient.', 'ARTMEN071104256835', '2020-07-11 03:31:28', '2020-07-11 03:31:28'),
(41, 'MENOPT071104326863', 'Option rendez-vous du menu d\'onglet de Article', 'Option rendez-vous du menu d\'onglet de la partie droite de la page Patient.', 'ARTMEN071104256835', '2020-07-11 03:32:37', '2020-07-11 03:32:37'),
(42, 'MENOPT071104334281', 'Option actes du menu d\'onglet de Article', 'Option actes du menu d\'onglet de la partie droite de la page Patient.', 'ARTMEN071104256835', '2020-07-11 03:33:45', '2020-07-11 03:33:45'),
(43, 'ARTCON071104354056', 'Conteneur d\'onglet de Article', 'Component qui contient tous les onglets de la page Patient.', 'COMART070411096361', '2020-07-11 03:35:47', '2020-07-11 03:35:47'),
(44, 'CONONG071104381795', 'Onglet contact de Article', 'Onglet contact de la partie droite de la page Patient.', 'ARTCON071104354056', '2020-07-11 03:38:43', '2020-07-11 03:38:43'),
(45, 'ONGPRE071104431853', 'Premier composant d\'onglet de contact', 'Il s\'agit du premier composant de l\'onglet contact qui se trouve dans la partie droite de la page Patient.', 'CONONG071104381795', '2020-07-11 03:43:01', '2020-07-11 03:43:01'),
(46, 'PREMEN071104454095', 'Menu d\'onglet du 1er composant de contact', 'Il s\'agit du menu d\'onglet du premier composant de l\'onglet contact qui se trouve dans la partie droite de la page Patient.', 'ONGPRE071104431853', '2020-07-11 03:45:37', '2020-07-11 03:45:37'),
(47, 'MENOPT071104527015', 'Option Patient du 1er Menu de Contact', 'Il s\'agit de l\'option Patient du menu d\'onglet du premier composant de l\'onglet contact qui se trouve dans la partie droite de la page Patient.', 'PREMEN071104454095', '2020-07-11 03:52:25', '2020-07-11 03:52:25'),
(48, 'MENOPT071104539110', 'Option Adresse du 1er Menu de Contact', 'Il s\'agit de l\'option Adresse du menu d\'onglet du premier composant de l\'onglet contact qui se trouve dans la partie droite de la page Patient.', 'PREMEN071104454095', '2020-07-11 03:53:10', '2020-07-11 03:53:10'),
(49, 'PRECON071104554033', 'Conteneur d\'onglet du 1er composant de Contact', 'Il s\'agit du composant qui contient tous les onglets du premier composant de l\'onglet contact qui se trouve dans la partie droite de la page Patient.', 'ONGPRE071104431853', '2020-07-11 03:55:25', '2020-07-11 03:55:25'),
(50, 'CONONG071104582851', 'Onglet Patient du 1er composant de Contact', 'Il s\'agit de l\'onglet Patient  du premier composant de l\'onglet contact qui se trouve dans la partie droite de la page Patient.', 'PRECON071104554033', '2020-07-11 03:58:13', '2020-07-11 03:58:13'),
(51, 'ONGCHA071105016659', 'Champs Titre de l\'onglet Patient du 1er composant de Contact', 'Il s\'agit du champs Titre de l\'onglet Patient  du premier composant de l\'onglet contact qui se trouve dans la partie droite de la page Patient.', 'CONONG071104582851', '2020-07-11 04:01:33', '2020-07-11 04:01:33'),
(52, 'ONGCHA071105391670', 'Champs Nom de l\'onglet Patient du 1er composant de Contact', 'Il s\'agit du champs Nom de l\'onglet Patient  du premier composant de l\'onglet contact qui se trouve dans la partie droite de la page Patient.', 'CONONG071104582851', '2020-07-11 04:39:24', '2020-07-11 04:39:24'),
(53, 'ONGCHA071105419808', 'Champs Prénom de l\'onglet Patient du 1er composant de Contact', 'Il s\'agit du champs Prénom de l\'onglet Patient  du premier composant de l\'onglet contact qui se trouve dans la partie droite de la page Patient.', 'CONONG071104582851', '2020-07-11 04:41:44', '2020-07-11 04:41:44'),
(54, 'ONGCHA071105429692', 'Champs Age de l\'onglet Patient du 1er composant de Contact', 'Il s\'agit du champs Age de l\'onglet Patient  du premier composant de l\'onglet contact qui se trouve dans la partie droite de la page Patient.', 'CONONG071104582851', '2020-07-11 04:42:39', '2020-07-11 04:42:39'),
(55, 'ONGCHA071105438504', 'Champs Date de naissance de l\'onglet Patient du 1er composant de Contact', 'Il s\'agit du champs Date de naissance de l\'onglet Patient  du premier composant de l\'onglet contact qui se trouve dans la partie droite de la page Patient.', 'CONONG071104582851', '2020-07-11 04:43:47', '2020-07-11 04:43:47'),
(56, 'ONGCHA07110544108', 'Champs Prénom de la mère de l\'onglet Patient du 1er composant de Contact', 'Il s\'agit du champs Prénom de la mère de l\'onglet Patient  du premier composant de l\'onglet contact qui se trouve dans la partie droite de la page Patient.', 'CONONG071104582851', '2020-07-11 04:44:46', '2020-07-11 04:44:46'),
(57, 'ONGCHA071105453795', 'Champs Sexe de l\'onglet Patient du 1er composant de Contact', 'Il s\'agit du champs Sexe de l\'onglet Patient  du premier composant de l\'onglet contact qui se trouve dans la partie droite de la page Patient.', 'CONONG071104582851', '2020-07-11 04:45:42', '2020-07-11 04:45:42'),
(58, 'ONGCHA071105463599', 'Champs Langue de l\'onglet Patient du 1er composant de Contact', 'Il s\'agit du champs Langue de l\'onglet Patient  du premier composant de l\'onglet contact qui se trouve dans la partie droite de la page Patient.', 'CONONG071104582851', '2020-07-11 04:46:44', '2020-07-11 04:46:44'),
(59, 'ONGCHA07110547231', 'Champs Groupe sanguin de l\'onglet Patient du 1er composant de Contact', 'Il s\'agit du champs Groupe sanguin de l\'onglet Patient  du premier composant de l\'onglet contact qui se trouve dans la partie droite de la page Patient.', 'CONONG071104582851', '2020-07-11 04:47:38', '2020-07-11 04:47:38'),
(60, 'ONGCHA071105493484', 'Champs Numéro de pièce d\'identité de l\'onglet Patient du 1er composant de Contact', 'Il s\'agit du champs Numéro de pièce d\'identité de l\'onglet Patient  du premier composant de l\'onglet contact qui se trouve dans la partie droite de la page Patient.', 'CONONG071104582851', '2020-07-11 04:49:00', '2020-07-11 04:49:00'),
(61, 'ONGCHA071105502739', 'Champs Date d\'inscription de l\'onglet Patient du 1er composant de Contact', 'Il s\'agit du champs Date d\'inscription de l\'onglet Patient  du premier composant de l\'onglet contact qui se trouve dans la partie droite de la page Patient.', 'CONONG071104582851', '2020-07-11 04:50:26', '2020-07-11 04:50:26');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `component`
--
/*ALTER TABLE `component`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `component_code_unique_unique` (`code_unique`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `component`
--
ALTER TABLE `component`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;
COMMIT;*/

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
