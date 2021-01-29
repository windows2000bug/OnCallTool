-- MySQL dump 10.14  Distrib 5.5.68-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: oncall
-- ------------------------------------------------------
-- Server version	5.5.68-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Apps`
--

DROP TABLE IF EXISTS `Apps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Apps` (
  `app_name` varchar(50) DEFAULT NULL,
  `a_tid` int(11) DEFAULT NULL,
  `crit` int(11) DEFAULT NULL,
  `app_id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`app_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Apps`
--

LOCK TABLES `Apps` WRITE;
/*!40000 ALTER TABLE `Apps` DISABLE KEYS */;
INSERT INTO `Apps` VALUES ('Apache HTTPD',92,1,8),('Apache HTTPD',1,1,9),('IIS',2,1,10);
/*!40000 ALTER TABLE `Apps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Cal`
--

DROP TABLE IF EXISTS `Cal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Cal` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `c_uid` int(9) DEFAULT NULL,
  `c_tid` int(9) DEFAULT NULL,
  `date_start` datetime DEFAULT NULL,
  `date_end` datetime DEFAULT NULL,
  `prim` int(2) DEFAULT NULL,
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB AUTO_INCREMENT=2334 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Cal`
--

LOCK TABLES `Cal` WRITE;
/*!40000 ALTER TABLE `Cal` DISABLE KEYS */;
INSERT INTO `Cal` VALUES (2328,2,1,'2021-01-25 11:16:00','2021-02-01 11:16:00',1),(2329,1,2,'2021-01-25 11:18:00','2021-02-01 11:18:00',1),(2330,3,1,'2021-01-25 00:00:00','2021-02-01 11:23:00',0),(2331,4,2,'2021-01-25 11:24:00','2021-02-01 11:24:00',0),(2332,3,1,'2021-02-01 11:24:00','2021-02-08 11:24:00',1),(2333,2,1,'2021-02-01 11:25:00','2021-02-08 11:25:00',0);
/*!40000 ALTER TABLE `Cal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Teams`
--

DROP TABLE IF EXISTS `Teams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Teams` (
  `tid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `team_name` char(50) DEFAULT NULL,
  `parent` int(9) DEFAULT NULL,
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Teams`
--

LOCK TABLES `Teams` WRITE;
/*!40000 ALTER TABLE `Teams` DISABLE KEYS */;
INSERT INTO `Teams` VALUES (1,'Linux Team',0),(2,'Windows Team',0);
/*!40000 ALTER TABLE `Teams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Users`
--

DROP TABLE IF EXISTS `Users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Users` (
  `uid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(50) DEFAULT NULL,
  `phone1` char(50) DEFAULT NULL,
  `phone2` char(50) DEFAULT NULL,
  `phone3` char(50) DEFAULT NULL,
  `u_tid` int(9) DEFAULT NULL,
  `u_order` int(9) DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Users`
--

LOCK TABLES `Users` WRITE;
/*!40000 ALTER TABLE `Users` DISABLE KEYS */;
INSERT INTO `Users` VALUES (1,'Jane Doe','555 555 2222','555 555 3333','555 555 4444',2,1),(2,'John Doe','555 555 1222','555 555 1223','555 555 1224',1,1),(3,'Jack Doe','555 555 1112','555 555 1113','555 555 1114',1,2),(4,'Jeff Doe','555 555 2233','555 555 2234','555 555 2235',2,2);
/*!40000 ALTER TABLE `Users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-01-28 11:32:15
