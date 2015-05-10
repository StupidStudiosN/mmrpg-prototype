-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.6.17 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             9.1.0.4867
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table pluto1_mmrpg2k11.mmrpg_categories
CREATE TABLE IF NOT EXISTS `mmrpg_categories` (
  `category_id` mediumint(8) NOT NULL AUTO_INCREMENT COMMENT 'Category ID',
  `category_level` mediumint(8) NOT NULL DEFAULT '5' COMMENT 'Category Level',
  `category_name` varchar(255) NOT NULL COMMENT 'Category Name',
  `category_token` varchar(255) NOT NULL COMMENT 'Category Token',
  `category_description` text CHARACTER SET latin1 NOT NULL COMMENT 'Category Description',
  `category_published` smallint(1) NOT NULL DEFAULT '1' COMMENT 'Category Published',
  `category_order` mediumint(8) NOT NULL DEFAULT '0' COMMENT 'Category Order',
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
