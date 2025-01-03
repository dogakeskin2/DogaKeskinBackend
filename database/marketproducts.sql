-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1:3306
-- Üretim Zamanı: 20 May 2024, 17:54:27
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
-- Tablo için tablo yapısı `marketproducts`
--

DROP TABLE IF EXISTS `marketproducts`;
CREATE TABLE IF NOT EXISTS `marketproducts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(100) COLLATE utf8mb4_turkish_ci NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_turkish_ci NOT NULL,
  `stock` int NOT NULL,
  `normalprice` decimal(10,2) NOT NULL,
  `discountedprice` decimal(10,2) NOT NULL,
  `expirationdate` date NOT NULL,
  `image` varchar(100) COLLATE utf8mb4_turkish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `marketproducts`
--

INSERT INTO `marketproducts` (`id`, `email`, `title`, `stock`, `normalprice`, `discountedprice`, `expirationdate`, `image`) VALUES
(62, 'ahmetfatih1098@icloud.com', 'Şeftali', 250, 15.00, 7.00, '2024-05-30', '4f55583ba81d33ac05f665c7bee33a7318bc1f7f.jpeg'),
(61, 'fatih.senelmis@ug.bilkent.edu.tr', 'Tadelle', 200, 30.00, 10.00, '2024-06-01', 'e24649c6f68a8a4cb05caab70a57c17dbd057705.jpg'),
(60, 'fatih.senelmis@ug.bilkent.edu.tr', 'Magnum Duble', 123, 25.00, 5.00, '2024-05-24', '26e1b4fdd4704200ddf489a635e9a8edc0ef78e0.jpg'),
(59, 'fatih.senelmis@ug.bilkent.edu.tr', 'Çilek', 200, 25.00, 10.00, '2024-06-01', '06f8c2cdf0b1bc90f99dd26c6c8a707da2b8a65f.jpeg'),
(57, 'fatih.senelmis@ug.bilkent.edu.tr', 'Mango', 150, 30.00, 15.00, '2024-05-31', 'f628058c35a894fb4918b90c8ff804bd737bad33.jpg'),
(58, 'fatih.senelmis@ug.bilkent.edu.tr', 'Ekmek', 100, 10.00, 1.00, '2024-05-01', '75a2a239455c63c069bf1fba3c38355169fb9a03.png'),
(63, 'ahmetfatih1098@icloud.com', 'Schweppes', 54, 34.00, 14.00, '2024-06-08', '2c04c0d6b041203d179d40e1a6e31cecf78202b2.jpg'),
(64, 'ahmetfatih1098@icloud.com', 'Nescafe', 100, 33.00, 13.00, '2024-05-19', '6f41eb7e274d5c099a80b1e604156804895791e4.jpg'),
(65, 'ahmetfatih1098@icloud.com', 'Muz', 103, 53.00, 33.00, '2024-06-09', 'a0094a9c27e515fcdda323f06d3463f61c0407d3.jpeg'),
(66, 'ahmetfatih1098@icloud.com', 'Pringles', 98, 18.00, 5.00, '2024-06-10', 'fbacbe4e394ac230a233329f5300b5e2da7a4de9.jpg'),
(67, 'fatih.senelmis@ug.bilkent.edu.tr', 'Çay', 13, 50.00, 12.00, '2024-06-06', 'a5a78c9400115b1994b8208dc02a4a9a3711a18a.png'),
(68, 'fatih.senelmis@ug.bilkent.edu.tr', 'Toblerone', 55, 32.00, 13.00, '2024-06-09', '377e38bd2eef588707a11401383a7a9c2ed1f0f2.jpg'),
(69, 'ahmetfatih1098@icloud.com', 'Domates', 32, 14.00, 3.00, '2024-05-23', '7db8421b27a55150207f7e991d27eca4fd7cd62e.jpg'),
(70, 'fatih.senelmis@ug.bilkent.edu.tr', 'Et', 500, 100.00, 30.00, '2024-06-02', '8040b6799acf71c624e062e80019058da425f3da.jpg');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
