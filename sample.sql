-- MySQL dump 10.13  Distrib 8.0.16, for Win64 (x86_64)
--
-- Host: localhost    Database: apollio
-- ------------------------------------------------------
-- Server version	8.0.16

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
 SET NAMES utf8 ;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Dumping data for table `artwork`
--

LOCK TABLES `artwork` WRITE;
/*!40000 ALTER TABLE `artwork` DISABLE KEYS */;
INSERT INTO `artwork` VALUES (1,1,'Owl','bc4df68e480a46076ec161d5ce7643cd.jpg','2019-06-23 17:36:45'),(2,1,'Owl','e5234dd652f01e5400405776818abdb9.jpg','2019-06-23 17:37:03'),(3,1,'Dog','61c1cfb6d1777ae9823ebbe1b10b211d.jpg','2019-06-23 17:37:27'),(4,1,'Goat','2dabffb28435c9c858264654fa114e79.jpg','2019-06-23 17:37:48'),(5,1,'Elk','16cf97f221994ec34a2335fbe958513b.jpg','2019-06-23 17:38:05'),(6,1,'Tiger','3c9183e53cef9ff20e4f2a07babbd4f6.jpg','2019-06-23 17:38:17'),(7,1,'Cat','b7ef26827506bc433ffa722e53b0a869.jpg','2019-06-23 17:39:14'),(8,1,'Cat 2','0cfa2f792cc93dbbf2f1bf8d6cf1ea9e.jpg','2019-06-23 17:39:32'),(9,1,'Falcon','c88adb83754e872b1241e81ade267471.jpg','2019-06-23 17:39:49'),(10,1,'Dog','1f76ddb9fef9fe0a12b81a9d43747bd8.jpg','2019-06-23 17:40:01'),(11,2,'Dog','41adbc834739e35e77d5e26e2e13d223.jpg','2019-06-23 17:40:32'),(12,2,'Cat','f5891014de83add1cddc791b5da0f8ff.jpg','2019-06-23 17:40:44'),(13,2,'Dog 2','7e867edb5345a40f0d71365e877b5dc8.jpg','2019-06-23 17:40:52'),(14,3,'Cat','570657744169521dc3ca69b3f55da3a9.jpg','2019-06-23 17:41:39'),(15,3,'Lion','da2abf02a045a276ed3d8507d15100a6.jpg','2019-06-23 17:42:01'),(16,1,'Deer','fc8c3a24ba857d727d6d1a43f64570d8.jpg','2019-06-23 17:42:58'),(17,1,'Snowy owl','4a3ebf26eff12f4f346e0111bcfc737f.jpg','2019-06-23 17:43:04'),(18,1,'red robin','2af4ba131ce40e2be5c104cca0bd40dc.jpg','2019-06-23 17:43:13'),(19,1,'Bird!','708bf76b252c16092571d749a3338bc7.jpg','2019-06-23 17:43:19'),(20,1,'Fox','2cb7cc3558e285185d88ad5a5d2a10af.jpg','2019-06-23 17:44:05');
/*!40000 ALTER TABLE `artwork` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'tiberius@example.com','a:0:{}','$2y$13$oD8m62N1WnKb.88jdWdlouxeD6aPq32tr2/syzB3AniIodWu/AVHO','tiberius'),(2,'picard@example.com','a:0:{}','$2y$13$ZOAT180N1mmV5f7uOHqJSu/g4yPMFIrL5Vx852ptGldCJGl5LfUsW','picard'),(3,'burnham@example.com','a:0:{}','$2y$13$Jg2biNEkBiYiBilppkCDyu/cOR.MZyTXe7iSpZSOlqriSq50yOoVm','burnham');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `user_user`
--

LOCK TABLES `user_user` WRITE;
/*!40000 ALTER TABLE `user_user` DISABLE KEYS */;
INSERT INTO `user_user` VALUES (1,2),(2,1),(3,1),(3,2);
/*!40000 ALTER TABLE `user_user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-06-23 19:48:20
