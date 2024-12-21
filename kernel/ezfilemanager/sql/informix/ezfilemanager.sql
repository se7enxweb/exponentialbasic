CREATE TABLE eZFileManager_File (
  ID int NOT NULL,
  Name varchar(200),
  Description lvarchar,
  FileName varchar(200),
  OriginalFileName varchar(200),
  ReadPermission int,
  WritePermission int,
  UserID int,
  PRIMARY KEY (ID)
);

CREATE TABLE eZFileManager_FileFolderLink (
  ID int NOT NULL,
  FolderID int NOT NULL,
  FileID int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZFileManager_FilePageViewLink (
  ID int NOT NULL,
  PageViewID int NOT NULL,
  FileID int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZFileManager_Folder (
  ID int NOT NULL,
  Name varchar(100),
  Description lvarchar,
  ParentID int NOT NULL,
  ReadPermission int,
  WritePermission int,
  SectionID int,
  UserID int,
  PRIMARY KEY (ID)
);

CREATE TABLE eZFileManager_FolderPermission (
  ID int NOT NULL,
  ObjectID int default NULL,
  GroupID int default NULL,
  ReadPermission int,
  WritePermission int,
  UploadPermission int,
  PRIMARY KEY (ID)
);

CREATE TABLE eZFileManager_FilePermission (
  ID int NOT NULL,
  ObjectID int default NULL,
  GroupID int default NULL,
  ReadPermission int,
  WritePermission int,
  PRIMARY KEY (ID)
);
