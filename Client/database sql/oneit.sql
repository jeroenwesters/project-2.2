-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Gegenereerd op: 04 feb 2019 om 17:17
-- Serverversie: 5.7.25-0ubuntu0.18.04.2
-- PHP-versie: 7.2.10-0ubuntu0.18.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `oneit`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

CREATE TABLE `users` (
  `userid` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `admin` tinyint(1) NOT NULL,
  `api_key` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `users`
--

INSERT INTO `users` (`userid`, `username`, `password`, `admin`, `api_key`) VALUES
(15, 'testadmin', '$2y$10$9pr.yO7Ub/b18fbY8N2eju1cJVUzGogv9am4/JQ45qUJaeqzsCikO', 1, '!V@TADR#@#FHT#SRGDSC@$'),
(16, 'testuser', '$2y$10$5AUgEz2Qu1Ptuz4cf9ojuerPy94TQ0jmprjUiQuLj1WViyxyL9PYS', 0, '!V@TADR#@#FHT#SRGDSC@$'),
(17, 'free', '$2y$10$.gTgyP5Wnf2/qqLZIPaca.1k09x5pwqE9V0KBCSDK..kXTVLwiT4q', 1, 'asf756saf5asf75a7s6f');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`),
  ADD UNIQUE KEY `userid` (`userid`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `users`
--
ALTER TABLE `users`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
