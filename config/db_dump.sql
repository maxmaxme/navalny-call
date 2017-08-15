-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.5.42 - Source distribution
-- Server OS:                    osx10.6
-- HeidiSQL Version:             9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for navalny-call
CREATE DATABASE IF NOT EXISTS `navalny-call` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `navalny-call`;

-- Dumping structure for table navalny-call.city_codes
CREATE TABLE IF NOT EXISTS `city_codes` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Dumping data for table navalny-call.city_codes: 3 rows
/*!40000 ALTER TABLE `city_codes` DISABLE KEYS */;
INSERT INTO `city_codes` (`ID`, `Code`) VALUES
  (1, 495),
  (2, 499),
  (3, 812);
/*!40000 ALTER TABLE `city_codes` ENABLE KEYS */;

-- Dumping structure for table navalny-call.log
CREATE TABLE IF NOT EXISTS `log` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ButtonID` int(11) NOT NULL,
  `PhoneID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `DateTime` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table navalny-call.log: 0 rows
/*!40000 ALTER TABLE `log` DISABLE KEYS */;
/*!40000 ALTER TABLE `log` ENABLE KEYS */;

-- Dumping structure for table navalny-call.phones
CREATE TABLE IF NOT EXISTS `phones` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Phone` bigint(20) NOT NULL,
  `UserID` int(11) NOT NULL,
  `StatusID` int(11) NOT NULL COMMENT '0 — идёт разговор, 1 — завершён',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table navalny-call.phones: 0 rows
/*!40000 ALTER TABLE `phones` DISABLE KEYS */;
/*!40000 ALTER TABLE `phones` ENABLE KEYS */;

-- Dumping structure for table navalny-call.script
CREATE TABLE IF NOT EXISTS `script` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Text` varchar(1000) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- Dumping data for table navalny-call.script: 8 rows
/*!40000 ALTER TABLE `script` DISABLE KEYS */;
INSERT INTO `script` (`ID`, `Text`) VALUES
  (1, 'Звоню'),
  (4, 'Здравствуйте. \r\nПоговорим о Навальном?'),
  (5, 'Почему Вы так думаете?'),
  (6, 'До свидания'),
  (11, 'вы уверены?'),
  (21, 'Как дела вообще?'),
  (20, 'Круто!'),
  (19, 'Ок, продолжим');
/*!40000 ALTER TABLE `script` ENABLE KEYS */;

-- Dumping structure for table navalny-call.script_buttons
CREATE TABLE IF NOT EXISTS `script_buttons` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ScriptID` int(11) NOT NULL,
  `Text` varchar(100) NOT NULL,
  `ToScriptID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=46 DEFAULT CHARSET=utf8;

-- Dumping data for table navalny-call.script_buttons: 15 rows
/*!40000 ALTER TABLE `script_buttons` DISABLE KEYS */;
INSERT INTO `script_buttons` (`ID`, `ScriptID`, `Text`, `ToScriptID`) VALUES
  (1, 1, 'Ответил', 4),
  (2, 1, 'Нет, ответил', 0),
  (3, 4, 'Да, конечно', 20),
  (4, 4, 'Нет, ни за что', 11),
  (5, 4, 'Вы что? Он же спонсируется госдепом', 5),
  (6, 5, 'Друзья говорят', 0),
  (7, 5, 'Просто знаю', 0),
  (8, 0, 'Инициализация звонка', 0),
  (9, 0, 'Завершение звонка', 0),
  (42, 19, 'Ну давайте', 0),
  (44, 20, 'Ага', 21),
  (41, 19, 'Хотя нет, до свидания', 0),
  (40, 11, 'Нет', 19),
  (39, 11, 'Да', 0),
  (45, 21, 'До свидания', 0);
/*!40000 ALTER TABLE `script_buttons` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
