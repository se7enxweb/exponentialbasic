Create TABLE eZFileManager_File (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` char(200) DEFAULT NULL,
  Description char(200) DEFAULT NULL,
  FileName char(200) DEFAULT NULL,
  OriginalFileName char(200) DEFAULT NULL,
  ReadPermission int(11) DEFAULT 1,
  WritePermission int(11) DEFAULT 1,
  UserID int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZFileManager_FileFolderLink (
 `ID` int(11) NOT NULL DEFAULT 0,
  FolderID int(11) NOT NULL DEFAULT 0,
  FileID int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZFileManager_FilePageViewLink (
  `ID` int(11) NOT NULL DEFAULT 0,
  PageViewID int(11) NOT NULL DEFAULT 0,
  FileID int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZFileManager_FolderPermission (
  `ID` int(11) NOT NULL DEFAULT 0,
  ObjectID int(11) DEFAULT NULL,
  GroupID int(11) DEFAULT NULL,
  ReadPermission int(11) DEFAULT 0,
  WritePermission int(11) DEFAULT 0,
  UploadPermission int(11) DEFAULT 0,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZFileManager_Folder (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(100) DEFAULT NULL,
  Description text DEFAULT NULL,
  ParentID int(11) NOT NULL DEFAULT 0,
  ReadPermission int(11) DEFAULT 1,
  WritePermission int(11) DEFAULT 1,
  SectionID int(11) DEFAULT 1,
  UserID int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZFileManager_FilePermission (

  `ID` int(11) NOT NULL DEFAULT 0,
  `ObjectID` int(11) DEFAULT NULL,
  `GroupID` int(11) DEFAULT NULL,
  `ReadPermission` int(11) DEFAULT 0,
  `WritePermission` int(11) DEFAULT 0,
  `UploadPermission` int(11) DEFAULT 0,
  PRIMARY KEY (`ID`)
);