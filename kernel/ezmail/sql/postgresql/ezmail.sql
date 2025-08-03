CREATE TABLE eZMail_Mail (
  ID int NOT NULL,
  UserID int default '0', 
  ToField varchar(100),
  FromField varchar(100),
  FromName varchar(100),
  Cc varchar(255),
  Bcc varchar(255),
  MessageID varchar(200),
  Reference varchar(100),
  ReplyTo varchar(100),
  Subject varchar(255),
  BodyText text,
  Status int default '0' NOT NULL,
  Size int default '0',
  UDate int default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZMail_Account (
  ID int NOT NULL,
  UserID int default '0',
  Name varchar(200) default NULL,
  LoginName varchar(100),
  Password varchar(50),
  Server varchar(150),
  ServerPort int default '0',
  DeleteFromServer int default '1',
  ServerType int, 
  IsActive int default '0' NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZMail_Folder (
  ID int NOT NULL,
  UserID int default '0',
  ParentID int default '0',
  Name varchar(200) default NULL,
  FolderType int, 
  PRIMARY KEY (ID)
);

CREATE TABLE eZMail_MailFolderLink (
  MailID int NOT NULL default '0',
  FolderID int NOT NULL default '0',
  PRIMARY KEY (MailID,FolderID)
);

CREATE TABLE eZMail_MailAttachmentLink (
  MailID int NOT NULL default '0',
  FileID int NOT NULL default '0',
  PRIMARY KEY (MailID,FileID)
);

CREATE TABLE eZMail_MailImageLink (
  MailID int NOT NULL default '0',
  ImageID int NOT NULL default '0',
  PRIMARY KEY (MailID,ImageID)
);

CREATE TABLE eZMail_FilterRule (
  ID int NOT NULL,
  UserID int NOT NULL default '0',
  FolderID int NOT NULL default '0',
  HeaderType int default '0',
  CheckType int default '0',
  MatchValue varchar(200),
  IsActive int default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZMail_FetchedMail (
  UserID int NOT NULL,
  MessageID varchar(100) NOT NULL,
  PRIMARY KEY (UserID, MessageID)
);

CREATE TABLE eZMail_MailContactLink (
  ID int NOT NULL,
  MailID int NOT NULL default '0',
  PersonID int,
  CompanyID int,
  PRIMARY KEY (ID)
);
