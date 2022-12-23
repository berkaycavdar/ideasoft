-- PHP Sürümü: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `ideasoft`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `customer`
--

CREATE TABLE `customer` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(250) NOT NULL,
  `since` varchar(20) NOT NULL,
  `revenue` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Tablo döküm verisi `customer`
--

INSERT INTO `customer` (`id`, `name`, `since`, `revenue`) VALUES
(1, 'Türker Jöntürk', '2014-06-28', '492.12'),
(2, 'Kaptan Devopuz', '2015-01-15', '1505.95'),
(3, 'İsa Sonuyumaz', '2016-02-11', '0.00');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `item`
--

CREATE TABLE `item` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(250) NOT NULL,
  `category` int(11) NOT NULL,
  `price` varchar(250) NOT NULL,
  `stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Tablo döküm verisi `item`
--

INSERT INTO `item` (`id`, `name`, `category`, `price`, `stock`) VALUES
(1, 'Black&Decker A7062 40 Parça Cırcırlı Tornavida Seti', 1, '120.75', 146),
(2, 'Reko Mini Tamir Hassas Tornavida Seti 32\'li', 1, '49.50', 187),
(3, 'Viko Karre Anahtar - Beyaz', 1, '11.28', 160),
(4, 'Legrand Salbei Anahtar, Alüminyum', 2, '22.80', 196),
(5, 'Schneider Asfora Beyaz Komütatör', 2, '12.95', 160);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `orders`
--

CREATE TABLE `orders` (
  `id` int(11) UNSIGNED NOT NULL,
  `order_id` varchar(20) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `item_cat` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `time_stamps` varchar(20) NOT NULL,
  `status` int(11) NOT NULL,
  `count` varchar(250) NOT NULL,
  `indirim` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Tablo döküm verisi `orders`
--

INSERT INTO `orders` (`id`, `order_id`, `customer_id`, `item_id`, `item_cat`, `quantity`, `time_stamps`, `status`, `count`, `indirim`) VALUES
(1, 'IDEA-93918', 1, 1, 1, 10, '1671786814', 0, '966', '20'),
(2, 'IDEA-90287', 2, 1, 1, 2, '1671786814', 0, '193.2', '20'),
(3, 'IDEA-90287', 2, 2, 1, 1, '1671786814', 0, '49.5', NULL),
(4, 'IDEA-90174', 3, 2, 1, 6, '1671786814', 0, '237.6', '20'),
(5, 'IDEA-90174', 3, 3, 1, 10, '1671786814', 0, '90.24', '20'),
(6, 'IDEA-95956', 1, 4, 2, 6, '1671786814', 0, '114', '1'),
(7, 'IDEA-95956', 1, 5, 2, 10, '1671786814', 0, '129.5', NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `order_total`
--

CREATE TABLE `order_total` (
  `id` int(11) NOT NULL,
  `order_id` varchar(250) NOT NULL,
  `total_count` varchar(250) NOT NULL,
  `indirim` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Tablo döküm verisi `order_total`
--

INSERT INTO `order_total` (`id`, `order_id`, `total_count`, `indirim`) VALUES
(1, 'IDEA-93918', '1086.75', '10'),
(2, 'IDEA-90287', '291', '0'),
(3, 'IDEA-90174', '409.8', '0'),
(4, 'IDEA-95956', '266.3', '0');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `order_total`
--
ALTER TABLE `order_total`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Tablo için AUTO_INCREMENT değeri `item`
--
ALTER TABLE `item`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Tablo için AUTO_INCREMENT değeri `order_total`
--
ALTER TABLE `order_total`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
