-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for genan_db
CREATE DATABASE IF NOT EXISTS `genan_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `genan_db`;

-- Dumping structure for table genan_db.tblanalytics
CREATE TABLE IF NOT EXISTS `tblanalytics` (
  `id` int NOT NULL AUTO_INCREMENT,
  `course` varchar(255) DEFAULT NULL,
  `yearFrom` varchar(50) DEFAULT NULL,
  `yearTo` varchar(50) DEFAULT NULL,
  `reportType` varchar(255) DEFAULT NULL,
  `term` varchar(50) DEFAULT NULL,
  `male` int DEFAULT NULL,
  `female` int DEFAULT NULL,
  `undergrad` int DEFAULT '0',
  `vacant` int DEFAULT '0',
  `createdDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table genan_db.tblanalytics: ~18 rows (approximately)
DELETE FROM `tblanalytics`;
INSERT INTO `tblanalytics` (`id`, `course`, `yearFrom`, `yearTo`, `reportType`, `term`, `male`, `female`, `undergrad`, `vacant`, `createdDate`, `createdBy`) VALUES
	(1, 'Bachelor of Science in Forestry', '2019', '2020', 'graduate', 'First', 2, 1, 0, 0, '2024-10-25 00:26:10', 1),
	(2, 'Bachelor of Science in Forestry', '2019', '2020', 'graduate', 'Second', 21, 21, 0, 0, '2024-10-25 00:27:29', 1),
	(3, 'Bachelor of Science in Civil Engineering', '2019', '2020', 'graduate', 'First', 1, 0, 0, 0, '2024-10-25 00:36:02', 1),
	(4, 'Bachelor of Science in Electrical Engineering', '2019', '2020', 'graduate', 'First', 1, 0, 0, 0, '2024-10-28 02:48:29', 0),
	(5, 'Bachelor of Science in Forestry', '2022', '2023', 'graduate', 'First', 2, 1, 0, 0, '2024-10-28 05:22:27', 0),
	(6, 'Bachelor of Science in Forestry', '2022', '2023', 'graduate', 'Second', 41, 115, 0, 0, '2024-10-28 05:22:55', 0),
	(8, 'Bachelor of Science in Forestry', '2023', '2024', 'graduate', 'First', 4, 4, 0, 0, '2024-11-07 03:59:26', 1),
	(9, 'Bachelor of Science in Forestry', '2023', '2024', 'graduate', 'Second', 11, 44, 0, 0, '2024-11-07 03:59:26', 1),
	(10, 'Bachelor of Science in Civil Engineering', '2023', '2024', 'graduate', 'First', 0, 0, 0, 0, '2024-11-07 03:59:26', 1),
	(11, 'Bachelor of Science in Civil Engineering', '2023', '2024', 'graduate', 'Second', 0, 0, 0, 0, '2024-11-07 03:59:26', 1),
	(12, 'Construction Engineering and Management', '2023', '2024', 'graduate', 'First', 0, 0, 0, 0, '2024-11-07 03:59:26', 1),
	(13, 'Construction Engineering and Management', '2023', '2024', 'graduate', 'Second', 16, 18, 0, 0, '2024-11-07 03:59:26', 1),
	(14, 'Bachelor of Science in Electrical Engineering', '2023', '2024', 'graduate', 'First', 9, 4, 0, 0, '2024-11-07 12:32:23', 1),
	(15, 'Bachelor of Science in Electrical Engineering', '2023', '2024', 'graduate', 'Second', 23, 15, 0, 0, '2024-11-07 12:32:23', 1),
	(16, 'BSA-ANSCI', '2023', '2024', 'enrollment', '1st Year', 60, 26, 0, 0, '2024-11-07 12:37:11', 1),
	(17, 'BSA-ANSCI', '2023', '2024', 'enrollment', '2nd Year', 57, 54, 0, 0, '2024-11-07 12:37:12', 1),
	(18, 'BSA-ANSCI', '2023', '2024', 'enrollment', '3rd Year', 37, 31, 0, 0, '2024-11-07 12:37:12', 1),
	(19, 'BSA-ANSCI', '2023', '2024', 'enrollment', '4th Year', 28, 30, 0, 0, '2024-11-07 12:37:12', 1);

-- Dumping structure for table genan_db.tbldocument_gfps
CREATE TABLE IF NOT EXISTS `tbldocument_gfps` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` text,
  `content` longtext,
  `description` text,
  `createdDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table genan_db.tbldocument_gfps: ~0 rows (approximately)
DELETE FROM `tbldocument_gfps`;

-- Dumping structure for table genan_db.tblevaluation_response
CREATE TABLE IF NOT EXISTS `tblevaluation_response` (
  `id` int NOT NULL AUTO_INCREMENT,
  `eventId` int NOT NULL DEFAULT '0',
  `questionId` int NOT NULL DEFAULT '0',
  `responseCol` varchar(50) NOT NULL DEFAULT '0',
  `schoreCol` varchar(50) NOT NULL DEFAULT '0',
  `remarks` text NOT NULL,
  `createdBy` int NOT NULL DEFAULT '0',
  `createdDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table genan_db.tblevaluation_response: ~0 rows (approximately)
DELETE FROM `tblevaluation_response`;

-- Dumping structure for table genan_db.tblevaluation_templates
CREATE TABLE IF NOT EXISTS `tblevaluation_templates` (
  `id` int NOT NULL AUTO_INCREMENT,
  `questionaire` longtext NOT NULL,
  `maxPoints` int NOT NULL DEFAULT '0',
  `toolTip` longtext NOT NULL,
  `eventId` int NOT NULL DEFAULT '0',
  `createdDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdBy` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table genan_db.tblevaluation_templates: ~0 rows (approximately)
DELETE FROM `tblevaluation_templates`;

-- Dumping structure for table genan_db.tblevents
CREATE TABLE IF NOT EXISTS `tblevents` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `eventType` varchar(50) NOT NULL DEFAULT '0',
  `eventDate` text NOT NULL,
  `fileUploaded` longtext NOT NULL,
  `location` text NOT NULL,
  `createdBy` int NOT NULL DEFAULT '0',
  `createdDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table genan_db.tblevents: ~0 rows (approximately)
DELETE FROM `tblevents`;

-- Dumping structure for table genan_db.tblusers
CREATE TABLE IF NOT EXISTS `tblusers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'Student Number',
  `password` text NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `middleName` varchar(255) NOT NULL,
  `suffix` varchar(255) NOT NULL,
  `sex` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `userType` int NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `isDeleted` int NOT NULL DEFAULT '0',
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table genan_db.tblusers: ~1 rows (approximately)
DELETE FROM `tblusers`;
INSERT INTO `tblusers` (`id`, `username`, `password`, `firstName`, `lastName`, `middleName`, `suffix`, `sex`, `email`, `contact`, `address`, `userType`, `status`, `isDeleted`, `createdAt`, `updatedAt`) VALUES
	(1, 'admin', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'First', 'User', 'A', ' ', 'Male', 'test@mail.com', '09876543211', 'test address', 1, 1, 0, '2024-10-12 14:35:21', '2024-10-12 14:35:21');

-- Dumping structure for table genan_db.tblusertypes
CREATE TABLE IF NOT EXISTS `tblusertypes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `description` varchar(255) NOT NULL,
  `modules` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table genan_db.tblusertypes: ~2 rows (approximately)
DELETE FROM `tblusertypes`;
INSERT INTO `tblusertypes` (`id`, `description`, `modules`) VALUES
	(1, 'Super Admin', '101,102'),
	(2, 'Instructor', '201,202');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
