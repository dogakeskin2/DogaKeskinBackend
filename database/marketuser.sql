-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1:3306
-- Üretim Zamanı: 20 May 2024, 17:54:30
-- Sunucu sürümü: 8.2.0
-- PHP Sürümü: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `test`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `marketuser`
--

DROP TABLE IF EXISTS `marketuser`;
CREATE TABLE IF NOT EXISTS `marketuser` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(100) COLLATE utf8mb4_turkish_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_turkish_ci NOT NULL,
  `password` varchar(100) COLLATE utf8mb4_turkish_ci NOT NULL,
  `city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `district` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `address` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `remember` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `type` varchar(50) COLLATE utf8mb4_turkish_ci NOT NULL DEFAULT 'MarketUser',
  `verificationcode` int NOT NULL,
  `verified` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `marketuser`
--

INSERT INTO `marketuser` (`id`, `email`, `name`, `password`, `city`, `district`, `address`, `remember`, `type`, `verificationcode`, `verified`) VALUES
(64, 'fatih.senelmis@ug.bilkent.edu.tr', 'Bilkent Market', '$2y$10$NrJmjbB272m8VArTgvTaWOFUJt4TrBiVavkYFYjnBgVjw9hfZIrre', 'Ankara', 'Çankaya', 'Bilkent Üni.', NULL, 'MarketUser', 963838, 0),
(65, 'ahmetfatih1098@icloud.com', 'İstanbul Market', '$2y$10$DH08GVCPob6pMm2UPpT1ku7/PhAij8BN50hkVw9Oc4qF3gPuE4/5m', 'İstanbul', 'Beşiktaş', 'Beşiktaş/İstanbul', NULL, 'MarketUser', 581209, 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
