-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema ecommerce
--

CREATE DATABASE IF NOT EXISTS ecommerce;
USE ecommerce;

--
-- Definition of table `ecom_categories`
--

DROP TABLE IF EXISTS `ecom_categories`;
CREATE TABLE `ecom_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ecom_categories`
--

/*!40000 ALTER TABLE `ecom_categories` DISABLE KEYS */;
INSERT INTO `ecom_categories` (`id`,`name`,`description`,`image`,`created_at`,`updated_at`) VALUES 
 (1,'Fashion','add','1.png','2025-06-30 01:03:34','2025-06-30 19:42:54'),
 (2,'Bags','BagPack','2.png','2025-06-30 20:59:55','2025-06-30 21:31:36'),
 (3,'Electronics','Laptop, Mobile','3.png','2025-06-30 21:25:29','2025-06-30 21:25:29');
/*!40000 ALTER TABLE `ecom_categories` ENABLE KEYS */;


--
-- Definition of table `ecom_countries`
--

DROP TABLE IF EXISTS `ecom_countries`;
CREATE TABLE `ecom_countries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `code` varchar(45) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ecom_countries`
--

/*!40000 ALTER TABLE `ecom_countries` DISABLE KEYS */;
INSERT INTO `ecom_countries` (`id`,`name`,`code`,`created_at`,`updated_at`) VALUES 
 (1,'Bangladesh','BD',NULL,NULL),
 (2,'South Korea','S.Korea',NULL,NULL),
 (3,'Japan','JP',NULL,NULL),
 (4,'India','Ind',NULL,NULL);
/*!40000 ALTER TABLE `ecom_countries` ENABLE KEYS */;


--
-- Definition of table `ecom_customers`
--

DROP TABLE IF EXISTS `ecom_customers`;
CREATE TABLE `ecom_customers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `email` varchar(45) DEFAULT NULL,
  `address` text NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `phone` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ecom_customers`
--

/*!40000 ALTER TABLE `ecom_customers` DISABLE KEYS */;
INSERT INTO `ecom_customers` (`id`,`name`,`email`,`address`,`status`,`created_at`,`updated_at`,`phone`) VALUES 
 (1,'Mim','ab@gmail.com','dasd','active','2025-07-04 16:41:41','2025-07-04 16:41:41',''),
 (2,'anmu','samdola81@gmail.com','sdsds','active','2025-07-07 20:30:51','2025-07-07 20:30:51','546546545');
/*!40000 ALTER TABLE `ecom_customers` ENABLE KEYS */;


--
-- Definition of table `ecom_order_deliveries`
--

DROP TABLE IF EXISTS `ecom_order_deliveries`;
CREATE TABLE `ecom_order_deliveries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `delivery_person` varchar(45) NOT NULL,
  `delivery_company` varchar(45) DEFAULT NULL,
  `delivery_note` text DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `delivery_status` enum('pending','out_for_delivery','delivered','failed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `warehouse_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ecom_order_deliveries`
--

/*!40000 ALTER TABLE `ecom_order_deliveries` DISABLE KEYS */;
INSERT INTO `ecom_order_deliveries` (`id`,`order_id`,`delivery_person`,`delivery_company`,`delivery_note`,`delivery_date`,`delivery_status`,`created_at`,`updated_at`,`warehouse_id`) VALUES 
 (1,1,'dgfr','dfdsfdsf','dsf','0000-00-00','pending','2025-07-10 23:01:10','2025-07-10 23:52:59',1),
 (2,2,'John Doe','FastExpress','No note','2025-07-10','pending','2025-07-10 23:29:26','2025-07-11 01:57:48',1),
 (3,3,'John Doe','Fast Delivery Ltd.','Handle with care',NULL,'pending','2025-07-10 17:50:21','2025-07-11 01:57:50',1),
 (4,1,'fd','dfs','dsf',NULL,'pending','2025-07-11 14:54:25','2025-07-11 14:54:25',1),
 (5,6,'ds','dsf','gfg',NULL,'pending','2025-07-11 14:56:05','2025-07-11 14:56:05',1);
/*!40000 ALTER TABLE `ecom_order_deliveries` ENABLE KEYS */;


--
-- Definition of table `ecom_order_delivery_items`
--

DROP TABLE IF EXISTS `ecom_order_delivery_items`;
CREATE TABLE `ecom_order_delivery_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `delivery_id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned NOT NULL,
  `quantity` varchar(45) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ecom_order_delivery_items`
