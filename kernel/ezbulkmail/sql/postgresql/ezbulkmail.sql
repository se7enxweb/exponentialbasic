CREATE TABLE eZBulkMail_Category (
  ID int NOT NULL,
  Name varchar(200) default NULL,
  Description text,
  IsPublic int NOT NULL,
  IsSingleCategory int default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZBulkMail_Mail (
  ID int NOT NULL,
  UserID int default '0',
  FromField varchar(100) default NULL,
  FromName varchar(100) default NULL,
  ReplyTo varchar(100) default NULL,
  Subject varchar(255) default NULL,
  BodyText text,
  SentDate int default 0,
  IsDraft int NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZBulkMail_MailCategoryLink (
  MailID int NOT NULL default '0',
  CategoryID int NOT NULL default '0',
  PRIMARY KEY (MailID,CategoryID)
);

CREATE TABLE eZBulkMail_MailTemplateLink (
  MailID int NOT NULL default '0',
  TemplateID int NOT NULL default '0',
  PRIMARY KEY (MailID)
);

CREATE TABLE eZBulkMail_SentLog (
  ID int NOT NULL,
  MailID int NOT NULL default '0',
  Mail varchar(255) default NULL,
  SentDate int default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZBulkMail_SubscriptionAddress (
  ID int NOT NULL,
  Password varchar(50) NOT NULL,
  EMail varchar(255) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZBulkMail_SubscriptionLink (
  CategoryID int NOT NULL default '0',
  AddressID int NOT NULL default '0',
  PRIMARY KEY (AddressID,CategoryID)
);

CREATE TABLE eZBulkMail_Template (
  ID int NOT NULL,
  Name varchar(200) default NULL,
  Description varchar default NULL,
  Header text,
  Footer text,
  PRIMARY KEY (ID)
);

CREATE TABLE eZBulkMail_GroupCategoryLink (
  CategoryID int NOT NULL,
  GroupID int NOT NULL,
  PRIMARY KEY (CategoryID, GroupID)
);

CREATE TABLE eZBulkMail_Forgot (
  ID int NOT NULL,
  Mail varchar(255) NOT NULL,
  Password varchar(50) NOT NULL,
  Hash varchar(33),
  Time int,
  PRIMARY KEY (ID)
);

CREATE TABLE eZBulkMail_CategoryDelay (
  ID int NOT NULL default '0',
  CategoryID int NOT NULL default '0',
  AddressID int NOT NULL default '0',
  Delay int default '0',
  MailID int default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZBulkMail_Offset (
  ID int NOT NULL,
  Hour int default NULL,
  Daily int default NULL,
  Weekly int default NULL,
  Monthly int default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZBulkMail_SubscriptionCategorySettings (
  ID int NOT NULL,
  CategoryID int NOT NULL default '0',
  AddressID int NOT NULL default '0',
  Delay int default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZBulkMail_UserCategoryDelay (
  ID int NOT NULL,
  CategoryID int default '0',
  UserID int default '0',
  Delay int default '0',
  MailID int default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZBulkMail_UserCategoryLink (
  UserID int NOT NULL default '0',
  CategoryID int NOT NULL default '0',
  PRIMARY KEY (UserID, CategoryID)
);

CREATE TABLE eZBulkMail_UserCategorySettings (
  ID int NOT NULL,
  CategoryID int default '0',
  UserID int default '0',
  Delay int default '0',
  PRIMARY KEY (ID)
);



