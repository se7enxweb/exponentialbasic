Create TABLE eZFileManager_File (
  ID int(11) DEFAULT '0' NOT NULL,
  Name char(200),
  Description char(200),
  FileName char(200),
  OriginalFileName char(200),
  ReadPermission int(11) DEFAULT '1',
  WritePermission int(11) DEFAULT '1',
  UserID int(11),
  PRIMARY KEY (ID)
);

CREATE TABLE eZFileManager_FileFolderLink (
  ID int(11) DEFAULT '0' NOT NULL,
  FolderID int(11) DEFAULT '0' NOT NULL,
  FileID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZFileManager_FilePageViewLink (
  ID int(11) DEFAULT '0' NOT NULL,
  PageViewID int(11) DEFAULT '0' NOT NULL,
  FileID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZFileManager_Folder (
  ID int(11) DEFAULT '0' NOT NULL,
  Name varchar(100),
  Description text,
  ParentID int(11) DEFAULT '0' NOT NULL,
  ReadPermission int(11) DEFAULT '1',
  WritePermission int(11) DEFAULT '1',
  SectionID int(11) DEFAULT '1',
  UserID int(11),
  PRIMARY KEY (ID)
);

CREATE TABLE eZFileManager_FolderPermission (
  ID int(11) DEFAULT '0' NOT NULL,
  ObjectID int(11) DEFAULT NULL,
  GroupID int(11) DEFAULT NULL,
  ReadPermission int(11) DEFAULT '0',
  WritePermission int(11) DEFAULT '0',
  UploadPermission int(11) DEFAULT '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZFileManager_FilePermission (
  ID int(11) DEFAULT '0' NOT NULL,
  ObjectID int(11) DEFAULT NULL,
  GroupID int(11) DEFAULT NULL,
  ReadPermission int(11) DEFAULT '0',
  WritePermission int(11) DEFAULT '0',
  PRIMARY KEY (ID)
);