--

/*!40000 ALTER TABLE `ecom_order_delivery_items` DISABLE KEYS */;
INSERT INTO `ecom_order_delivery_items` (`id`,`delivery_id`,`product_id`,`quantity`,`created_at`,`updated_at`) VALUES 
 (1,3,18,'1','2025-07-10 17:50:21','2025-07-10 17:50:21'),
 (2,4,18,'1','2025-07-11 14:54:25','2025-07-11 14:54:25'),
 (3,5,1,'2','2025-07-11 14:56:05','2025-07-11 14:56:05');
/*!40000 ALTER TABLE `ecom_order_delivery_items` ENABLE KEYS */;


--
-- Definition of table `ecom_order_items`
--

DROP TABLE IF EXISTS `ecom_order_items`;
CREATE TABLE `ecom_order_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned NOT NULL,
  `quantity` decimal(10,0) NOT NULL,
  `unit_price` decimal(10,0) NOT NULL,
  `discount` varchar(45) NOT NULL,
  `tax` decimal(10,0) NOT NULL,
  `subtotal` decimal(10,0) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ecom_order_items`
--

/*!40000 ALTER TABLE `ecom_order_items` DISABLE KEYS */;
INSERT INTO `ecom_order_items` (`id`,`order_id`,`product_id`,`quantity`,`unit_price`,`discount`,`tax`,`subtotal`,`created_at`,`updated_at`) VALUES 
 (1,1,18,'1','100','0','0','100','2025-07-10 12:53:23','2025-07-10 12:53:23'),
 (2,2,18,'1','100','0','0','100','2025-07-10 13:11:21','2025-07-10 13:11:21'),
 (3,3,18,'1','100','0','0','100','2025-07-10 13:16:41','2025-07-10 13:16:41'),
 (4,4,17,'1','100','5','10','105','2025-07-10 13:17:29','2025-07-10 13:17:29'),
 (5,5,17,'10','10','0','0','100','2025-07-10 17:11:05','2025-07-10 17:11:05'),
 (6,6,1,'2','100','0','0','200','2025-07-11 14:55:33','2025-07-11 14:55:33');
/*!40000 ALTER TABLE `ecom_order_items` ENABLE KEYS */;


--
-- Definition of table `ecom_order_payments`
--

DROP TABLE IF EXISTS `ecom_order_payments`;
CREATE TABLE `ecom_order_payments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `payment_date` datetime NOT NULL DEFAULT current_timestamp(),
  `amount` decimal(12,2) NOT NULL,
  `method` enum('cash','bank','cheque','card','mobile','other') NOT NULL DEFAULT 'cash',
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `ecom_order_payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `ecom_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ecom_order_payments`
--

/*!40000 ALTER TABLE `ecom_order_payments` DISABLE KEYS */;
INSERT INTO `ecom_order_payments` (`id`,`order_id`,`payment_date`,`amount`,`method`,`note`,`created_at`,`updated_at`) VALUES 
 (1,1,'2025-07-10 00:00:00','100.00','cash',NULL,'2025-07-10 12:53:23','2025-07-10 12:53:23'),
 (2,2,'2025-07-10 00:00:00','105.00','cash',NULL,'2025-07-10 13:11:21','2025-07-10 13:11:21'),
 (3,3,'2025-07-10 00:00:00','105.00','cash',NULL,'2025-07-10 13:16:41','2025-07-10 13:16:41'),
 (4,4,'2025-07-10 00:00:00','125.00','cash',NULL,'2025-07-10 13:17:29','2025-07-10 13:17:29'),
 (5,5,'2025-07-10 00:00:00','100.00','cash',NULL,'2025-07-10 17:11:05','2025-07-10 17:11:05'),
 (6,6,'2025-07-11 00:00:00','200.00','cash',NULL,'2025-07-11 14:55:33','2025-07-11 14:55:33'),
 (7,6,'2025-07-11 00:00:00','0.00','cash',NULL,'2025-07-11 14:55:33','2025-07-11 14:55:33');
