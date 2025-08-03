CREATE TABLE eZMail_Mail (
  ID int(11) NOT NULL,
  UserID int(11) default '0', 
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
  Status int(1) default '0' NOT NULL,
  Size int(11) default '0',
  UDate int(15) default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZMail_Account (
  ID int(11) NOT NULL,
  UserID int(11) default '0',
  Name varchar(200) default NULL,
  LoginName varchar(100),
  Password varchar(50),
  Server varchar(150),
  ServerPort int(5) default '0',
  DeleteFromServer int(1) default '1',
  ServerType int(2), 
  IsActive int(1) default '0' NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZMail_Folder (
  ID int(11) NOT NULL,
  UserID int(11) default '0',
  ParentID int(11) default '0',
  Name varchar(200) default NULL,
  FolderType int(2), 
  PRIMARY KEY (ID)
);

CREATE TABLE eZMail_MailFolderLink (
  MailID int(11) NOT NULL default '0',
  FolderID int(11) NOT NULL default '0',
  PRIMARY KEY (MailID,FolderID)
);

CREATE TABLE eZMail_MailAttachmentLink (
  MailID int(11) NOT NULL default '0',
  FileID int(11) NOT NULL default '0',
  PRIMARY KEY (MailID,FileID)
);

CREATE TABLE eZMail_MailImageLink (
  MailID int(11) NOT NULL default '0',
  ImageID int(11) NOT NULL default '0',
  PRIMARY KEY (MailID,ImageID)
);

CREATE TABLE eZMail_FilterRule (
  ID int(11) NOT NULL,
  UserID int(11) NOT NULL default '0',
  FolderID int(11) NOT NULL default '0',
  HeaderType int(2) default '0',
  CheckType int(2) default '0',
  MatchValue varchar(200),
  IsActive int(1) default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZMail_FetchedMail (
  UserID int(11) NOT NULL,
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