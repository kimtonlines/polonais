-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Sam 27 Janvier 2018 à 21:03
-- Version du serveur :  5.7.21-0ubuntu0.16.04.1
-- Version de PHP :  7.1.13-1+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `base2`
--

-- --------------------------------------------------------

--
-- Structure de la table `temps`
--

CREATE TABLE `temps` (
  `id` int(11) NOT NULL,
  `id_pilote` bigint(20) DEFAULT NULL,
  `id_speciale` int(11) DEFAULT NULL,
  `dates` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `temps` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `depart` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `arrivee` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `confirmation_token` varchar(180) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `nom` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `prenoms` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`id`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `confirmation_token`, `password_requested_at`, `roles`, `nom`, `prenoms`) VALUES
(1, 'elidjeaka', 'elidjeaka', 'elidjeakaemmanuel@ymail.com', 'elidjeakaemmanuel@ymail.com', 1, NULL, '$2y$13$DqRAGdvbu2Q66MF34CC2HeWYCMu2DBNfx506YNgKFBuQlcXY5a/AS', '2017-11-06 02:50:12', NULL, NULL, 'a:1:{i:0;s:16:"ROLE_SUPER_ADMIN";}', 'ELIDJE', 'AKA EMMANUEL'),
(3, 'kos', 'kos', 'p@gmail.com', 'p@gmail.com', 1, NULL, '$2y$13$8Jv9Op2EPphlKMxhVfn.4Oz2lJev4a3fvl.8LXhL0OsFYf9wme04a', '2017-02-15 11:08:09', NULL, NULL, 'a:1:{i:0;s:16:"ROLE_SUPER_ADMIN";}', 'KOSSONOU', 'PARFAIT'),
(4, 'jean.kassi', 'jean.kassi', 'jean.kassi@ymail.com', 'jean.kassi@ymail.com', 1, NULL, '$2y$13$bTD6hkQtY4oLIoIdCJxxo.kDaPkNiZRMC9w3ArLGfxUGtCBztEnz2', '2017-02-17 16:12:39', NULL, NULL, 'a:1:{i:0;s:16:"ROLE_SUPER_ADMIN";}', 'KASSI', 'JEAN VINCENT'),
(5, 'daniel', 'daniel', '001ndj@gmail.com', '001ndj@gmail.com', 1, NULL, '$2y$13$oTtS6YtlbK5UGz5xhmpA9e3agFEUUwe9TRWrFMnipssCjkhxyyhG2', '2017-09-16 20:33:46', NULL, NULL, 'a:1:{i:0;s:16:"ROLE_SUPER_ADMIN";}', 'nougbele', 'daniel'),
(7, 'ange', 'ange', 'angeyoro@gmail.com', 'angeyoro@gmail.com', 1, NULL, '$2y$13$HsOhNaWng22xDthiVqIgJe2KfBOTI8n6PH3WCkd0oi2/gHqcr9lXm', '2017-09-16 20:29:14', NULL, NULL, 'a:1:{i:0;s:16:"ROLE_SUPER_ADMIN";}', 'yoro', 'ange');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `temps`
--
ALTER TABLE `temps`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D64992FC23A8` (`username_canonical`),
  ADD UNIQUE KEY `UNIQ_8D93D649A0D96FBF` (`email_canonical`),
  ADD UNIQUE KEY `UNIQ_8D93D649C05FB297` (`confirmation_token`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `temps`
--
ALTER TABLE `temps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
