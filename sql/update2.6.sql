-- --------------------------------------------------------

--
-- Tabellstruktur `Refletor_station_state`
--

CREATE TABLE `Refletor_station_state` (
  `ID` int NOT NULL,
  `Callsign` varchar(50) NOT NULL,
  `Current_start` timestamp NOT NULL,
  `Current_stop` timestamp NOT NULL,
  `action` int NOT NULL,
  `tg` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Tabellstruktur `Station_day_statistic`
--

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

-- --------------------------------------------------------

--
-- Tabellstruktur `trafic_day_statistics`
--

CREATE TABLE `trafic_day_statistics` (
  `id` int NOT NULL,
  `Node` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `TG` varchar(20) NOT NULL,
  `Year` varchar(12) NOT NULL,
  `Mounth` varchar(12) NOT NULL,
  `Day` varchar(12) NOT NULL,
  `Timestamp` timestamp NOT NULL,
  `00_N` float NOT NULL,
  `00_X2` float NOT NULL,
  `00_T` float NOT NULL,
  `01_N` float NOT NULL,
  `01_X2` float NOT NULL,
  `01_T` float NOT NULL,
  `02_N` float NOT NULL,
  `02_X2` float NOT NULL,
  `02_T` float NOT NULL,
  `03_N` float NOT NULL,
  `03_X2` float NOT NULL,
  `03_T` float NOT NULL,
  `04_N` float NOT NULL,
  `04_X2` float NOT NULL,
  `04_T` float NOT NULL,
  `05_N` float NOT NULL,
  `05_X2` float NOT NULL,
  `05_T` float NOT NULL,
  `06_N` float NOT NULL,
  `06_X2` float NOT NULL,
  `06_T` float NOT NULL,
  `07_N` float NOT NULL,
  `07_X2` float NOT NULL,
  `07_T` float NOT NULL,
  `08_N` float NOT NULL,
  `08_X2` float NOT NULL,
  `08_T` float NOT NULL,
  `09_N` float NOT NULL,
  `09_X2` float NOT NULL,
  `09_T` float NOT NULL,
  `10_N` float NOT NULL,
  `10_X2` float NOT NULL,
  `10_T` float NOT NULL,
  `11_N` float NOT NULL,
  `11_X2` float NOT NULL,
  `11_T` float NOT NULL,
  `12_N` float NOT NULL,
  `12_X2` float NOT NULL,
  `12_T` float NOT NULL,
  `13_N` float NOT NULL,
  `13_X2` float NOT NULL,
  `13_T` float NOT NULL,
  `14_N` float NOT NULL,
  `14_X2` float NOT NULL,
  `14_T` float NOT NULL,
  `15_N` float NOT NULL,
  `15_X2` float NOT NULL,
  `15_T` float NOT NULL,
  `16_N` float NOT NULL,
  `16_X2` float NOT NULL,
  `16_T` float NOT NULL,
  `17_N` float NOT NULL,
  `17_X2` float NOT NULL,
  `17_T` float NOT NULL,
  `18_N` float NOT NULL,
  `18_X2` float NOT NULL,
  `18_T` float NOT NULL,
  `19_N` float NOT NULL,
  `19_X2` float NOT NULL,
  `19_T` float NOT NULL,
  `20_N` float NOT NULL,
  `20_X2` float NOT NULL,
  `20_T` float NOT NULL,
  `21_N` float NOT NULL,
  `21_X2` float NOT NULL,
  `21_T` float NOT NULL,
  `22_N` float NOT NULL,
  `22_X2` float NOT NULL,
  `22_T` float NOT NULL,
  `23_N` float NOT NULL,
  `23_X2` float NOT NULL,
  `23_T` float NOT NULL,
  `Total_N` float NOT NULL,
  `Total_X2` float NOT NULL,
  `Total_T` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Tabellstruktur `trafic_mounth_statistics`
--

CREATE TABLE `trafic_mounth_statistics` (
  `id` int NOT NULL,
  `Node` varchar(50) NOT NULL,
  `TG` varchar(20) NOT NULL,
  `Year` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `TOT_N` float NOT NULL,
  `TOT_X2` float NOT NULL,
  `TOT_T` float NOT NULL,
  `12_N` float NOT NULL,
  `12_X2` float NOT NULL,
  `12_T` float NOT NULL,
  `11_N` float NOT NULL,
  `11_X2` float NOT NULL,
  `11_T` float NOT NULL,
  `10_N` float NOT NULL,
  `10_X2` float NOT NULL,
  `10_T` float NOT NULL,
  `9_N` float NOT NULL,
  `9_X2` float NOT NULL,
  `9_T` float NOT NULL,
  `8_N` float NOT NULL,
  `8_X2` float NOT NULL,
  `8_T` float NOT NULL,
  `7_N` float NOT NULL,
  `7_X2` float NOT NULL,
  `7_T` float NOT NULL,
  `6_N` float NOT NULL,
  `6_X2` float NOT NULL,
  `6_T` float NOT NULL,
  `5_N` float NOT NULL,
  `5_X2` float NOT NULL,
  `5_T` float NOT NULL,
  `4_N` float NOT NULL,
  `4_X2` float NOT NULL,
  `4_T` float NOT NULL,
  `3_N` float NOT NULL,
  `3_X2` float NOT NULL,
  `3_T` float NOT NULL,
  `2_N` float NOT NULL,
  `2_X2` float NOT NULL,
  `2_T` float NOT NULL,
  `1_N` float NOT NULL,
  `1_X2` float NOT NULL,
  `1_T` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Index för dumpade tabeller
--

--
-- Index för tabell `Refletor_station_state`
--
ALTER TABLE `Refletor_station_state`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Callsign` (`Callsign`);

--
-- Index för tabell `Station_day_statistic`
--
ALTER TABLE `Station_day_statistic`
  ADD PRIMARY KEY (`Id`);

--
-- Index för tabell `trafic_day_statistics`
--
ALTER TABLE `trafic_day_statistics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Node` (`Node`),
  ADD KEY `TG` (`TG`),
  ADD KEY `Year` (`Year`),
  ADD KEY `Mounth` (`Mounth`),
  ADD KEY `Day` (`Day`);

--
-- Index för tabell `trafic_mounth_statistics`
--
ALTER TABLE `trafic_mounth_statistics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Node` (`Node`),
  ADD KEY `TG` (`TG`),
  ADD KEY `Year` (`Year`);

--
-- AUTO_INCREMENT för dumpade tabeller
--

--
-- AUTO_INCREMENT för tabell `Refletor_station_state`
--
ALTER TABLE `Refletor_station_state`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT för tabell `Station_day_statistic`
--
ALTER TABLE `Station_day_statistic`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT för tabell `trafic_day_statistics`
--
ALTER TABLE `trafic_day_statistics`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT för tabell `trafic_mounth_statistics`
--
ALTER TABLE `trafic_mounth_statistics`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;


UPDATE `Settings` SET `value` = '2.6B' WHERE `Settings`.`id` = 1; 

