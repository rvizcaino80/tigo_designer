-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.5.25a - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL version:             7.0.0.4053
-- Date/time:                    2013-12-19 21:34:12
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET FOREIGN_KEY_CHECKS=0 */;

-- Dumping database structure for designer
CREATE DATABASE IF NOT EXISTS `designer` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci */;
USE `designer`;


-- Dumping structure for table designer.elements
DROP TABLE IF EXISTS `elements`;
CREATE TABLE IF NOT EXISTS `elements` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `form` int(10) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `key` varchar(50) DEFAULT NULL,
  `parent` varchar(50) DEFAULT 'root',
  `label` varchar(50) DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1703 DEFAULT CHARSET=latin1;

-- Dumping data for table designer.elements: ~6 rows (approximately)
/*!40000 ALTER TABLE `elements` DISABLE KEYS */;
INSERT INTO `elements` (`id`, `form`, `type`, `key`, `parent`, `label`, `position`) VALUES
	(1697, 1, 'container', 'containerolJLy', 'root', 'Sin Titulo', 1),
	(1698, 1, 'row', 'rowyAV9y', 'containerolJLy', NULL, 1),
	(1699, 1, 'row', 'rowc06VG', 'containerolJLy', NULL, 2),
	(1700, 1, 'textfield', 'textfieldrskq8', 'rowyAV9y', 'Sin Titulo', 1),
	(1701, 1, 'textfield', 'textfieldXuKSY', 'rowyAV9y', 'Sin Titulo', 0),
	(1702, 1, 'textfield', 'textfield6QzZs', 'rowyAV9y', 'Sin Titulo', 2);
/*!40000 ALTER TABLE `elements` ENABLE KEYS */;


-- Dumping structure for table designer.forms
DROP TABLE IF EXISTS `forms`;
CREATE TABLE IF NOT EXISTS `forms` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Dumping data for table designer.forms: ~1 rows (approximately)
/*!40000 ALTER TABLE `forms` DISABLE KEYS */;
INSERT INTO `forms` (`id`, `name`) VALUES
	(1, 'Formulario de Prueba');
/*!40000 ALTER TABLE `forms` ENABLE KEYS */;
/*!40014 SET FOREIGN_KEY_CHECKS=1 */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
