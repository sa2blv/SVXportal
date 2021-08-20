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




ALTER TABLE `RefletorNodeLOG` ADD INDEX(`Talktime`); 

ALTER TABLE `Infotmation_page` ADD `GrafanaUrl` TEXT NOT NULL AFTER `Image`; 

ALTER TABLE `Infotmation_page` CHANGE `GrafanaUrl` `GrafanaUrl` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL; 


ALTER TABLE `users` ADD `image_url` VARCHAR(255) NULL DEFAULT NULL AFTER `email`; 

ALTER TABLE `Infotmation_page` ADD `Module` VARCHAR(90) NOT NULL AFTER `Station_id`;

ALTER TABLE `RefletorNodeLOG` CHANGE `Id` `Id` INT(11) NOT NULL AUTO_INCREMENT; 


ALTER TABLE `users` ADD `Reset_token` VARCHAR(99) NOT NULL AFTER `image_url`; 

UPDATE `Settings` SET `value` = '2.5' WHERE `Settings`.`Define` = 'PORTAL_VERSION'; 