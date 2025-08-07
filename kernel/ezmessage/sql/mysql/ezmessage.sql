CREATE TABLE eZMessage_Message (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  FromUserID int(11) NOT NULL DEFAULT 0,
  ToUserID int(11) NOT NULL DEFAULT 0,
  Created int(11) NOT NULL DEFAULT 0,
  IsRead int(11) NOT NULL DEFAULT 0,
  TimeRead int(11) NOT NULL DEFAULT 0,
  `Subject` varchar(255) NOT NULL DEFAULT '',
  Description text DEFAULT NULL,
  PRIMARY KEY (`ID`)
);

-- #
-- # add on for major update
-- #
-- ALTER TABLE `eZMessage_Message` ADD `TimeRead` INT( 11 ) NOT NULL AFTER `IsRead` ;

CREATE TABLE eZMessage_MessageDefinition (
  `ID` int(11) NOT NULL DEFAULT 0,
  MessageID int(11) NOT NULL DEFAULT 0,
  ToUserID int(11) NOT NULL DEFAULT 0,
  FromUserID int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`)
);

