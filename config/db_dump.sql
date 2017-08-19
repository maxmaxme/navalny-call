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


# Дамп таблицы city_codes
# ------------------------------------------------------------

CREATE TABLE `city_codes` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `city_codes` WRITE;
/*!40000 ALTER TABLE `city_codes` DISABLE KEYS */;

INSERT INTO `city_codes` (`ID`, `Code`)
VALUES
  (1,495),
  (2,499),
  (3,812);

/*!40000 ALTER TABLE `city_codes` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы log
# ------------------------------------------------------------

CREATE TABLE `log` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ButtonID` int(11) NOT NULL,
  `PhoneID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `DateTime` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Дамп таблицы phones
# ------------------------------------------------------------

CREATE TABLE `phones` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Phone` bigint(20) NOT NULL,
  `UserID` int(11) NOT NULL,
  `StatusID` int(11) NOT NULL COMMENT '0 — идёт разговор, 1 — завершён',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Дамп таблицы script
# ------------------------------------------------------------

CREATE TABLE `script` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Text` varchar(1000) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `script` WRITE;
/*!40000 ALTER TABLE `script` DISABLE KEYS */;

INSERT INTO `script` (`ID`, `Text`)
VALUES
  (1,'Звоню!'),
  (4,'Здравствуйте. Меня зовут %n%.\nПоговорим о Навальном?'),
  (5,'Почему Вы так думаете?'),
  (6,'До свидания'),
  (11,'вы уверены?'),
  (21,'Как дела вообще?'),
  (20,'Круто!'),
  (19,'Ок, продолжим');

/*!40000 ALTER TABLE `script` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы script_buttons
# ------------------------------------------------------------

CREATE TABLE `script_buttons` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ScriptID` int(11) NOT NULL,
  `Text` varchar(100) NOT NULL,
  `ToScriptID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `script_buttons` WRITE;
/*!40000 ALTER TABLE `script_buttons` DISABLE KEYS */;

INSERT INTO `script_buttons` (`ID`, `ScriptID`, `Text`, `ToScriptID`)
VALUES
  (1,1,'Ответил',4),
  (2,1,'Нет, ответил :(',0),
  (3,4,'Да, конечно',20),
  (4,4,'Нет, ни за что',11),
  (5,4,'Вы что? Он же спонсируется госдепом',5),
  (6,5,'Друзья говорят',0),
  (7,5,'Просто знаю',0),
  (8,0,'Инициализация звонка',0),
  (9,0,'Завершение звонка',0),
  (42,19,'Ну давайте',0),
  (44,20,'Ага',21),
  (41,19,'Хотя нет, до свидания',0),
  (40,11,'Нет',19),
  (39,11,'Да',0),
  (45,21,'До свидания',0);

/*!40000 ALTER TABLE `script_buttons` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы users
# ------------------------------------------------------------

CREATE TABLE `users` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Login` varchar(11) NOT NULL DEFAULT '',
  `Password` varchar(255) NOT NULL DEFAULT '',
  `GroupID` int(11) unsigned NOT NULL,
  `Hash` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`ID`, `Login`, `Password`, `GroupID`, `Hash`)
VALUES
  (1,'admin','',2,''),
  (2,'vasya','',1,''),
  (3,'test','$2y$10$nq79Aa8k2xiLD81BKIQeVe413AzFqO3HAOX82EWfFGtdMkNpi8XiC',3,'');

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы users_groups
# ------------------------------------------------------------

CREATE TABLE `users_groups` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(11) NOT NULL DEFAULT '',
  `CanCall` tinyint(1) unsigned NOT NULL,
  `CanViewTree` tinyint(1) unsigned NOT NULL,
  `CanEditTree` tinyint(1) unsigned NOT NULL,
  `CanViewLog` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `users_groups` WRITE;
/*!40000 ALTER TABLE `users_groups` DISABLE KEYS */;

INSERT INTO `users_groups` (`ID`, `Name`, `CanCall`, `CanViewTree`, `CanEditTree`, `CanViewLog`)
VALUES
  (1,'Волонтёр',1,0,0,0),
  (2,'Админ',1,1,0,1),
  (3,'Test',1,1,0,1);

/*!40000 ALTER TABLE `users_groups` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
