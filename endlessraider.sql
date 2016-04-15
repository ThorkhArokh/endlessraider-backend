-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Serveur: 127.0.0.1
-- Généré le : Ven 15 Avril 2016 à 21:49
-- Version du serveur: 5.1.54
-- Version de PHP: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `endlessraider`
--

-- --------------------------------------------------------

--
-- Structure de la table `er_droit`
--

CREATE TABLE IF NOT EXISTS `er_droit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `er_droit`
--


-- --------------------------------------------------------

--
-- Structure de la table `er_event`
--

CREATE TABLE IF NOT EXISTS `er_event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `dateDebut` datetime NOT NULL,
  `dateFin` datetime NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `idTypeEvt` int(11) NOT NULL,
  `idJeu` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `er_event`
--


-- --------------------------------------------------------

--
-- Structure de la table `er_evtjoueur`
--

CREATE TABLE IF NOT EXISTS `er_evtjoueur` (
  `idEvt` int(11) NOT NULL,
  `idJoueur` int(11) NOT NULL,
  PRIMARY KEY (`idEvt`,`idJoueur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `er_evtjoueur`
--


-- --------------------------------------------------------

--
-- Structure de la table `er_jeu`
--

CREATE TABLE IF NOT EXISTS `er_jeu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `er_jeu`
--


-- --------------------------------------------------------

--
-- Structure de la table `er_joueur`
--

CREATE TABLE IF NOT EXISTS `er_joueur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `idDroit` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `er_joueur`
--


-- --------------------------------------------------------

--
-- Structure de la table `er_typeevt`
--

CREATE TABLE IF NOT EXISTS `er_typeevt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `codeCategorie` varchar(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `er_typeevt`
--

