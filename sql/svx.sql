-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Värd: localhost
-- Tid vid skapande: 03 nov 2019 kl 21:00
-- Serverversion: 5.7.27-0ubuntu0.18.04.1
-- PHP-version: 7.2.24-0ubuntu0.18.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databas: `svx`
--

-- --------------------------------------------------------

--
-- Tabellstruktur `covrige`
--

CREATE TABLE `covrige` (
  `Id` int(11) NOT NULL,
  `Name` text NOT NULL,
  `Radiomobilestring` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `covrige`
--


-- --------------------------------------------------------

--
-- Tabellstruktur `Daylog`
--

CREATE TABLE `Daylog` (
  `ID` int(11) NOT NULL,
  `Repeater` int(11) NOT NULL,
  `Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `Daylog`
--



-- --------------------------------------------------------

--
-- Tabellstruktur `Filter`
--

CREATE TABLE `Filter` (
  `id` int(11) NOT NULL,
  `JSON` text NOT NULL,
  `Filter` text NOT NULL,
  `Namn` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `Filter`
--



-- --------------------------------------------------------

--
-- Tabellstruktur `repeater`
--

CREATE TABLE `repeater` (
  `id` int(11) NOT NULL,
  `Openings` int(11) NOT NULL,
  `Nag` int(11) NOT NULL,
  `Name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `repeater`
--



-- --------------------------------------------------------

--
-- Tabellstruktur `Talkgroup`
--

CREATE TABLE `Talkgroup` (
  `ID` int(11) NOT NULL,
  `TG` int(11) NOT NULL,
  `TXT` text NOT NULL,
  `Collor` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `Talkgroup`
--


--
-- Index för dumpade tabeller
--

--
-- Index för tabell `covrige`
--
ALTER TABLE `covrige`
  ADD PRIMARY KEY (`Id`);

--
-- Index för tabell `Daylog`
--
ALTER TABLE `Daylog`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `ID` (`ID`);

--
-- Index för tabell `Filter`
--
ALTER TABLE `Filter`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `repeater`
--
ALTER TABLE `repeater`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `Talkgroup`
--
ALTER TABLE `Talkgroup`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT för dumpade tabeller
--

--
-- AUTO_INCREMENT för tabell `covrige`
--
ALTER TABLE `covrige`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT för tabell `Daylog`
--
ALTER TABLE `Daylog`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=727;
--
-- AUTO_INCREMENT för tabell `Filter`
--
ALTER TABLE `Filter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT för tabell `Talkgroup`
--
ALTER TABLE `Talkgroup`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