/*!40000 ALTER TABLE `ecom_order_payments` ENABLE KEYS */;


--
-- Definition of table `ecom_order_shipments`
--

DROP TABLE IF EXISTS `ecom_order_shipments`;
CREATE TABLE `ecom_order_shipments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `shipment_date` datetime NOT NULL DEFAULT current_timestamp(),
  `tracking_number` varchar(100) DEFAULT NULL,
  `carrier` varchar(100) DEFAULT NULL,
  `status` enum('pending','shipped','delivered','cancelled') DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ecom_order_shipments`
--

/*!40000 ALTER TABLE `ecom_order_shipments` DISABLE KEYS */;
/*!40000 ALTER TABLE `ecom_order_shipments` ENABLE KEYS */;


--
-- Definition of table `ecom_order_status_histories`
--

DROP TABLE IF EXISTS `ecom_order_status_histories`;
CREATE TABLE `ecom_order_status_histories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `old_status` enum('pending','confirmed','processing','shipped','delivered','cancelled','returned') DEFAULT NULL,
  `new_status` enum('pending','confirmed','processing','shipped','delivered','cancelled','returned') NOT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `ecom_order_status_histories_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `ecom_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ecom_order_status_histories`
--

/*!40000 ALTER TABLE `ecom_order_status_histories` DISABLE KEYS */;
INSERT INTO `ecom_order_status_histories` (`id`,`order_id`,`old_status`,`new_status`,`changed_at`,`created_at`,`updated_at`) VALUES 
 (1,1,NULL,'pending','2025-07-10 18:53:23','2025-07-10 12:53:23','2025-07-10 12:53:23'),
 (2,2,NULL,'pending','2025-07-10 19:11:21','2025-07-10 13:11:21','2025-07-10 13:11:21'),
 (3,3,NULL,'pending','2025-07-10 19:16:41','2025-07-10 13:16:41','2025-07-10 13:16:41'),
 (4,4,NULL,'pending','2025-07-10 19:17:29','2025-07-10 13:17:29','2025-07-10 13:17:29'),
 (5,5,NULL,'pending','2025-07-10 23:11:05','2025-07-10 17:11:05','2025-07-10 17:11:05'),
 (6,6,NULL,'pending','2025-07-11 20:55:33','2025-07-11 14:55:33','2025-07-11 14:55:33');
/*!40000 ALTER TABLE `ecom_order_status_histories` ENABLE KEYS */;


--
-- Definition of table `ecom_orders`
--

DROP TABLE IF EXISTS `ecom_orders`;
CREATE TABLE `ecom_orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int(10) unsigned NOT NULL,
  `order_no` varchar(50) NOT NULL,
  `order_date` datetime NOT NULL DEFAULT current_timestamp(),
  `delivery_date` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','confirmed','processing','shipped','delivered','cancelled','returned') DEFAULT 'pending',
  `payment_status` enum('unpaid','partial','paid','refunded') DEFAULT 'unpaid',
  `total_amount` decimal(12,2) NOT NULL,
  `paid_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `due_amount` decimal(12,2) NOT NULL,
  `shipping_address` text NOT NULL,
  `discount_amount` decimal(12,2) NOT NULL DEFAULT 0.00 COMMENT 'Order‑level discount (absolute value)',
  `vat_amount` decimal(12,2) NOT NULL DEFAULT 0.00 COMMENT 'Total VAT applied',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_no` (`order_no`),
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `ecom_orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `ecom_customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ecom_orders`
--

