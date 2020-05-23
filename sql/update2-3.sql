
ALTER TABLE `RefletorNodeLOG` ADD INDEX(`Talkgroup`);
ALTER TABLE `RefletorNodeLOG` ADD INDEX(`Callsign`); 
ALTER TABLE `RefletorNodeLOG` ADD INDEX(`Type`); 
ALTER TABLE `RefletorStations` ADD INDEX(`Callsign`); 
ALTER TABLE `RefletorNodeLOG` ADD INDEX(`Nodename`); 
ALTER TABLE `RefletorNodeLOG` ADD INDEX(`Time`); 
ALTER TABLE `RefletorStations` ADD INDEX(`Callsign`); 
ALTER TABLE `RefletorNodeLOG` ADD INDEX(`Id`); 


--
-- Tabellstruktur `Settings`
--

CREATE TABLE `Settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `Define` text NOT NULL,
  `value` text NOT NULL,
  `Name` text NOT NULL,
  `type` int(11) NOT NULL,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `users` ADD PRIMARY KEY(`id`); 
ALTER TABLE `users` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT; 


ALTER TABLE `Settings` CHANGE `Define` `Define` VARCHAR(80) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL; 
ALTER TABLE `Settings` ADD INDEX(`Define`); 

INSERT INTO `Settings` (`id`, `Define`, `value`, `Name`, `type`) VALUES (NULL, 'PORTAL_VERSION', '2.3', 'protal version number ', '0'); 
INSERT INTO `Settings` (`id`, `Define`, `value`, `Name`, `type`) VALUES (NULL, 'HIDE_LANGUGE_BAR', '0', 'Hide the languge bar', '1');

INSERT INTO `Settings` (`id`, `Define`, `value`, `Name`, `type`) VALUES (NULL, 'USE_CUSTOM_SIDBAR_HEADER', '0', 'Use Custom header in sidebar', '1'); 

ALTER TABLE `users` ADD `Is_admin` INT NOT NULL AFTER `level`; 

INSERT INTO `Settings` (`id`, `Define`, `value`, `Name`, `type`) VALUES (NULL, 'iframe_documentation_url', 'http://sk3w.se/dokuwiki/doku.php?id=svxreflector&do=export_xhtml', 'External dokumentation page', '2');

INSERT INTO `Settings` (`id`, `Define`, `value`, `Name`, `type`) VALUES (NULL, 'USE_LOGIN', '0', 'Use login for player', '1'); 

INSERT INTO `Settings` (`id`, `Define`, `value`, `Name`, `type`) VALUES (NULL, 'USE_EXTERNAL_URL', '1', 'Use external dokumentation page', '1'); 

INSERT INTO `Settings` (`id`, `Define`, `value`, `Name`, `type`) VALUES (NULL, 'PLAYER_TALKGROUP_DEFAULT', '240', 'The default talkgroup for Recording statistic', '2');

ALTER TABLE `users` ADD `Firstname` VARCHAR(100) NOT NULL AFTER `Is_admin`, ADD `lastname` VARCHAR(100) NOT NULL AFTER `Firstname`; 

ALTER TABLE `RefletorStations` ADD `Last_Seen` DATETIME NULL DEFAULT NULL AFTER `Collor`; 

ALTER TABLE `RefletorStations` ADD `Station_Down` INT NOT NULL AFTER `Last_Seen`; 

INSERT INTO `users` (`Username`, `Password`, `level`, `Is_admin`, `Firstname`, `lastname`) VALUES ('svxportal', MD5('svxportal'), '3', '1', 'svxportal', 'install');

INSERT INTO `Settings` (`id`, `Define`, `value`, `Name`, `type`) VALUES (NULL, 'SEND_MAIL_TO_SYSOP', '0', 'Send an email to sysop when the node goes down', '1'); 

INSERT INTO `Settings` (`id`, `Define`, `value`, `Name`, `type`) VALUES (NULL, 'SYSMATER_MAIL', 'info@a.se', 'Mail to system administrator', '2'); 

INSERT INTO `Settings` (`id`, `Define`, `value`, `Name`, `type`) VALUES (NULL, 'SYSTEM_MAIL', 'info@a.se', 'E-mail adress for the system', '2');

ALTER TABLE `RefletorStations` ADD `Station_Down_timmer_count` INT NOT NULL AFTER `Station_Down`;
