-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1:3306
-- Üretim Zamanı: 20 May 2024, 17:54:22
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
-- Tablo için tablo yapısı `customeruser`
--

DROP TABLE IF EXISTS `customeruser`;
CREATE TABLE IF NOT EXISTS `customeruser` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(100) COLLATE utf8mb4_turkish_ci NOT NULL,
  `password` varchar(100) COLLATE utf8mb4_turkish_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_turkish_ci NOT NULL,
  `city` varchar(100) COLLATE utf8mb4_turkish_ci NOT NULL,
  `district` varchar(100) COLLATE utf8mb4_turkish_ci NOT NULL,
  `address` varchar(100) COLLATE utf8mb4_turkish_ci NOT NULL,
  `remember` varchar(100) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `type` varchar(50) COLLATE utf8mb4_turkish_ci NOT NULL DEFAULT 'CustomerUser',
  `verificationcode` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `customeruser`
--

INSERT INTO `customeruser` (`id`, `email`, `password`, `name`, `city`, `district`, `address`, `remember`, `type`, `verificationcode`) VALUES
(7, 'ahmetfatih1098@gmail.com', '$2y$10$ZoPZQ.k5wbgLJsuLHdQLu.Tnb3BXWs./9FBSNmEXHeE.oYRSok3du', 'Fatih', 'Ankara', 'Çankaya', 'Bilkent Üni.', NULL, 'CustomerUser', 0),
(8, 'ahmet_fatih1098@outlook.com', '$2y$10$rS/z7shCViziin7/eiierewY6qHaNcAPFf3BDTvKmT9ulB8mZsYCy', 'Ahmet', 'İstanbul', 'Ümraniye', 'Ümraniye/İstanbul', NULL, 'CustomerUser', 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