/*!40000 ALTER TABLE `ecom_orders` DISABLE KEYS */;
INSERT INTO `ecom_orders` (`id`,`customer_id`,`order_no`,`order_date`,`delivery_date`,`status`,`payment_status`,`total_amount`,`paid_amount`,`due_amount`,`shipping_address`,`discount_amount`,`vat_amount`,`created_at`,`updated_at`) VALUES 
 (1,1,'ORD-250710125323701','2025-07-10 00:00:00','2025-07-10 00:00:00','pending','paid','100.00','100.00','0.00','dfdfd','0.00','0.00','2025-07-10 12:53:23','2025-07-10 12:53:23'),
 (2,1,'ORD-250710131120206','2025-07-10 00:00:00','2025-07-10 00:00:00','pending','partial','105.00','105.00','-5.00','dfdfdf','0.00','5.00','2025-07-10 13:11:21','2025-07-10 13:11:21'),
 (3,1,'ORD-250710131641794','2025-07-10 00:00:00','2025-07-10 00:00:00','pending','paid','105.00','105.00','0.00','fdf','0.00','5.00','2025-07-10 13:16:41','2025-07-10 13:16:41'),
 (4,1,'ORD-250710131729741','2025-07-10 00:00:00','2025-07-10 00:00:00','pending','paid','125.00','125.00','0.00','rfsd','0.00','20.00','2025-07-10 13:17:29','2025-07-10 13:17:29'),
 (5,1,'ORD-250710171105413','2025-07-10 00:00:00','2025-07-10 00:00:00','pending','paid','100.00','100.00','0.00','sfdf','0.00','0.00','2025-07-10 17:11:05','2025-07-10 17:11:05'),
 (6,1,'ORD-250711145533465','2025-07-11 00:00:00','2025-07-11 00:00:00','pending','paid','200.00','200.00','0.00','abc','0.00','0.00','2025-07-11 14:55:33','2025-07-11 14:55:33');
/*!40000 ALTER TABLE `ecom_orders` ENABLE KEYS */;


--
-- Definition of table `ecom_product_brands`
--

