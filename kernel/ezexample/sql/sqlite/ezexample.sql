CREATE TABLE eZExample_Test (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Text` varchar(255) DEFAULT NULL,
  Created int(11) DEFAULT NULL);
Create TABLE eZFileManager_File (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` char(200) DEFAULT NULL,
  Description char(200) DEFAULT NULL,
  FileName char(200) DEFAULT NULL,
  OriginalFileName char(200) DEFAULT NULL,
  ReadPermission int(11) DEFAULT 1,
  WritePermission int(11) DEFAULT 1,
  UserID int(11) DEFAULT NULL);

