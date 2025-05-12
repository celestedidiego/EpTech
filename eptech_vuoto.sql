-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mag 12, 2025 alle 12:45
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eptech`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `admin`
--

CREATE TABLE `admin` (
  `adminId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `category`
--

CREATE TABLE `category` (
  `categoryId` int(11) NOT NULL,
  `nameCategory` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `credit_card`
--

CREATE TABLE `credit_card` (
  `registered_user_id` int(11) DEFAULT NULL,
  `cardNumber` varchar(16) NOT NULL,
  `cardHolderName` varchar(100) NOT NULL,
  `endDate` varchar(5) NOT NULL,
  `cvv` varchar(3) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `image`
--

CREATE TABLE `image` (
  `idImage` int(11) NOT NULL,
  `name` varchar(70) DEFAULT NULL,
  `size` int(9) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `imageData` longblob NOT NULL,
  `productId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `itemorder`
--

CREATE TABLE `itemorder` (
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `orders`
--

CREATE TABLE `orders` (
  `shipping_id` int(11) NOT NULL,
  `card_number` varchar(16) DEFAULT NULL,
  `registered_user_id` int(11) NOT NULL,
  `idOrder` int(11) NOT NULL,
  `totalPrice` decimal(10,2) NOT NULL,
  `dateTime` datetime NOT NULL,
  `orderStatus` varchar(50) DEFAULT NULL,
  `qTotalProduct` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `products`
--

CREATE TABLE `products` (
  `category_id` int(11) DEFAULT NULL,
  `productId` int(11) NOT NULL,
  `nameProduct` varchar(255) NOT NULL,
  `priceProduct` decimal(10,2) NOT NULL CHECK (`priceProduct` >= 0),
  `description` text NOT NULL,
  `brand` varchar(255) NOT NULL,
  `model` varchar(255) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `avQuantity` decimal(10,0) NOT NULL CHECK (`avQuantity` >= 0),
  `adminId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `refund_requests`
--

CREATE TABLE `refund_requests` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `status` varchar(20) NOT NULL,
  `requestDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `registered_user`
--

CREATE TABLE `registered_user` (
  `registeredUserId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `birthDate` date NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_blocked` tinyint(1) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `confirmationToken` varchar(64) DEFAULT NULL,
  `emailConfirmed` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `reviews`
--

CREATE TABLE `reviews` (
  `product_id` int(11) NOT NULL,
  `registere_user_id` int(11) NOT NULL,
  `idReview` int(11) NOT NULL,
  `text` text DEFAULT NULL,
  `vote` int(5) DEFAULT NULL,
  `responseAdmin` longtext DEFAULT NULL,
  `adminId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `shipping`
--

CREATE TABLE `shipping` (
  `registered_user_id` int(11) NOT NULL,
  `idShipping` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `cap` varchar(10) NOT NULL,
  `city` varchar(100) NOT NULL,
  `recipientName` varchar(100) NOT NULL,
  `recipientSurname` varchar(100) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `unregistered`
--

CREATE TABLE `unregistered` (
  `UnRegisteredUserId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`adminId`);

--
-- Indici per le tabelle `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`categoryId`),
  ADD UNIQUE KEY `nameCategory` (`nameCategory`);

--
-- Indici per le tabelle `credit_card`
--
ALTER TABLE `credit_card`
  ADD PRIMARY KEY (`cardNumber`),
  ADD KEY `IDX_11D627EEA6A12EC1` (`registered_user_id`);

--
-- Indici per le tabelle `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`idImage`),
  ADD KEY `IDX_C53D045F36799605` (`productId`);

--
-- Indici per le tabelle `itemorder`
--
ALTER TABLE `itemorder`
  ADD PRIMARY KEY (`order_id`,`product_id`),
  ADD KEY `IDX_603CA7788D9F6D38` (`order_id`),
  ADD KEY `IDX_603CA7784584665A` (`product_id`);

--
-- Indici per le tabelle `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`idOrder`),
  ADD KEY `IDX_E52FFDEE4887F3F8` (`shipping_id`),
  ADD KEY `IDX_E52FFDEEE4AF4C20` (`card_number`),
  ADD KEY `IDX_E52FFDEEA6A12EC1` (`registered_user_id`);

--
-- Indici per le tabelle `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`productId`),
  ADD KEY `IDX_B3BA5A5A12469DE2` (`category_id`),
  ADD KEY `IDX_B3BA5A5A2D696931` (`adminId`);

--
-- Indici per le tabelle `refund_requests`
--
ALTER TABLE `refund_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_A6AE4528D9F6D38` (`order_id`);

--
-- Indici per le tabelle `registered_user`
--
ALTER TABLE `registered_user`
  ADD PRIMARY KEY (`registeredUserId`);

--
-- Indici per le tabelle `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`idReview`),
  ADD KEY `IDX_6970EB0F4584665A` (`product_id`),
  ADD KEY `IDX_6970EB0F9276297C` (`registere_user_id`),
  ADD KEY `IDX_6970EB0F2D696931` (`adminId`);

--
-- Indici per le tabelle `shipping`
--
ALTER TABLE `shipping`
  ADD PRIMARY KEY (`idShipping`),
  ADD KEY `IDX_2D1C1724A6A12EC1` (`registered_user_id`);

--
-- Indici per le tabelle `unregistered`
--
ALTER TABLE `unregistered`
  ADD PRIMARY KEY (`UnRegisteredUserId`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `admin`
--
ALTER TABLE `admin`
  MODIFY `adminId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `category`
--
ALTER TABLE `category`
  MODIFY `categoryId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `image`
--
ALTER TABLE `image`
  MODIFY `idImage` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `orders`
--
ALTER TABLE `orders`
  MODIFY `idOrder` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `products`
--
ALTER TABLE `products`
  MODIFY `productId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `refund_requests`
--
ALTER TABLE `refund_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `registered_user`
--
ALTER TABLE `registered_user`
  MODIFY `registeredUserId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `reviews`
--
ALTER TABLE `reviews`
  MODIFY `idReview` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `shipping`
--
ALTER TABLE `shipping`
  MODIFY `idShipping` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `unregistered`
--
ALTER TABLE `unregistered`
  MODIFY `UnRegisteredUserId` int(11) NOT NULL AUTO_INCREMENT;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `credit_card`
--
ALTER TABLE `credit_card`
  ADD CONSTRAINT `FK_11D627EEA6A12EC1` FOREIGN KEY (`registered_user_id`) REFERENCES `registered_user` (`registeredUserId`);

--
-- Limiti per la tabella `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `FK_C53D045F36799605` FOREIGN KEY (`productId`) REFERENCES `products` (`productId`);

--
-- Limiti per la tabella `itemorder`
--
ALTER TABLE `itemorder`
  ADD CONSTRAINT `FK_603CA7784584665A` FOREIGN KEY (`product_id`) REFERENCES `products` (`productId`),
  ADD CONSTRAINT `FK_603CA7788D9F6D38` FOREIGN KEY (`order_id`) REFERENCES `orders` (`idOrder`);

--
-- Limiti per la tabella `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `FK_E52FFDEE4887F3F8` FOREIGN KEY (`shipping_id`) REFERENCES `shipping` (`idShipping`),
  ADD CONSTRAINT `FK_E52FFDEEA6A12EC1` FOREIGN KEY (`registered_user_id`) REFERENCES `registered_user` (`registeredUserId`),
  ADD CONSTRAINT `FK_E52FFDEEE4AF4C20` FOREIGN KEY (`card_number`) REFERENCES `credit_card` (`cardNumber`);

--
-- Limiti per la tabella `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `FK_B3BA5A5A12469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`categoryId`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_B3BA5A5A2D696931` FOREIGN KEY (`adminId`) REFERENCES `admin` (`adminId`) ON DELETE CASCADE;

--
-- Limiti per la tabella `refund_requests`
--
ALTER TABLE `refund_requests`
  ADD CONSTRAINT `FK_A6AE4528D9F6D38` FOREIGN KEY (`order_id`) REFERENCES `orders` (`idOrder`);

--
-- Limiti per la tabella `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `FK_6970EB0F2D696931` FOREIGN KEY (`adminId`) REFERENCES `admin` (`adminId`),
  ADD CONSTRAINT `FK_6970EB0F4584665A` FOREIGN KEY (`product_id`) REFERENCES `products` (`productId`),
  ADD CONSTRAINT `FK_6970EB0F9276297C` FOREIGN KEY (`registere_user_id`) REFERENCES `registered_user` (`registeredUserId`);

--
-- Limiti per la tabella `shipping`
--
ALTER TABLE `shipping`
  ADD CONSTRAINT `FK_2D1C1724A6A12EC1` FOREIGN KEY (`registered_user_id`) REFERENCES `registered_user` (`registeredUserId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