DROP TABLE IF EXISTS `ecom_product_brands`;
CREATE TABLE `ecom_product_brands` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `description` varchar(45) DEFAULT NULL,
  `image` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `category_id` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ecom_product_brands`
--

/*!40000 ALTER TABLE `ecom_product_brands` DISABLE KEYS */;
INSERT INTO `ecom_product_brands` (`id`,`name`,`description`,`image`,`created_at`,`updated_at`,`category_id`) VALUES 
 (1,'Arrong2','Jama','1.png','2025-06-30 01:03:07','2025-07-01 04:23:35','1'),
 (2,'Sara','Shirt, Pant, T-shirt','2.png','2025-06-30 21:10:12','2025-07-01 05:07:57','1'),
 (3,'Apple','Phone, Earphone, Laptop,Mac','3.svg','2025-06-30 22:29:49','2025-06-30 22:29:49','3');
/*!40000 ALTER TABLE `ecom_product_brands` ENABLE KEYS */;


--
-- Definition of table `ecom_products`
--

DROP TABLE IF EXISTS `ecom_products`;
CREATE TABLE `ecom_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `barcode` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) DEFAULT 0.00,
  `tax` decimal(10,2) DEFAULT 0.00,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `img` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ecom_products`
--

/*!40000 ALTER TABLE `ecom_products` DISABLE KEYS */;
INSERT INTO `ecom_products` (`id`,`name`,`brand_id`,`category_id`,`supplier_id`,`barcode`,`price`,`discount`,`tax`,`quantity`,`status`,`img`,`description`,`created_at`,`updated_at`) VALUES 
 (1,'Threepices4545',2,3,1,'454445','500.00','12.00','0.00',5,'active','[\"1.webp\",\"1751419936_68648c201aedf.png\",\"1751419936_68648c201b267.png\",\"1751420278_68648d765565d.png\",\"1751420279_68648d771e72e.png\",\"1751420279_68648d775da68.png\",\"1751420279_68648d779f01f.png\",\"1751420288_68648d8025494.png\"]','Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.','2025-06-30 00:13:50','2025-07-02 04:35:44'),
 (2,'Threepis',1,1,1,'454445','700.00','0.00','0.00',50,'active','[\"2.webp\"]','sfdffdsfasfdsfa','2025-06-30 01:05:12','2025-07-05 04:47:43'),
 (6,'Mim',1,1,1,'454445','500.00','0.00','100.00',50,'active','[\"6_1751408563_Screenshot 2025-05-03 010411.png\",\"6_1751408563_Screenshot 2025-05-03 132837.png\"]','ghgh','2025-07-01 22:22:43','2025-07-01 22:22:43'),
 (7,'Mim',1,1,1,'454445','500.00','50.00','2000.00',50,'active','[\"7_1751434120_Screenshot 2025-05-07 005327.png\",\"7_1751434120_Screenshot 2025-05-07 005600.png\",\"7_1751434120_Screenshot 2025-05-07 005756.png\"]','hdfgf','2025-07-02 05:28:40','2025-07-02 05:28:40'),
 (9,'fish5',1,1,1,'fghds','500.00','20.00','100.00',100,'active','[\"9_1751447707__104304494_mediaitem104304493.jpg\",\"9_1751447707_Emoji-with-blue-sleeping-cap-279x300.jpg\",\"9_1751447707_HR- Diagram 2.png\",\"9_1751447707_sayma.png\",\"9_1751447707_HR- Diagram.png\",\"9_1751447707_datatable.png\",\"1751447785_6864f8e9eb117.jpg\"]','fgfrtgf','2025-07-02 09:15:07','2025-07-05 04:42:47'),
 (10,'Doctor',2,1,1,'588','500.00','0.00','20.00',10,'active','[\"10_1751447905_Emoji-with-blue-sleeping-cap-279x300.jpg\"]','juygjghjhgj','2025-07-02 09:18:25','2025-07-02 09:18:25'),
 (11,'Samsung S25 Ultra 6.9\" AMOLED Display, 200MP',3,NULL,1,'454445','5000.00','0.00','0.00',50,'active','[\"11_1751480314_samsung_medium.avif\",\"11_1751480314_samsung_S25_ULTRA_medium.avif\",\"11_1751480314_Galaxy_s25_ultra.webp\",\"11_1751480314_samsung_1.webp\"]','dss','2025-07-02 18:18:34','2025-07-02 18:18:34'),
 (13,'Mim',1,NULL,1,'56465465','500.00','500.00','200.00',100,'active','[\"13_1751691430_samsung_S25_ULTRA_medium.avif\",\"13_1751691430_Galaxy_s25_ultra.webp\"]','dfasdf','2025-07-05 04:57:10','2025-07-05 04:57:10'),
 (14,'Mim',1,NULL,1,'56465465','500.00','10.00','20.00',6,'active','[\"14_1751691587_Samsung_Logo.svg.webp\",\"14_1751691587_samsung_medium.avif\"]','geche','2025-07-05 04:59:47','2025-07-05 04:59:47'),
 (15,'a',1,1,1,'11','50.00','0.00','0.00',10,'active','[\"15_1751693038_Samsung_Logo.svg.webp\",\"15_1751693038_samsung_medium.avif\"]','ds','2025-07-05 05:23:58','2025-07-05 11:26:08'),
 (16,'Mim',1,NULL,1,'11','50.00','50.00','0.00',10,'active','[\"16_1751693597_Samsung_Logo.svg.webp\",\"16_1751693597_samsung_medium.avif\"]','dfsdfasd','2025-07-05 05:33:17','2025-07-05 05:33:17'),
 (17,'Three pieces',1,NULL,1,'454445','50.00','0.00','0.00',10,'active','[\"17_1751693705_Samsung_Logo.svg.webp\"]','fdsf','2025-07-05 05:35:05','2025-07-05 05:35:05'),
 (18,'anmu',1,NULL,1,'fghds','10.00','0.00','0.00',1,'active','[\"18_1751693836_samsung_S25_ULTRA_medium.avif\",\"18_1751693836_Galaxy_s25_ultra.webp\"]','adssd','2025-07-05 05:37:16','2025-07-05 05:37:16'),
 (19,'Mim',1,1,1,'56465465','500.00','0.00','0.00',10,'active','[\"19_1751697180_Samsung_Logo.svg.webp\",\"19_1751697180_samsung_medium.avif\"]','aaaaaaaaaaaaaaaaaaaa','2025-07-05 06:33:00','2025-07-05 06:33:00'),
 (20,'done',1,3,1,'454445','10.00','0.00','0.00',10,'active','[\"20_1751697227_samsung_S25_ULTRA_medium.avif\",\"20_1751697227_Galaxy_s25_ultra.webp\"]','cccccccc','2025-07-05 06:33:47','2025-07-05 06:33:47');
/*!40000 ALTER TABLE `ecom_products` ENABLE KEYS */;


--
-- Definition of table `ecom_purchase_items`
--

DROP TABLE IF EXISTS `ecom_purchase_items`;
CREATE TABLE `ecom_purchase_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned NOT NULL,
  `quantity` int(10) unsigned NOT NULL,
  `discount` decimal(10,0) NOT NULL,
  `tax_percent` decimal(10,0) NOT NULL,
  `tax_amount` decimal(10,0) NOT NULL,
  `subtotal` decimal(10,0) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `unit_cost` decimal(10,0) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ecom_purchase_items`
--

/*!40000 ALTER TABLE `ecom_purchase_items` DISABLE KEYS */;
INSERT INTO `ecom_purchase_items` (`id`,`purchase_id`,`product_id`,`quantity`,`discount`,`tax_percent`,`tax_amount`,`subtotal`,`created_at`,`updated_at`,`unit_cost`) VALUES 
 (2,1,17,10,'1','1','10','1009','2025-07-05 22:17:23','2025-07-05 22:17:23','100'),
 (3,2,18,2,'0','0','0','196','2025-07-05 22:22:03','2025-07-05 22:22:03','98'),
 (4,3,1,10,'0','0','0','1000','2025-07-10 14:24:59','2025-07-10 14:24:59','100'),
 (5,4,17,100,'0','0','0','10000','2025-07-10 14:45:55','2025-07-10 14:45:55','100'),
 (6,5,16,10,'0','0','0','990','2025-07-10 18:22:09','2025-07-10 18:22:09','99');
/*!40000 ALTER TABLE `ecom_purchase_items` ENABLE KEYS */;


--
-- Definition of table `ecom_purchase_payments`
--

DROP TABLE IF EXISTS `ecom_purchase_payments`;
CREATE TABLE `ecom_purchase_payments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_id` int(10) unsigned NOT NULL,
  `payment_date` datetime NOT NULL,
  `amount` decimal(10,0) NOT NULL,
  `reference_no` varchar(45) DEFAULT NULL,
  `method` enum('cash','bank','cheque','card','mobile','other') NOT NULL DEFAULT 'cash',
  `currency` char(3) NOT NULL DEFAULT 'BDT',
  `exchange_rate` decimal(12,6) NOT NULL DEFAULT 1.000000,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ecom_purchase_payments`
