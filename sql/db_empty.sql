-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 11, 2021 at 03:59 PM
-- Server version: 5.7.26
-- PHP Version: 7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `samaygnaw`
--

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
CREATE TABLE IF NOT EXISTS `clients` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `sgi` varchar(255) DEFAULT NULL,
  `nom` varchar(50) DEFAULT NULL,
  `prenom` varchar(255) DEFAULT NULL,
  `tel` int(10) DEFAULT NULL,
  `genre` varchar(10) DEFAULT NULL,
  `cou` decimal(4,1) DEFAULT '0.0',
  `epaule` decimal(4,1) DEFAULT '0.0',
  `poitrine` decimal(4,1) DEFAULT '0.0',
  `ceinture` decimal(4,1) DEFAULT '0.0',
  `tourBras` decimal(4,1) DEFAULT '0.0',
  `tourPoignet` decimal(4,1) DEFAULT '0.0',
  `longManche` decimal(4,1) DEFAULT '0.0',
  `longPant` decimal(4,1) DEFAULT '0.0',
  `longTaille` decimal(4,1) DEFAULT '0.0',
  `longCaftan` decimal(4,1) DEFAULT '0.0',
  `tourCuisse` decimal(4,1) DEFAULT '0.0',
  `tourCheville` decimal(4,1) DEFAULT '0.0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `gnaws`
--

DROP TABLE IF EXISTS `gnaws`;
CREATE TABLE IF NOT EXISTS `gnaws` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `sgi` varchar(255) NOT NULL,
  `prop` varchar(255) NOT NULL,
  `salon` varchar(255) NOT NULL,
  `dateC` datetime DEFAULT CURRENT_TIMESTAMP,
  `dateL` datetime DEFAULT NULL,
  `prix` int(10) DEFAULT NULL,
  `avance` int(10) DEFAULT '0',
  `etat` varchar(100) DEFAULT 'En cours',
  `type` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`,`sgi`,`prop`,`salon`),
  KEY `prop` (`prop`),
  KEY `salon` (`salon`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `requests`
--

DROP TABLE IF EXISTS `requests`;
CREATE TABLE IF NOT EXISTS `requests` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) DEFAULT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `tel` int(10) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `shadow` varchar(255) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `statut` varchar(50) DEFAULT 'Pending',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `salons`
--

DROP TABLE IF EXISTS `salons`;
CREATE TABLE IF NOT EXISTS `salons` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `sgi` varchar(255) DEFAULT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `tel` int(10) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) DEFAULT NULL,
  `shadow` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `login` (`login`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
