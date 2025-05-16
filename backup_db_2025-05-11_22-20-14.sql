/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.5.28-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: fdb1030.awardspace.net    Database: 4630827_distribuidoral
-- ------------------------------------------------------
-- Server version	8.0.32

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `carrito`
--

DROP TABLE IF EXISTS `carrito`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `carrito` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int DEFAULT NULL,
  `id_producto` int DEFAULT NULL,
  `cantidad` int DEFAULT NULL,
  `fecha_agregado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estado` varchar(20) COLLATE utf8mb4_general_ci DEFAULT 'pendiente',
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_producto` (`id_producto`),
  CONSTRAINT `carrito_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `carrito_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carrito`
--

LOCK TABLES `carrito` WRITE;
/*!40000 ALTER TABLE `carrito` DISABLE KEYS */;
INSERT INTO `carrito` VALUES (9,4,29,3,'2025-05-06 03:02:40','pendiente'),(10,NULL,28,1,'2025-05-06 03:19:09','pendiente'),(11,1,28,12,'2025-05-06 04:52:46','pendiente'),(12,1,29,1,'2025-05-06 05:00:19','pendiente');
/*!40000 ALTER TABLE `carrito` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_venta`
--

DROP TABLE IF EXISTS `detalle_venta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_venta` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_venta` int NOT NULL,
  `id_producto` int NOT NULL,
  `cantidad` int NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_venta` (`id_venta`),
  KEY `id_producto` (`id_producto`),
  CONSTRAINT `detalle_venta_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id`),
  CONSTRAINT `detalle_venta_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_venta`
--

LOCK TABLES `detalle_venta` WRITE;
/*!40000 ALTER TABLE `detalle_venta` DISABLE KEYS */;
INSERT INTO `detalle_venta` VALUES (1,1,3,5,3000.00),(2,1,28,1,50000.00),(3,2,28,3,50000.00),(4,3,2,1,1000.00),(5,4,2,1,1000.00),(6,5,2,1,1000.00),(7,6,2,1,1000.00),(8,7,30,2,2500.00),(9,8,29,3,500.00),(10,9,3,2,3000.00),(11,10,28,1,50000.00),(12,11,32,1,4800.00),(13,12,30,1,2500.00),(14,13,3,1,3000.00),(15,13,33,1,7600.00),(16,14,60,2,1800.00),(17,15,28,1,50000.00),(18,16,33,1,7600.00),(19,17,3,1,3000.00),(20,18,32,1,4800.00),(21,18,29,1,500.00),(22,18,30,1,2500.00),(23,18,3,1,3000.00),(24,19,28,5,50000.00),(25,19,3,4,3000.00),(26,19,29,4,500.00),(27,19,2,1,1000.00),(28,20,30,1,2500.00),(29,20,31,1,5200.00),(30,21,31,1,5200.00),(31,21,61,1,1400.00);
/*!40000 ALTER TABLE `detalle_venta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pedido_items`
--

DROP TABLE IF EXISTS `pedido_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedido_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_pedido` int NOT NULL,
  `id_producto` int NOT NULL,
  `cantidad` int NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_pedido` (`id_pedido`),
  KEY `id_producto` (`id_producto`),
  CONSTRAINT `pedido_items_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id`),
  CONSTRAINT `pedido_items_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedido_items`
--

LOCK TABLES `pedido_items` WRITE;
/*!40000 ALTER TABLE `pedido_items` DISABLE KEYS */;
INSERT INTO `pedido_items` VALUES (1,1,28,1,50000.00);
/*!40000 ALTER TABLE `pedido_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pedidos`
--

DROP TABLE IF EXISTS `pedidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedidos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedidos`
--

LOCK TABLES `pedidos` WRITE;
/*!40000 ALTER TABLE `pedidos` DISABLE KEYS */;
INSERT INTO `pedidos` VALUES (1,9,50000.00,'2025-05-05 19:30:50');
/*!40000 ALTER TABLE `pedidos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `existencia` int NOT NULL,
  `urlImagen` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `categoria` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=130 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (2,'mesedora',1000.00,-1,'https://www.ventamueblesvintage.com/wp-content/uploads/2021/02/NELO-scaled.jpg',NULL),(3,'Cama',3000.00,-4,'https://www.ilumeloutlet.com/cdn/shop/files/EMELYTN003FULL_600x.jpg?v=1714660536',NULL),(28,'Cama Queen size pillowtop',50000.00,-3,'../uploads/1746416412_pillowtop.jfif','mobiliaria'),(29,'set de cubiertos de acero inoxidable',500.00,-3,'https://supplyrd.com/cdn/shop/products/image_7366ef4e-4378-4c52-99c5-f0252178b46b.jpg?v=1592137083','vajilla'),(30,'Silla de madera',2500.00,10,'https://images.unsplash.com/photo-1519710164239-da123dc03ef4','mobiliaria'),(31,'Mesa rústica',5200.00,6,'https://images.unsplash.com/photo-1540575467063-178a50c2df87','mobiliaria'),(32,'Estante de roble',4800.00,10,'https://m.media-amazon.com/images/I/61TY8sFxXZL._AC_UF894,1000_QL80_.jpg','mobiliaria'),(33,'Armario de pino',7600.00,3,'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTUGf0tmOQkgMjnkzrP3ORIuPuygVMf4uLVyQ&s','mobiliaria'),(34,'Banco de exterior',3200.00,20,'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTU6wk0C8JzeP0E61XLja_b95bCVzwqe49lkg&s','mobiliaria'),(35,'Cama matrimonial',10500.00,3,'https://m.media-amazon.com/images/I/71Xw-7MXTJL._AC_UF894,1000_QL80_.jpg','mobiliaria'),(36,'Cómoda vintage',6100.00,6,'https://dcdn-us.mitiendanube.com/stores/102/273/products/whatsapp-image-2022-11-28-at-15-29-561-15f1f47aa2945d3f7f16696602977050-640-0.jpeg','mobiliaria'),(37,'Escritorio de oficina',8700.00,4,'https://img.uline.com/is/image/uline/HD_3572_txt_MXSpa?$Mobile_Zoom$','mobiliaria'),(38,'Taburete alto',1800.00,25,'https://images.unsplash.com/photo-1512290923902-8a9f81dc236c','mobiliaria'),(39,'Sofá de lino',9200.00,2,'https://images.unsplash.com/photo-1587293852720-3a0b9c073ab3','mobiliaria'),(40,'Lámpara de pie',2200.00,18,'https://images.unsplash.com/photo-1493666438817-866a91353ca9','decoraciones'),(41,'Cuadro abstracto',3500.00,7,'https://artquid.twic.pics/art/5/21/333056.684531894.1.o561394734.jpg?twic=v1/cover=575/background=white','decoraciones'),(42,'Espejo con marco',4200.00,9,'https://m.media-amazon.com/images/I/71SQsVDC9VL._AC_UF894,1000_QL80_.jpg','decoraciones'),(43,'Jarrón cerámico',1500.00,30,'https://images.unsplash.com/photo-1501004318641-b39e6451bec6','decoraciones'),(44,'Cojines decorativos',800.00,40,'https://images.unsplash.com/photo-1519710164239-da123dc03ef4?ixid=decor','decoraciones'),(45,'Alfombra persa',6500.00,3,'https://puntacanaparty.com/wp-content/uploads/2023/08/Alfombra-persa-roja-6-x-4.png','decoraciones'),(46,'Portavelas de cristal',950.00,22,'https://images.unsplash.com/photo-1505691723518-36a0f3a0a438?ixid=dec3','decoraciones'),(47,'Reloj de pared vintage',2800.00,11,'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?ixid=dec4','decoraciones'),(48,'Planta artificial',1200.00,28,'https://images.unsplash.com/photo-1501004318641-b39e6451bec6?ixid=dec5','decoraciones'),(49,'Móvil de techo',3300.00,14,'https://images.unsplash.com/photo-1519710164239-da123dc03ef4?ixid=dec6','decoraciones'),(50,'Juego de platos cerámica',2400.00,25,'https://images.unsplash.com/photo-1564758866814-6b6a2f0b4a8f','vajilla'),(51,'Vajilla de porcelana',7800.00,5,'https://images.unsplash.com/photo-1556911220-e15b29be8c1e','vajilla'),(52,'Copas de vino',3200.00,18,'https://casacuesta.com/media/catalog/product/cache/fde49a4ea9a339628caa0bc56aea00ff/3/1/3170932-1.jpg','vajilla'),(53,'Tazas de café',1500.00,30,'https://images.unsplash.com/photo-1523473827536-53e31e1f1e09','vajilla'),(54,'Bol de ensalada',900.00,40,'https://img.freepik.com/fotos-premium/ensalada-mixta-verduras-bol_488220-72575.jpg','vajilla'),(55,'Cubertería 24 piezas',4500.00,10,'https://m.media-amazon.com/images/I/717-pULNnJL.jpg','vajilla'),(56,'Fuente de servir',2800.00,12,'https://images.unsplash.com/photo-1542444459-db4f67f2d732','vajilla'),(57,'Set de salsas',1200.00,22,'https://images.unsplash.com/photo-1564758866814-6b6a2f0b4a8f?ixid=vaj8','vajilla'),(58,'Tetera de acero',2300.00,16,'https://images.unsplash.com/photo-1556911220-e15b29be8c1e?ixid=vaj9','vajilla'),(59,'Taladro inalámbrico',6200.00,7,'https://images.unsplash.com/photo-1581091870633-1167f2ab0c86','herramientas'),(60,'Juego de destornilladores',1800.00,18,'https://images.unsplash.com/photo-1556910123-8f26e5a5c0ed','herramientas'),(61,'Llave inglesa',1400.00,24,'https://images.unsplash.com/photo-1509475826633-fed577a2c71b','herramientas'),(62,'Cinta métrica',600.00,50,'https://innovacentro.com.do/product/image/medium/042122_0.jpg','herramientas'),(63,'Martillo de acero',1200.00,30,'https://images.unsplash.com/photo-1581091012184-6107c2ac0b50','herramientas'),(64,'Sierra manual',2200.00,18,'https://images.unsplash.com/photo-1576866209830-3f8d033bcf6f?ixid=her6','herramientas'),(65,'Alicate profesional',1600.00,22,'https://images.unsplash.com/photo-1509475826633-fed577a2c71b?ixid=her7','herramientas'),(66,'Nivel de burbuja',900.00,35,'https://images.unsplash.com/photo-1581091870633-1167f2ab0c86?ixid=her8','herramientas'),(67,'Juego de llaves Allen',700.00,40,'https://images.unsplash.com/photo-1556910123-8f26e5a5c0ed?ixid=her9','herramientas'),(68,'Taladro de banco',8200.00,4,'https://images.unsplash.com/photo-1581091012184-6107c2ac0b50?ixid=her10','herramientas'),(69,'Refrigerador 300L',24000.00,3,'https://images.unsplash.com/photo-1581579181357-8e8eb8b015d6','electrodomesticos'),(70,'Lavadora carga frontal',18100.00,5,'https://images.unsplash.com/photo-1578922867097-802fa54eae1d','electrodomesticos'),(71,'Microondas digital',7200.00,10,'https://images.unsplash.com/photo-1595637641100-74a1f5b1b6b3','electrodomesticos'),(72,'Televisor 50\"',28500.00,2,'https://images.unsplash.com/photo-1580910051071-0fdb3f17c6d9','electrodomesticos'),(73,'Aire acondicionado 12K',15400.00,4,'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSsj7ORb2QFUy248RU0BuTM2BaNhi_SZ3EUtw&s','electrodomesticos'),(74,'Horno eléctrico',6300.00,8,'https://images.unsplash.com/photo-1587393430948-3fcd89c84816','electrodomesticos'),(75,'Licuadora 1.5L',3100.00,12,'https://images.unsplash.com/photo-1580281658628-86fa08e828dd?ixid=ele6','electrodomesticos'),(76,'Aspiradora ciclónica',9200.00,6,'https://m.media-amazon.com/images/I/51WP8kuY1XL._AC_UF894,1000_QL80_.jpg','electrodomesticos'),(77,'Plancha de vapor',2100.00,20,'https://images.unsplash.com/photo-1595637641100-74a1f5b1b6b3?ixid=ele8','electrodomesticos'),(78,'Cafetera espresso',6800.00,7,'https://aliss.do/media/catalog/product/1/2/12acd1d410d65c1e8be7831573cf197505822040_file.jpg?optimize=medium&bg-color=255,255,255&fit=bounds&height=700&width=700&canvas=700:700','electrodomesticos'),(79,'Silla de oficina ergonómica',4700.00,11,'https://images.unsplash.com/photo-1519710164239-da123dc03ef4?ixid=mob11','mobiliaria'),(80,'Mesa de centro moderna',5400.00,9,'https://images.unsplash.com/photo-1540575467063-178a50c2df87?ixid=mob12','mobiliaria'),(81,'Espejo de cuerpo entero',5100.00,7,'https://yourhomeplan.cl/cdn/shop/products/negros.jpg?v=1648421662','decoraciones'),(82,'Set de cortinas',2300.00,14,'https://images.unsplash.com/photo-1501004318641-b39e6451bec6?ixid=dec14','decoraciones'),(83,'Juego de tazones',1800.00,25,'https://images.unsplash.com/photo-1523473827536-53e31e1f1e09?ixid=vaj15','vajilla'),(84,'Kit de jardinería',2900.00,18,'https://images.unsplash.com/photo-1581091870633-1167f2ab0c86?ixid=her16','herramientas'),(85,'Set de brocas',1700.00,22,'https://images.unsplash.com/photo-1556910123-8f26e5a5c0ed?ixid=her17','herramientas'),(86,'Batidora de mano',3500.00,12,'https://casacuesta.com/media/catalog/product/cache/fde49a4ea9a339628caa0bc56aea00ff/3/2/3228929-1.jpg','electrodomesticos'),(87,'Refrigerador 200L',21000.00,6,'https://images.unsplash.com/photo-1581579181357-8e8eb8b015d6?ixid=ele19','electrodomesticos'),(88,'Vajilla infantil',2600.00,20,'https://images.unsplash.com/photo-1564758866814-6b6a2f0b4a8f?ixid=vaj20','vajilla'),(89,'Lámpara de techo LED',2800.00,15,'https://images.unsplash.com/photo-1493666438817-866a91353ca9?ixid=dec21','decoraciones'),(91,'Sofá cama',11500.00,4,'https://images.unsplash.com/photo-1587293852720-3a0b9c073ab3?ixid=mob23','mobiliaria'),(93,'Porta retratos',900.00,30,'https://images.unsplash.com/photo-1505691723518-36a0f3a0a438?ixid=dec25','decoraciones'),(94,'Set de copas de champán',4100.00,16,'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?ixid=vaj26','vajilla'),(95,'Taladro percutor',6800.00,5,'https://images.unsplash.com/photo-1581091012184-6107c2ac0b50?ixid=her27','herramientas'),(96,'Cafetera de cápsulas',7200.00,6,'https://casacuesta.com/media/catalog/product/cache/fde49a4ea9a339628caa0bc56aea00ff/3/2/3262487-1.jpg','electrodomesticos'),(97,'Set de ollas antiadherentes',5300.00,7,'https://images.unsplash.com/photo-1564758866814-6b6a2f0b4a8f?ixid=vaj29','vajilla'),(98,'Reloj de sobremesa',2600.00,12,'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?ixid=dec30','decoraciones'),(99,'Set de sartenes',4800.00,14,'https://images.unsplash.com/photo-1598511724252-8e4db9c6f47f?ixid=vaj31','vajilla'),(100,'Mesa plegable',5900.00,6,'https://images.unsplash.com/photo-1540575467063-178a50c2df87?ixid=mob32','mobiliaria'),(102,'Lámpara de mesa',1400.00,25,'https://images.unsplash.com/photo-1493666438817-866a91353ca9?ixid=dec34','decoraciones'),(103,'Armario empotrado',13200.00,2,'https://campahome.com/wp-content/uploads/2022/11/WhatsApp-Image-2022-11-07-at-11.04.07-1.jpeg','mobiliaria'),(104,'Set de cucharas medidoras',800.00,30,'https://images.unsplash.com/photo-1598511724252-8e4db9c6f47f?ixid=vaj36','vajilla'),(105,'Taladro percutor Bosch',8200.00,3,'https://images.unsplash.com/photo-1581091012184-6107c2ac0b50?ixid=her37','herramientas'),(106,'Horno tostador',2900.00,15,'https://images.unsplash.com/photo-1587393430948-3fcd89c84816?ixid=ele38','electrodomesticos'),(107,'Aspiradora de mano',4500.00,9,'https://i.blogs.es/bc2508/aspirador-de-mano/1366_2000.jpg','electrodomesticos'),(108,'Estante de metal',3900.00,12,'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ48igs9-9D-1B-iVv0KFrawM_09G26JDjH8g&s','mobiliaria'),(109,'Cojines de exterior',1100.00,20,'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQHwbQIaoz4wcSpIjTi9eGCLO2Lch6Gu-S-YA&s','decoraciones'),(110,'Cubiertos infantiles',2100.00,22,'https://lollypopbaby.es/files/product_images/2023/04/29/siliconen_bestek_roze.jpg','vajilla'),(111,'Juego de brocas Bosch',2500.00,18,'https://images.unsplash.com/photo-1556910123-8f26e5a5c0ed?ixid=her43','herramientas'),(112,'Reproductor DVD',4700.00,7,'https://images.unsplash.com/photo-1595637641100-74a1f5b1b6b3?ixid=ele44','electrodomesticos'),(113,'Lámpara de mesa LED',1300.00,28,'https://images.unsplash.com/photo-1493666438817-866a91353ca9?ixid=dec45','decoraciones'),(114,'Mesa auxiliar',3000.00,16,'https://images.unsplash.com/photo-1540575467063-178a50c2df87?ixid=mob46','mobiliaria'),(115,'Banco de madera reciclada',3500.00,8,'https://i.ytimg.com/vi/M3_OeTQsre4/maxresdefault.jpg','mobiliaria'),(116,'Cucharas de servir',600.00,35,'https://carballo.com.do/19015-large_default/cuchara-servir-4x243mmamberes18-10.jpg','vajilla'),(117,'Taladro inalámbrico Makita',9200.00,3,'https://images.unsplash.com/photo-1581091012184-6107c2ac0b50?ixid=her49','herramientas'),(118,'Horno de convección',8500.00,4,'https://images.unsplash.com/photo-1587393430948-3fcd89c84816?ixid=ele50','electrodomesticos'),(119,'Estantería modular',7200.00,5,'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?ixid=mob51','mobiliaria'),(120,'Cojines estampados',950.00,30,'https://cojinesdelapaz.cl/cdn/shop/files/Diseno_sin_titulo_-_2024-09-27T162757.126.jpg?v=1727465289&width=550','decoraciones'),(121,'Set de vasos altares',4100.00,14,'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?ixid=vaj53','vajilla'),(122,'Llave de tubo',1800.00,22,'https://images.unsplash.com/photo-1556910123-8f26e5a5c0ed?ixid=her54','herramientas'),(123,'Reproductor Blu-ray',5800.00,6,'https://images.unsplash.com/photo-1595637641100-74a1f5b1b6b3?ixid=ele55','electrodomesticos'),(124,'Mesa de comedor extensible',11200.00,3,'https://images.unsplash.com/photo-1540575467063-178a50c2df87?ixid=mob56','mobiliaria'),(125,'Vajilla de vidrio templado',5100.00,8,'https://images.unsplash.com/photo-1564758866814-6b6a2f0b4a8f?ixid=vaj57','vajilla'),(126,'Taladro de impacto',7500.00,5,'https://images.unsplash.com/photo-1581091012184-6107c2ac0b50?ixid=her58','herramientas'),(127,'Microondas retro',8800.00,4,'https://images.unsplash.com/photo-1595637641100-74a1f5b1b6b3?ixid=ele59','electrodomesticos'),(128,'Mobiliaria',2900.00,12,'https://casacuesta.com/media/catalog/product/cache/fde49a4ea9a339628caa0bc56aea00ff/3/2/3290753-1.jpg','mobiliaria'),(129,'Juego de sal y pimienta',400.00,50,'https://images.unsplash.com/photo-1564758866814-6b6a2f0b4a8f?ixid=vaj61','vajilla');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `contraseña` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `rol` enum('usuario','admin','superadmin') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `token` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `verificado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'Brenda','brenda@gmail.com','$2y$10$CtUvo/xHNW8jwFDoyDY3.Oqh0gpsI30HCMonEOY7WdmqF.faM9Kb.','usuario',NULL,1),(2,'zaid','zaid@gmail.com','$2y$10$DHXIIlGwabUn9EpwOqUpgubwl2PRcP8FMRohF4N/m3syD.Kf7BQxq','admin','ce8481fddc26f2d0adca4cf2157b7d9f',0),(3,'nuevo prueba','prueba@gmail.com','$2y$10$qKwpZ9tY0Iz4wakPNgNcU.TMr.6JDpoIA2Lihvc0IfRP3ZsxTTQ/u','',NULL,0),(4,'Cristina','mariaanabelmencia@gmail.com','$2y$10$SRgA0Kv843PxVdjeGbyBreZqqiIJUakTS8.l4HFChMh.fmF3KdU9O','usuario',NULL,1),(5,'prueba23','anibelmencializ@gmail.com','$2y$10$0x8QOP5B8pS778Lr2k1kJO9Cs4J/anF28I/z0bChYbyiY.e/AwD/a','usuario','0a065f9ddc3209f847fe88f895e222da',0),(9,'ana','anabelmencia06@gmail.com','$2y$10$WMD8p3C.1qACXOyJgRsQBO6ZTHSz6ug3XqMEQ6FYt.UPt/5onelM6','usuario',NULL,1),(10,'Distribuidora Lorenzo','distribuidoralorenzo19@gmail.com','$2y$10$eOHupHo9Ai5UrOXJozgfX.rgrPaW1PIM26iaY4s08MABQ45SRJHCa','superadmin',NULL,1),(13,'Brenda Mencia','brendamencia31@gmail.com','$2y$10$bYEoUIeTSYnnDDRYr0XgSu/5VYjHJnQVjg2OPo.1bON7sdtP85CJ2','usuario',NULL,1),(14,'Claudio Abel Rosario Reyes','binformatik007@gmail.com','$2y$10$2sWHEyw0lWB8RHELk1hzgOAjCxgHpMVnlP4hZ9UyJ/WzsfWh2vza6','usuario','a7985f8b5f5e3e7007f051e6c9543a9a',1),(15,'juan','den@gmail.com','$2y$10$9MppH6KHDs8epoi2vVY1CewCiF8E79AnZmnAarsHJlFEooBzouXC2','usuario','e6c92cba67bee83647cc3794a59535f9',1);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ventas`
--

DROP TABLE IF EXISTS `ventas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ventas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ventas`
--

LOCK TABLES `ventas` WRITE;
/*!40000 ALTER TABLE `ventas` DISABLE KEYS */;
INSERT INTO `ventas` VALUES (1,10,65000.00,'2025-05-08 13:08:54'),(2,10,150000.00,'2025-05-08 13:16:40'),(3,10,1000.00,'2025-05-08 13:23:59'),(4,10,1000.00,'2025-05-08 13:34:39'),(5,10,1000.00,'2025-05-08 13:35:54'),(6,10,1000.00,'2025-05-08 13:41:15'),(7,10,5000.00,'2025-05-08 13:46:33'),(8,10,1500.00,'2025-05-08 13:49:13'),(9,10,6000.00,'2025-05-08 13:50:47'),(10,10,50000.00,'2025-05-08 13:58:09'),(11,10,4800.00,'2025-05-08 14:01:17'),(12,10,2500.00,'2025-05-08 14:04:01'),(13,10,10600.00,'2025-05-08 14:11:57'),(14,10,3600.00,'2025-05-08 14:17:47'),(15,10,50000.00,'2025-05-08 14:31:05'),(16,10,7600.00,'2025-05-08 14:34:17'),(17,10,3000.00,'2025-05-08 14:36:20'),(18,15,10800.00,'2025-05-08 17:26:50'),(19,9,265000.00,'2025-05-08 20:53:25'),(20,10,7700.00,'2025-05-09 12:47:58'),(21,10,6600.00,'2025-05-09 16:16:08');
/*!40000 ALTER TABLE `ventas` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-05-11 22:20:15
