-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: localhost    Database: db_ecommerce
-- ------------------------------------------------------
-- Server version	5.7.19

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
-- Table structure for table `tb_addresses`
--

DROP TABLE IF EXISTS `tb_addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_addresses` (
  `idaddress` int(11) NOT NULL AUTO_INCREMENT,
  `idperson` int(11) NOT NULL,
  `desaddress` varchar(128) NOT NULL,
  `desnumber` varchar(16) NOT NULL,
  `descomplement` varchar(32) DEFAULT NULL,
  `descity` varchar(32) NOT NULL,
  `desstate` varchar(32) NOT NULL,
  `descountry` varchar(32) NOT NULL,
  `deszipcode` char(8) NOT NULL,
  `desdistrict` varchar(32) NOT NULL,
  `dtregister` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idaddress`),
  KEY `fk_addresses_persons_idx` (`idperson`),
  CONSTRAINT `fk_addresses_persons` FOREIGN KEY (`idperson`) REFERENCES `tb_persons` (`idperson`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_addresses`
--

LOCK TABLES `tb_addresses` WRITE;
/*!40000 ALTER TABLE `tb_addresses` DISABLE KEYS */;
INSERT INTO `tb_addresses` VALUES (1,32,'Rua Professor Amicis Brandi Bertolotti','100','','São Paulo','SP','Brasil','04953070','Vila Gilda','2018-07-26 23:28:53'),(2,32,'Rua Professor Amicis Brandi Bertolotti','100','','São Paulo','SP','Brasil','04953070','Vila Gilda','2018-07-27 00:18:18'),(3,32,'Rua Professor Amicis Brandi Bertolotti','100','','São Paulo','SP','Brasil','04953070','Vila Gilda','2018-07-27 00:32:36'),(4,32,'Rua Professor Amicis Brandi Bertolotti','100','','São Paulo','SP','Brasil','04953070','Vila Gilda','2018-07-27 00:41:48'),(5,32,'Rua Fantina Lobo Moreira de Campos','50','','São Paulo','SP','Brasil','05353070','Cidade São Francisco','2018-07-27 00:43:59');
/*!40000 ALTER TABLE `tb_addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_carts`
--

DROP TABLE IF EXISTS `tb_carts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_carts` (
  `idcart` int(11) NOT NULL AUTO_INCREMENT,
  `dessessionid` varchar(64) NOT NULL,
  `iduser` int(11) DEFAULT NULL,
  `deszipcode` char(8) DEFAULT NULL,
  `vlfreight` decimal(10,2) DEFAULT NULL,
  `nrdays` int(11) DEFAULT NULL,
  `dtregister` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idcart`),
  KEY `FK_carts_users_idx` (`iduser`),
  CONSTRAINT `fk_carts_users` FOREIGN KEY (`iduser`) REFERENCES `tb_users` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_carts`
--

LOCK TABLES `tb_carts` WRITE;
/*!40000 ALTER TABLE `tb_carts` DISABLE KEYS */;
INSERT INTO `tb_carts` VALUES (1,'0ptalo3rt0leph6iimhluet0sp',NULL,NULL,NULL,NULL,'2018-07-10 12:37:30'),(2,'fqflunpfvaiktepbg9r7bqoj1a',NULL,NULL,NULL,NULL,'2018-07-11 23:21:29'),(3,'t76oqal3kdo1pmf7d7gd2g3h0i',NULL,NULL,NULL,NULL,'2018-07-12 21:35:56'),(4,'47dsejflk8ec90idjgfvb7fgph',NULL,NULL,NULL,NULL,'2018-07-13 13:57:49'),(5,'opclpf33vpakrv45cba8ct72pa',NULL,'04953070',56.34,1,'2018-07-14 15:27:21'),(6,'ogjoulh3lb09vq4cei7c0b09ai',NULL,'04953070',57.71,1,'2018-07-16 11:54:19'),(7,'rmblee5poohnvoqu0ckatrspg9',NULL,'04953070',68.19,1,'2018-07-16 20:32:08'),(9,'ma769vujbiujajv06r49ltis8b',NULL,NULL,NULL,NULL,'2018-07-17 23:43:29'),(11,'kpe4pdfdehum9m5ircljujjv15',NULL,'04953070',47.51,1,'2018-07-18 21:05:43'),(12,'n1r4ps35hoeuj1esburg1b6vp9',26,'04953070',85.22,1,'2018-07-19 15:24:56'),(13,'qprbavegltcidggfqj0cl158po',23,'04953070',47.51,1,'2018-07-20 15:30:28'),(14,'p9f1uas2efluihg5t4jb7bh8sa',23,'04953070',68.19,1,'2018-07-23 13:00:24'),(15,'20vdkci1u43hqouiuu3p51qqts',23,'04953070',68.19,1,'2018-07-24 13:02:55'),(16,'9c4lh69j09agdrb6r83027kgqo',NULL,NULL,NULL,NULL,'2018-07-25 13:34:38'),(17,'tojvdimu8ppmafef1og9tg2cmd',23,'04953070',45.05,1,'2018-07-25 20:18:52'),(18,'gooqh5nb4uo12cj7u27q1cpnt9',23,'04953070',47.51,1,'2018-07-26 17:09:55'),(19,'64hcdu7l0bahtvlkmfdn3vmfl7',NULL,'05353070',69.10,1,'2018-07-26 19:38:06'),(20,'dnjar17hphjjf7v5aotumq7dpe',NULL,NULL,NULL,NULL,'2018-07-27 22:14:31'),(21,'m4k7qmrj1863euu8lppemtsg09',NULL,NULL,NULL,NULL,'2018-07-28 17:20:36');
/*!40000 ALTER TABLE `tb_carts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_cartsproducts`
--

DROP TABLE IF EXISTS `tb_cartsproducts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_cartsproducts` (
  `idcartproduct` int(11) NOT NULL AUTO_INCREMENT,
  `idcart` int(11) NOT NULL,
  `idproduct` int(11) NOT NULL,
  `dtremoved` datetime DEFAULT NULL,
  `dtregister` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idcartproduct`),
  KEY `FK_cartsproducts_carts_idx` (`idcart`),
  KEY `FK_cartsproducts_products_idx` (`idproduct`),
  CONSTRAINT `fk_cartsproducts_carts` FOREIGN KEY (`idcart`) REFERENCES `tb_carts` (`idcart`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_cartsproducts_products` FOREIGN KEY (`idproduct`) REFERENCES `tb_products` (`idproduct`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_cartsproducts`
--

LOCK TABLES `tb_cartsproducts` WRITE;
/*!40000 ALTER TABLE `tb_cartsproducts` DISABLE KEYS */;
INSERT INTO `tb_cartsproducts` VALUES (1,3,7,'2018-07-12 19:18:35','2018-07-12 22:16:12'),(2,3,7,'2018-07-12 19:18:36','2018-07-12 22:16:17'),(3,3,7,'2018-07-12 19:18:36','2018-07-12 22:16:19'),(4,3,7,'2018-07-12 19:18:37','2018-07-12 22:16:31'),(5,3,7,'2018-07-12 19:18:39','2018-07-12 22:16:56'),(6,3,9,'2018-07-12 19:18:40','2018-07-12 22:17:18'),(7,3,9,'2018-07-12 19:18:40','2018-07-12 22:17:37'),(8,3,9,'2018-07-12 19:18:40','2018-07-12 22:17:39'),(9,3,8,'2018-07-12 19:48:50','2018-07-12 22:38:59'),(10,3,8,'2018-07-12 19:48:51','2018-07-12 22:38:59'),(11,3,8,'2018-07-12 19:48:51','2018-07-12 22:38:59'),(12,3,8,'2018-07-12 19:48:52','2018-07-12 22:46:31'),(13,3,8,'2018-07-12 19:48:53','2018-07-12 22:46:31'),(14,3,8,'2018-07-12 19:49:14','2018-07-12 22:46:31'),(15,3,8,'2018-07-12 19:49:14','2018-07-12 22:49:11'),(16,3,8,'2018-07-12 19:49:18','2018-07-12 22:49:12'),(17,3,8,NULL,'2018-07-12 22:49:16'),(18,3,8,NULL,'2018-07-12 23:16:21'),(19,3,9,NULL,'2018-07-12 23:16:26'),(20,4,9,'2018-07-13 12:06:12','2018-07-13 15:06:07'),(21,4,9,NULL,'2018-07-13 15:06:10'),(22,5,8,'2018-07-14 13:07:38','2018-07-14 15:27:28'),(23,5,8,'2018-07-14 13:07:40','2018-07-14 16:07:35'),(24,5,8,'2018-07-14 13:07:41','2018-07-14 16:07:37'),(25,5,9,'2018-07-14 13:20:02','2018-07-14 16:07:49'),(26,5,9,'2018-07-14 13:20:03','2018-07-14 16:07:50'),(27,5,9,'2018-07-14 13:20:04','2018-07-14 16:20:00'),(28,5,9,NULL,'2018-07-14 16:20:01'),(29,5,10,NULL,'2018-07-14 16:25:35'),(30,6,9,'2018-07-16 09:03:14','2018-07-16 11:54:46'),(31,6,9,'2018-07-16 09:03:33','2018-07-16 11:54:46'),(32,6,9,'2018-07-16 09:03:41','2018-07-16 12:03:29'),(33,6,9,'2018-07-16 09:03:43','2018-07-16 12:03:35'),(34,6,9,'2018-07-16 09:03:43','2018-07-16 12:03:36'),(35,6,9,'2018-07-16 09:03:44','2018-07-16 12:03:37'),(36,6,9,'2018-07-16 09:03:44','2018-07-16 12:03:38'),(37,6,9,'2018-07-16 09:03:44','2018-07-16 12:03:39'),(38,6,9,'2018-07-16 09:03:45','2018-07-16 12:03:39'),(39,6,9,'2018-07-16 09:03:45','2018-07-16 12:03:40'),(40,6,11,'2018-07-16 09:20:23','2018-07-16 12:04:13'),(41,6,11,'2018-07-16 09:20:36','2018-07-16 12:04:16'),(42,6,11,'2018-07-16 09:23:33','2018-07-16 12:21:40'),(43,6,11,'2018-07-16 09:24:01','2018-07-16 12:22:05'),(44,6,11,'2018-07-16 09:26:10','2018-07-16 12:22:35'),(45,6,11,'2018-07-16 09:26:10','2018-07-16 12:24:39'),(46,6,11,'2018-07-16 09:26:10','2018-07-16 12:24:55'),(47,6,9,'2018-07-16 09:27:36','2018-07-16 12:26:38'),(48,6,9,'2018-07-16 09:27:40','2018-07-16 12:27:15'),(49,6,9,'2018-07-16 09:27:51','2018-07-16 12:27:23'),(50,6,9,'2018-07-16 09:33:24','2018-07-16 12:27:29'),(51,6,9,'2018-07-16 10:00:30','2018-07-16 12:33:14'),(52,6,9,NULL,'2018-07-16 12:48:14'),(53,6,11,NULL,'2018-07-16 14:18:26'),(54,7,9,NULL,'2018-07-16 20:33:09'),(55,7,9,NULL,'2018-07-16 20:39:39'),(60,11,9,NULL,'2018-07-18 21:09:07'),(61,12,11,'2018-07-19 12:53:43','2018-07-19 15:42:29'),(62,12,9,NULL,'2018-07-19 15:53:40'),(63,12,9,NULL,'2018-07-19 19:48:31'),(64,12,7,NULL,'2018-07-19 19:51:57'),(65,13,9,NULL,'2018-07-20 16:12:27'),(66,14,9,NULL,'2018-07-23 13:00:34'),(67,14,9,NULL,'2018-07-23 13:00:36'),(68,15,9,'2018-07-24 10:33:31','2018-07-24 13:03:29'),(69,15,9,NULL,'2018-07-24 13:03:29'),(70,15,9,NULL,'2018-07-24 13:33:24'),(71,17,7,NULL,'2018-07-25 20:19:02'),(72,18,9,NULL,'2018-07-26 17:10:04'),(73,19,9,NULL,'2018-07-26 23:28:35'),(74,19,11,NULL,'2018-07-27 00:40:24'),(75,19,11,NULL,'2018-07-27 00:43:25');
/*!40000 ALTER TABLE `tb_cartsproducts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_categories`
--

DROP TABLE IF EXISTS `tb_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_categories` (
  `idcategory` int(11) NOT NULL AUTO_INCREMENT,
  `descategory` varchar(32) NOT NULL,
  `dtregister` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idcategory`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_categories`
--

LOCK TABLES `tb_categories` WRITE;
/*!40000 ALTER TABLE `tb_categories` DISABLE KEYS */;
INSERT INTO `tb_categories` VALUES (11,'Apple','2018-06-20 14:33:42'),(12,'Samsung','2018-06-20 14:33:46'),(13,'Android','2018-06-20 14:33:51'),(14,'Motorola','2018-06-20 14:33:59');
/*!40000 ALTER TABLE `tb_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_categoriesproducts`
--

DROP TABLE IF EXISTS `tb_categoriesproducts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_categoriesproducts` (
  `idcategory` int(11) NOT NULL,
  `idproduct` int(11) NOT NULL,
  PRIMARY KEY (`idcategory`,`idproduct`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_categoriesproducts`
--

LOCK TABLES `tb_categoriesproducts` WRITE;
/*!40000 ALTER TABLE `tb_categoriesproducts` DISABLE KEYS */;
INSERT INTO `tb_categoriesproducts` VALUES (13,7),(13,8),(13,9),(13,10),(13,11),(14,7),(14,8);
/*!40000 ALTER TABLE `tb_categoriesproducts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_orders`
--

DROP TABLE IF EXISTS `tb_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_orders` (
  `idorder` int(11) NOT NULL AUTO_INCREMENT,
  `idcart` int(11) NOT NULL,
  `iduser` int(11) NOT NULL,
  `idstatus` int(11) NOT NULL,
  `idaddress` int(11) NOT NULL,
  `vltotal` decimal(10,2) NOT NULL,
  `dtregister` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idorder`),
  KEY `FK_orders_users_idx` (`iduser`),
  KEY `fk_orders_ordersstatus_idx` (`idstatus`),
  KEY `fk_orders_carts_idx` (`idcart`),
  KEY `fk_orders_addresses_idx` (`idaddress`),
  CONSTRAINT `fk_orders_addresses` FOREIGN KEY (`idaddress`) REFERENCES `tb_addresses` (`idaddress`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_orders_carts` FOREIGN KEY (`idcart`) REFERENCES `tb_carts` (`idcart`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_orders_ordersstatus` FOREIGN KEY (`idstatus`) REFERENCES `tb_ordersstatus` (`idstatus`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_orders_users` FOREIGN KEY (`iduser`) REFERENCES `tb_users` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_orders`
--

LOCK TABLES `tb_orders` WRITE;
/*!40000 ALTER TABLE `tb_orders` DISABLE KEYS */;
INSERT INTO `tb_orders` VALUES (8,15,23,3,24,2666.19,'2018-07-24 13:03:48'),(9,17,23,1,25,1180.28,'2018-07-25 20:19:16'),(10,18,23,1,26,1346.51,'2018-07-26 17:10:28'),(11,19,28,3,1,1346.51,'2018-07-26 23:28:53'),(12,19,28,1,2,1346.51,'2018-07-27 00:18:18'),(13,19,28,1,3,1346.51,'2018-07-27 00:32:36'),(14,19,28,1,4,2036.61,'2018-07-27 00:41:48'),(15,19,28,1,5,2727.90,'2018-07-27 00:43:59');
/*!40000 ALTER TABLE `tb_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_ordersstatus`
--

DROP TABLE IF EXISTS `tb_ordersstatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_ordersstatus` (
  `idstatus` int(11) NOT NULL AUTO_INCREMENT,
  `desstatus` varchar(32) NOT NULL,
  `dtregister` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idstatus`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_ordersstatus`
--

LOCK TABLES `tb_ordersstatus` WRITE;
/*!40000 ALTER TABLE `tb_ordersstatus` DISABLE KEYS */;
INSERT INTO `tb_ordersstatus` VALUES (1,'Em Aberto','2017-03-13 03:00:00'),(2,'Aguardando Pagamento','2017-03-13 03:00:00'),(3,'Pago','2017-03-13 03:00:00'),(4,'Entregue','2017-03-13 03:00:00');
/*!40000 ALTER TABLE `tb_ordersstatus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_persons`
--

DROP TABLE IF EXISTS `tb_persons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_persons` (
  `idperson` int(11) NOT NULL AUTO_INCREMENT,
  `desperson` varchar(64) NOT NULL,
  `desemail` varchar(128) DEFAULT NULL,
  `nrphone` bigint(20) DEFAULT NULL,
  `dtregister` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idperson`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_persons`
--

LOCK TABLES `tb_persons` WRITE;
/*!40000 ALTER TABLE `tb_persons` DISABLE KEYS */;
INSERT INTO `tb_persons` VALUES (20,'Merlin','merlinopecadodagula@gmail.com',44552255,'2018-07-17 14:05:02'),(21,'Merlin','merlinopecadodagula@gmail.com',44552255,'2018-07-17 14:07:29'),(22,'Merlin','merlinopecadodagula@gmail.com',44552255,'2018-07-17 14:09:08'),(24,'admin2','admin@gmail.com',454252514,'2018-07-17 14:22:58'),(27,'Merlin','merliopecadodagula@gmail.com',115895959,'2018-07-17 14:29:21'),(30,'Ban','banopecadodaganacia@gmail.com',1158955959,'2018-07-18 00:45:43'),(32,'Meliodas','meliodasopecadodaira@gmail.com',11981561992,'2018-07-26 20:23:24'),(33,'Diane','dianeopecadodainveja@inveja.com',95036800,'2018-07-28 01:13:03'),(35,'King','kingopecadodapreguica@gmail.com',1158955959,'2018-07-28 01:24:31'),(36,'Escanor','escanoropecadodoorgulho@gmail.com',1158955959,'2018-07-28 01:26:29');
/*!40000 ALTER TABLE `tb_persons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_products`
--

DROP TABLE IF EXISTS `tb_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_products` (
  `idproduct` int(11) NOT NULL AUTO_INCREMENT,
  `desproduct` varchar(64) NOT NULL,
  `vlprice` decimal(10,2) NOT NULL,
  `vlwidth` decimal(10,2) NOT NULL,
  `vlheight` decimal(10,2) NOT NULL,
  `vllength` decimal(10,2) NOT NULL,
  `vlweight` decimal(10,2) NOT NULL,
  `desurl` varchar(128) NOT NULL,
  `dtregister` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idproduct`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_products`
--

LOCK TABLES `tb_products` WRITE;
/*!40000 ALTER TABLE `tb_products` DISABLE KEYS */;
INSERT INTO `tb_products` VALUES (7,'Smartphone Motorola Moto G5 Plus',1135.23,15.20,7.40,0.70,0.16,'smartphone-motorola-moto-g5-plus','2018-06-22 22:38:04'),(8,'Smartphone Moto Z Play',1887.78,14.10,0.90,1.16,0.13,'smartphone-moto-z-play','2018-06-22 22:38:04'),(9,'Smartphone Samsung Galaxy J5 Pro',1299.00,14.60,7.10,0.80,0.16,'smartphone-samsung-galaxy-j5','2018-06-22 22:38:04'),(10,'Smartphone Samsung Galaxy J7 Prime',1149.00,15.10,7.50,0.80,0.16,'smartphone-samsung-galaxy-j7','2018-06-22 22:38:04'),(11,'Smartphone Samsung Galaxy J3 Dual',679.90,14.20,7.10,0.70,0.14,'smartphone-samsung-galaxy-j3','2018-06-22 22:38:04');
/*!40000 ALTER TABLE `tb_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_productscategories`
--

DROP TABLE IF EXISTS `tb_productscategories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_productscategories` (
  `idcategory` int(11) NOT NULL,
  `idproduct` int(11) NOT NULL,
  PRIMARY KEY (`idcategory`,`idproduct`),
  KEY `fk_productscategories_products_idx` (`idproduct`),
  CONSTRAINT `fk_productscategories_categories` FOREIGN KEY (`idcategory`) REFERENCES `tb_categories` (`idcategory`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_productscategories_products` FOREIGN KEY (`idproduct`) REFERENCES `tb_products` (`idproduct`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_productscategories`
--

LOCK TABLES `tb_productscategories` WRITE;
/*!40000 ALTER TABLE `tb_productscategories` DISABLE KEYS */;
INSERT INTO `tb_productscategories` VALUES (13,8),(13,9),(13,10),(13,11);
/*!40000 ALTER TABLE `tb_productscategories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_users`
--

DROP TABLE IF EXISTS `tb_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_users` (
  `iduser` int(11) NOT NULL AUTO_INCREMENT,
  `idperson` int(11) NOT NULL,
  `deslogin` varchar(64) NOT NULL,
  `despassword` varchar(256) NOT NULL,
  `inadmin` tinyint(4) NOT NULL DEFAULT '0',
  `dtregister` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`iduser`),
  KEY `FK_users_persons_idx` (`idperson`),
  CONSTRAINT `fk_users_persons` FOREIGN KEY (`idperson`) REFERENCES `tb_persons` (`idperson`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_users`
--

LOCK TABLES `tb_users` WRITE;
/*!40000 ALTER TABLE `tb_users` DISABLE KEYS */;
INSERT INTO `tb_users` VALUES (23,27,'merlin','$2y$10$SXaCp.UhD9ExGOBXXQGn9uPuzfwGESAEx8OfLXEvtlA0s1Z4A4YBO',1,'2018-07-17 14:29:21'),(26,30,'ban','$2y$10$1ZXWTgfO.0b3mQ.p8.Nq2..20K/1NjqdI/t6/lkdHv4EaYP4yUmi2',0,'2018-07-18 00:45:44'),(28,32,'meliodas','$2y$10$HT2LDb/YT/8PvXJb9jkJqun86rSnm7yZAxN6GAjqFSuisJJFqPbci',1,'2018-07-26 20:23:24'),(29,33,'diane','$2y$10$o1NamcQyl3zLsZbrE/NAnOx/XmAobaAQcFf9Y/zOlWOJ5QnTFqQSq',1,'2018-07-28 01:13:03'),(31,35,'king','$2y$10$IxMELcRiGctqWo4zH0f.ge6/riMfuWZ15BS6z5.vvBt60uD6bdNSq',1,'2018-07-28 01:24:31'),(32,36,'Escanor','$2y$10$JjwCUIIjWG64yvVlB64v0OrlNLVv/XafF669GA7OM5CYRCQssfYL.',1,'2018-07-28 01:26:29');
/*!40000 ALTER TABLE `tb_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_userslogs`
--

DROP TABLE IF EXISTS `tb_userslogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_userslogs` (
  `idlog` int(11) NOT NULL AUTO_INCREMENT,
  `iduser` int(11) NOT NULL,
  `deslog` varchar(128) NOT NULL,
  `desip` varchar(45) NOT NULL,
  `desuseragent` varchar(128) NOT NULL,
  `dessessionid` varchar(64) NOT NULL,
  `desurl` varchar(128) NOT NULL,
  `dtregister` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idlog`),
  KEY `fk_userslogs_users_idx` (`iduser`),
  CONSTRAINT `fk_userslogs_users` FOREIGN KEY (`iduser`) REFERENCES `tb_users` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_userslogs`
--

LOCK TABLES `tb_userslogs` WRITE;
/*!40000 ALTER TABLE `tb_userslogs` DISABLE KEYS */;
/*!40000 ALTER TABLE `tb_userslogs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_userspasswordsrecoveries`
--

DROP TABLE IF EXISTS `tb_userspasswordsrecoveries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_userspasswordsrecoveries` (
  `idrecovery` int(11) NOT NULL AUTO_INCREMENT,
  `iduser` int(11) NOT NULL,
  `desip` varchar(45) NOT NULL,
  `dtrecovery` datetime DEFAULT NULL,
  `dtregister` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idrecovery`),
  KEY `fk_userspasswordsrecoveries_users_idx` (`iduser`),
  CONSTRAINT `fk_userspasswordsrecoveries_users` FOREIGN KEY (`iduser`) REFERENCES `tb_users` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_userspasswordsrecoveries`
--

LOCK TABLES `tb_userspasswordsrecoveries` WRITE;
/*!40000 ALTER TABLE `tb_userspasswordsrecoveries` DISABLE KEYS */;
/*!40000 ALTER TABLE `tb_userspasswordsrecoveries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'db_ecommerce'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-07-28 18:28:43
