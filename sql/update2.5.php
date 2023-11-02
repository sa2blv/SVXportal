/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databas: `svx`
--

-- --------------------------------------------------------

--
-- Tabellstruktur `ReflectorNodeLOG_History`
--

CREATE TABLE `ReflectorNodeLOG_History` (
  `Id` int(11) NOT NULL,
  `Callsign` varchar(40) NOT NULL,
  `Type` int(11) NOT NULL,
  `Active` int(11) NOT NULL,
  `Talkgroup` bigint(20) NOT NULL,
  `NODE` varchar(11) NOT NULL,
  `Siglev` int(11) NOT NULL,
  `Duration` int(11) NOT NULL,
  `Nodename` varchar(80) NOT NULL,
  `IsTalker` int(20) NOT NULL,
  `Time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Talktime` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Index för dumpade tabeller
--

--
-- Index för tabell `ReflectorNodeLOG_History`
--
ALTER TABLE `ReflectorNodeLOG_History`
  ADD PRIMARY KEY (`Id`)

--
-- AUTO_INCREMENT för dumpade tabeller
--

--
-- AUTO_INCREMENT för tabell `ReflectorNodeLOG_History`
--
ALTER TABLE `ReflectorNodeLOG_History`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


ALTER TABLE `RefletorNodeLOG` ADD INDEX(`Talktime`); 

ALTER TABLE `Infotmation_page` ADD `GrafanaUrl` TEXT NOT NULL AFTER `Image`; 

ALTER TABLE `Infotmation_page` CHANGE `GrafanaUrl` `GrafanaUrl` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL; 


ALTER TABLE `users` ADD `image_url` VARCHAR(255) NULL DEFAULT NULL AFTER `email`; 

ALTER TABLE `Infotmation_page` ADD `Module` VARCHAR(90) NOT NULL AFTER `Station_id`;

ALTER TABLE `RefletorNodeLOG` CHANGE `Id` `Id` INT(11) NOT NULL AUTO_INCREMENT; 


ALTER TABLE `users` ADD `Reset_token` VARCHAR(99) NOT NULL AFTER `image_url`; 



CREATE TABLE `Station_day_statistic` (
  `Id` int NOT NULL,
  `Station_id` int NOT NULL,
  `Date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Active_secunds` int NOT NULL,
  `Max_reciver` text NOT NULL,
  `minsiglev` float NOT NULL,
  `avrige` float NOT NULL,
  `maxsiglev` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Statistcs for reciver day by day';

--
-- Index för dumpade tabeller
--

--
-- Index för tabell `Station_day_statistic`
--
ALTER TABLE `Station_day_statistic`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT för dumpade tabeller
--

--
-- AUTO_INCREMENT för tabell `Station_day_statistic`
--
ALTER TABLE `Station_day_statistic`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT;


ALTER TABLE `RefletorStations` CHANGE `ID` `ID` INT NOT NULL AUTO_INCREMENT; 

UPDATE `Settings` SET `value` = '2.5' WHERE `Settings`.`Define` = 'PORTAL_VERSION'; 


