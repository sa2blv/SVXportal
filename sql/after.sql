UPDATE `Settings` SET `type` = '3' WHERE `Settings`.`id` = 1; 
UPDATE `Settings` SET `value` = '2.6' WHERE `Settings`.`id` = 1; 

ALTER TABLE `RefletorStations` DROP INDEX `Callsign_2`;

TRUNCATE `RefletorStations`;
ALTER TABLE `RefletorStations` CHANGE `ID` `ID` INT NOT NULL AUTO_INCREMENT; 

ALTER TABLE `RefletorStations` ADD `Version` VARCHAR(200) NOT NULL AFTER `Last_Seen`; 
ALTER TABLE `RefletorStations` ADD `Sysop` VARCHAR(200) NOT NULL AFTER `Collor`; 