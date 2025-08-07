CREATE TABLE eZMail_Account (
  `ID` int(11) NOT NULL DEFAULT 0,
  UserID int(11) DEFAULT 0,
  `Name` varchar(200) DEFAULT NULL,
  LoginName varchar(100) DEFAULT NULL,
  `Password` varchar(50) DEFAULT NULL,
  `Server` varchar(150) DEFAULT NULL,
  ServerPort int(5) DEFAULT 0,
  DeleteFromServer int(1) DEFAULT 1,
  ServerType int(2) DEFAULT NULL,
  IsActive int(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZMail_FetchedMail (
  UserID int(11) NOT NULL DEFAULT 0,
  MessageID varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (UserID,MessageID)
);

CREATE TABLE eZMail_FilterRule (
  `ID` int(11) NOT NULL DEFAULT 0,
  UserID int(11) NOT NULL DEFAULT 0,
  FolderID int(11) NOT NULL DEFAULT 0,
  HeaderType int(2) DEFAULT 0,
  CheckType int(2) DEFAULT 0,
  MatchValue varchar(200) DEFAULT NULL,
  IsActive int(1) DEFAULT 0,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZMail_Folder (
  `ID` int(11) NOT NULL DEFAULT 0,
  UserID int(11) DEFAULT 0,
  ParentID int(11) DEFAULT 0,
  `Name` varchar(200) DEFAULT NULL,
  FolderType int(2) DEFAULT NULL,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZMail_Mail (
  `ID` int(11) NOT NULL DEFAULT 0,
  UserID int(11) DEFAULT 0,
  ToField varchar(100) DEFAULT NULL,
  FromField varchar(100) DEFAULT NULL,
  FromName varchar(100) DEFAULT NULL,
  Cc varchar(255) DEFAULT NULL,
  Bcc varchar(255) DEFAULT NULL,
  MessageID varchar(200) DEFAULT NULL,
  Reference varchar(100) DEFAULT NULL,
  ReplyTo varchar(100) DEFAULT NULL,
  `Subject` varchar(255) DEFAULT NULL,
  BodyText text DEFAULT NULL,
  `Status` int(1) NOT NULL DEFAULT 0,
  Size int(11) DEFAULT 0,
  UDate int(15) DEFAULT 0,
  PRIMARY KEY (`ID`) 
);

CREATE TABLE eZMail_MailAttachmentLink (
  MailID int(11) NOT NULL DEFAULT 0,
  FileID int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (MailID,FileID)
);

CREATE TABLE eZMail_MailContactLink (
  `ID` int(11) NOT NULL DEFAULT 0,
  MailID int(11) NOT NULL DEFAULT 0,
  PersonID int(11) DEFAULT NULL,
  CompanyID int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZMail_MailFolderLink (
  MailID int(11) NOT NULL DEFAULT 0,
  FolderID int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (MailID,FolderID)
);

CREATE TABLE eZMail_MailImageLink (
  MailID int(11) NOT NULL DEFAULT 0,
  ImageID int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (MailID,ImageID)
);