--

/*!40000 ALTER TABLE `ecom_purchase_payments` DISABLE KEYS */;
INSERT INTO `ecom_purchase_payments` (`id`,`purchase_id`,`payment_date`,`amount`,`reference_no`,`method`,`currency`,`exchange_rate`,`created_at`,`updated_at`) VALUES 
 (2,1,'2025-07-05 00:00:00','1510','REF-6869a45ff1983','cash','BDT','1.000000','2025-07-05 22:17:23','2025-07-05 22:17:23'),
 (3,2,'2025-07-05 22:22:03','196','REF-6869a58b27522','cash','BDT','1.000000','2025-07-05 22:22:03','2025-07-05 22:22:03'),
 (4,3,'2025-07-10 14:24:59','1000','REF-686fcd3b9a2ee','cash','BDT','1.000000','2025-07-10 14:24:59','2025-07-10 14:24:59'),
 (5,4,'2025-07-10 14:45:55','10000','REF-686fd223eb578','cash','BDT','1.000000','2025-07-10 14:45:55','2025-07-10 14:45:55');
/*!40000 ALTER TABLE `ecom_purchase_payments` ENABLE KEYS */;


--
-- Definition of table `ecom_purchases`
--

DROP TABLE IF EXISTS `ecom_purchases`;
CREATE TABLE `ecom_purchases` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `supplier_id` int(10) unsigned NOT NULL,
  `warehouse_id` int(10) unsigned NOT NULL,
  `reference` varchar(45) NOT NULL,
  `purchase_no` varchar(45) NOT NULL,
  `invoice_number` varchar(45) NOT NULL,
  `purchase_date` datetime NOT NULL,
  `note` text NOT NULL,
  `sub_total` decimal(10,2) NOT NULL,
  `shipping` decimal(10,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `paid_amount` decimal(10,2) NOT NULL,
  `due_amount` decimal(10,2) NOT NULL,
  `payment_status` enum('due','paid','partial') DEFAULT 'due',
  `status` enum('pending','completed','cancelled') DEFAULT 'pending',
  `created_by` int(10) unsigned DEFAULT NULL,
  `is_returned` tinyint(1) DEFAULT 0,
  `is_editable` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `order_tax` decimal(10,0) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ecom_purchases`
--

/*!40000 ALTER TABLE `ecom_purchases` DISABLE KEYS */;
INSERT INTO `ecom_purchases` (`id`,`supplier_id`,`warehouse_id`,`reference`,`purchase_no`,`invoice_number`,`purchase_date`,`note`,`sub_total`,`shipping`,`total_amount`,`paid_amount`,`due_amount`,`payment_status`,`status`,`created_by`,`is_returned`,`is_editable`,`created_at`,`updated_at`,`order_tax`) VALUES 
 (1,1,1,'PUR-1751753768830','PNO-1751753768830','INV-1751753768830','2025-07-05 00:00:00','dfafhfhgh','1009.00','500.00','1510.00','1510.00','0.00','paid','pending',NULL,0,1,'2025-07-05 22:17:03','2025-07-05 22:17:23','1'),
 (2,1,1,'PUR-1751754100886','PNO-1751754100886','INV-1751754100886','2025-07-05 00:00:00','ghgfh','196.00','0.00','196.00','196.00','0.00','paid','pending',NULL,0,1,'2025-07-05 22:22:03','2025-07-05 22:22:03','0'),
 (3,1,1,'PUR-1752157473261','PNO-1752157473261','INV-1752157473261','2025-07-10 00:00:00','fdsf','1000.00','0.00','1000.00','1000.00','0.00','paid','pending',NULL,0,1,'2025-07-10 14:24:59','2025-07-10 14:24:59','0'),
 (4,1,1,'PUR-1752158718858','PNO-1752158718858','INV-1752158718858','2025-07-10 00:00:00','dfdsf','10000.00','0.00','10000.00','10000.00','0.00','paid','pending',NULL,0,1,'2025-07-10 14:45:55','2025-07-10 14:45:55','0'),
 (5,1,1,'PUR-1752171702842','PNO-1752171702842','INV-1752171702842','2025-07-10 00:00:00','dfgg','990.00','0.00','1080.00','0.00','1080.00','due','pending',NULL,0,1,'2025-07-10 18:22:09','2025-07-10 18:22:09','90');
/*!40000 ALTER TABLE `ecom_purchases` ENABLE KEYS */;


--
-- Definition of table `ecom_stocks`
--

DROP TABLE IF EXISTS `ecom_stocks`;
CREATE TABLE `ecom_stocks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `warehouse_id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned NOT NULL,
  `type` enum('purchase','sale','return','adjustment','transfer_in','transfer_out') NOT NULL,
  `reference_id` int(10) unsigned DEFAULT NULL COMMENT 'purchase_id, order_id etc.',
  `quantity_in` int(10) unsigned DEFAULT 0,
  `quantity_out` int(10) unsigned DEFAULT 0,
  `stock_date` datetime NOT NULL DEFAULT current_timestamp(),
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ecom_stocks`
--

/*!40000 ALTER TABLE `ecom_stocks` DISABLE KEYS */;
INSERT INTO `ecom_stocks` (`id`,`warehouse_id`,`product_id`,`type`,`reference_id`,`quantity_in`,`quantity_out`,`stock_date`,`note`,`created_at`,`updated_at`) VALUES 
 (1,1,1,'purchase',3,10,0,'2025-07-10 00:00:00','Purchase Entry','2025-07-10 14:24:59','2025-07-10 14:24:59'),
 (2,1,17,'purchase',4,100,0,'2025-07-10 00:00:00','Purchase Entry','2025-07-10 14:45:55','2025-07-10 14:45:55'),
 (3,1,18,'sale',1,0,1,'2025-07-10 17:50:21','Auto stock decrease from delivery','2025-07-10 17:50:21','2025-07-10 17:50:21'),
 (4,1,16,'purchase',5,10,0,'2025-07-10 00:00:00','Purchase Entry','2025-07-10 18:22:09','2025-07-10 18:22:09'),
 (5,1,18,'sale',1,0,1,'2025-07-11 14:54:25','Auto stock decrease from delivery','2025-07-11 14:54:25','2025-07-11 14:54:25'),
 (6,1,1,'sale',6,0,2,'2025-07-11 14:56:05','Auto stock decrease from delivery','2025-07-11 14:56:05','2025-07-11 14:56:05');
/*!40000 ALTER TABLE `ecom_stocks` ENABLE KEYS */;


--
-- Definition of table `ecom_suppliers`
--

DROP TABLE IF EXISTS `ecom_suppliers`;
CREATE TABLE `ecom_suppliers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `company_name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ecom_suppliers`
--

/*!40000 ALTER TABLE `ecom_suppliers` DISABLE KEYS */;
INSERT INTO `ecom_suppliers` (`id`,`name`,`phone`,`email`,`address`,`created_at`,`updated_at`,`company_name`) VALUES 
 (1,'Maruf','+8801*******','ma@gmail.com','175/A, Dhanmondi, Dhaka-1211','2025-07-02 20:20:07','2025-07-02 20:20:07','ABC COMpnay');
/*!40000 ALTER TABLE `ecom_suppliers` ENABLE KEYS */;


--
-- Definition of table `ecom_users`
--

DROP TABLE IF EXISTS `ecom_users`;
CREATE TABLE `ecom_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `full_name` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `photo` varchar(50) DEFAULT NULL,
  `verify_code` varchar(50) DEFAULT NULL,
  `inactive` tinyint(1) unsigned DEFAULT 0,
  `mobile` varchar(50) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `email_verified_at` datetime DEFAULT NULL,
  `remember_token` varchar(145) DEFAULT NULL,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ecom_users`
--

/*!40000 ALTER TABLE `ecom_users` DISABLE KEYS */;
INSERT INTO `ecom_users` (`id`,`role_id`,`password`,`email`,`full_name`,`created_at`,`photo`,`verify_code`,`inactive`,`mobile`,`updated_at`,`ip`,`email_verified_at`,`remember_token`,`name`) VALUES 
 (1,NULL,'$2y$12$nYc48QC8qPnR62N1wJCWNuFcNMvoOxWxBVTMamVHIjSF47lneKD/m','jhkb@gmail.com',NULL,'2025-07-01 10:29:35',NULL,NULL,0,NULL,'2025-07-01 10:29:35',NULL,NULL,NULL,'fish'),
 (2,NULL,'$2y$12$CCSdz3Jpa/RnY/iciaBjm.x1/8I31RFR38Eop060K4ysyYsTZ82fS','fish@gmail.com',NULL,'2025-07-01 11:13:06',NULL,NULL,0,NULL,'2025-07-01 11:13:06',NULL,NULL,NULL,'fish');
/*!40000 ALTER TABLE `ecom_users` ENABLE KEYS */;


--
-- Definition of table `ecom_warehouses`
--

DROP TABLE IF EXISTS `ecom_warehouses`;
CREATE TABLE `ecom_warehouses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `location` varchar(225) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ecom_warehouses`
--

/*!40000 ALTER TABLE `ecom_warehouses` DISABLE KEYS */;
INSERT INTO `ecom_warehouses` (`id`,`name`,`location`,`created_at`,`updated_at`) VALUES 
 (1,'E-commerce2','12/A, Dhanmondi','2025-06-30 01:04:28','2025-06-30 01:04:28');
/*!40000 ALTER TABLE `ecom_warehouses` ENABLE KEYS */;




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
