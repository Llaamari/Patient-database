-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 03.11.2023 klo 14:04
-- Palvelimen versio: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `potilastietokanta`
--

-- --------------------------------------------------------

--
-- Rakenne taululle `patients`
--

CREATE TABLE `patients` (
  `id2` int(11) NOT NULL,
  `id` int(11) DEFAULT NULL,
  `socialsecuritynumber` varchar(50) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `age` int(2) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vedos taulusta `patients`
--

INSERT INTO `patients` (`id2`, `id`, `socialsecuritynumber`, `lastname`, `firstname`, `age`, `user_id`) VALUES
(36, NULL, '150395-123A', 'SimilÃ¤', 'Laura', 28, 1),
(37, NULL, '010190-123A', 'Virtanen', 'Anna', 45, 1),
(38, NULL, '151285-456B', 'MÃ¤kinen', 'Jari', 32, 1),
(39, NULL, '030393-789C', 'Nieminen', 'Laura', 27, 1),
(40, NULL, '240497-234D', 'Korpela', 'Antti', 24, 1),
(41, NULL, '121276-567E', 'Lehtonen', 'Sari', 45, 1),
(42, NULL, '090572-890F', 'Lahtinen', 'Juha', 36, 3),
(43, NULL, '210685-123G', 'Rantanen', 'Maria', 36, 3),
(44, NULL, '150198-456H', 'HeikkilÃ¤', 'Matti', 23, 3),
(46, NULL, '1111111', 'Tuntematon', 'Tiina', 45, 1);

-- --------------------------------------------------------

--
-- Rakenne taululle `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vedos taulusta `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'laura', '$2y$10$hD//DFG9XthBdDFdNV/QlO.TTpm6eUvJ2AxE3hUZ4ZlHHaNqAZ3L.', '2023-09-07 08:42:15'),
(3, 'marianne', '$2y$10$Dn0z2YeuKcvclnSS2T6PiOt8ReOZo23wvv9Js2Fl1wH6pCMhuGCPy', '2023-09-07 12:43:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id2`),
  ADD KEY `id` (`id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id2` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Rajoitteet vedostauluille
--

--
-- Rajoitteet taululle `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `patients_ibfk_1` FOREIGN KEY (`id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
