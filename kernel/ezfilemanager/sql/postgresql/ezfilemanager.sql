CREATE TABLE eZFileManager_File (
  ID int DEFAULT '0' NOT NULL,
  Name varchar(200),
  Description varchar(200),
  FileName varchar(200),
  OriginalFileName varchar(200),
  ReadPermission int DEFAULT '1',
  WritePermission int DEFAULT '1',
  UserID int,
  PRIMARY KEY (ID)
);

CREATE TABLE eZFileManager_FileFolderLink (
  ID int DEFAULT '0' NOT NULL,
  FolderID int DEFAULT '0' NOT NULL,
  FileID int DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZFileManager_FilePageViewLink (
  ID int DEFAULT '0' NOT NULL,
  PageViewID int DEFAULT '0' NOT NULL,
  FileID int DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZFileManager_Folder (
  ID int DEFAULT '0' NOT NULL,
  Name varchar(100),
  Description text,
  ParentID int NOT NULL DEFAULT '0',
  ReadPermission int DEFAULT '1',
  WritePermission int DEFAULT '1',
  SectionID int DEFAULT '1',
  UserID int,
  PRIMARY KEY (ID)
);

CREATE TABLE eZFileManager_FolderPermission (
  ID int DEFAULT '0' NOT NULL,
  ObjectID int DEFAULT NULL,
  GroupID int DEFAULT NULL,
  ReadPermission int DEFAULT '0',
  WritePermission int DEFAULT '0',
  UploadPermission int DEFAULT '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZFileManager_FilePermission (
  ID int DEFAULT '0' NOT NULL,
  ObjectID int DEFAULT NULL,
  GroupID int DEFAULT NULL,
  ReadPermission int DEFAULT '0',
  WritePermission int DEFAULT '0',
  PRIMARY KEY (ID)
);

