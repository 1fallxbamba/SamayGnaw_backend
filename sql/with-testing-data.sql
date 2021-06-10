-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 10, 2021 at 06:25 PM
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
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `sgi`, `nom`, `prenom`, `tel`, `genre`, `cou`, `epaule`, `poitrine`, `ceinture`, `tourBras`, `tourPoignet`, `longManche`, `longPant`, `longTaille`, `longCaftan`, `tourCuisse`, `tourCheville`) VALUES
(1, 'AA11', 'Ndiaye', 'Ousmane', 771234567, 'M', '11.0', '22.0', '24.0', '42.0', '33.0', '36.0', '63.0', '44.0', '48.0', '84.0', '55.0', '60.0'),
(2, 'AB12', 'Diop', 'Fatou', 777654321, 'F', '22.0', '11.0', '24.0', '33.0', '42.0', '48.0', '44.0', '48.0', '63.0', '55.0', '38.0', '83.0'),
(3, 'CC20', 'Sy', 'Lamine', 771122334, 'M', '12.0', '34.0', '56.0', '78.0', '90.0', '11.0', '31.0', '51.0', '16.0', '71.0', '18.0', '91.0'),
(4, 'AC11', 'Ba', 'Maty', 773344556, 'F', '35.0', '43.0', '65.0', '87.0', '20.0', '91.0', '13.0', '15.0', '61.0', '17.0', '81.0', '19.0'),
(5, 'GC2', 'Diagne', 'Aminata', 773698733, 'F', '53.0', '44.0', '78.0', '90.0', '12.0', '20.0', '17.0', '31.0', '22.0', '145.0', '91.0', '14.0'),
(6, 'CA21', 'Samb', 'Abdou', 773298639, 'M', '30.0', '40.0', '60.0', '93.0', '38.7', '19.0', '22.6', '42.8', '36.0', '111.0', '79.0', '27.0');

-- --------------------------------------------------------

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
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gnaws`
--

INSERT INTO `gnaws` (`id`, `sgi`, `prop`, `salon`, `dateC`, `dateL`, `prix`, `avance`, `etat`, `type`) VALUES
(1, 'GN11', 'GC2', 'SGNSL11', '2021-06-10 12:45:33', '2021-07-11 18:45:00', 30000, 15000, 'Attente paiement', 'Robe'),
(2, 'GN02', 'AA11', 'SGNSL21', '2021-06-10 14:35:12', '2021-07-11 12:30:00', 15000, 0, 'En cours', 'Grand boubou'),
(3, 'GN04', 'CC20', 'SGNSL11', '2021-06-10 14:37:41', '2021-07-11 16:30:00', 150000, 150000, 'Terminé', 'VIP');

-- --------------------------------------------------------

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
  `statut` varchar(50) DEFAULT 'Pending',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

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
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

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
