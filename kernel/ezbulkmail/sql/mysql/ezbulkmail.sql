CREATE TABLE eZBulkMail_Category (
  ID int(11) NOT NULL,
  Name varchar(200) default NULL,
  Description text,
  IsPublic int NOT NULL,
  IsSingleCategory int default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZBulkMail_Mail (
  ID int(11) NOT NULL,
  UserID int(11) default '0',
  FromField varchar(100) default NULL,
  FromName varchar(100) default NULL,
  ReplyTo varchar(100) default NULL,
  Subject varchar(255) default NULL,
  BodyText text,
  SentDate int(14) default 0,
  IsDraft int(1) NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZBulkMail_MailCategoryLink (
  MailID int(11) NOT NULL default '0',
  CategoryID int(11) NOT NULL default '0',
  PRIMARY KEY (MailID,CategoryID)
);

CREATE TABLE eZBulkMail_MailTemplateLink (
  MailID int(11) NOT NULL default '0',
  TemplateID int(11) NOT NULL default '0',
  PRIMARY KEY (MailID)
);

CREATE TABLE eZBulkMail_SentLog (
  ID int(11) NOT NULL,
  MailID int(11) NOT NULL default '0',
  Mail varchar(255) default NULL,
  SentDate int(11) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZBulkMail_SubscriptionAddress (
  ID int(11) NOT NULL,
  Password varchar(50) NOT NULL,
  EMail varchar(255) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZBulkMail_SubscriptionLink (
  CategoryID int(11) NOT NULL default '0',
  AddressID int(11) NOT NULL default '0',
  PRIMARY KEY (AddressID,CategoryID)
);

CREATE TABLE eZBulkMail_Template (
  ID int(11) NOT NULL,
  Name varchar(200) default NULL,
  Description text default NULL,
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
  ID int(11) NOT NULL,
  Mail varchar(255) NOT NULL,
  Password varchar(50) NOT NULL,
  Hash varchar(33),
  Time int,
  PRIMARY KEY (ID)
);

CREATE TABLE eZBulkMail_CategoryDelay (
  ID int(11) NOT NULL,
  CategoryID int(11) NOT NULL default '0',
  AddressID int(11) NOT NULL default '0',
  Delay int(11) default '0',
  MailID int(11) default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZBulkMail_Offset (
  ID int(11) NOT NULL,
  Hour int(11) default NULL,
  Daily int(11) default NULL,
  Weekly int(11) default NULL,
  Monthly int(11) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZBulkMail_SubscriptionCategorySettings (
  ID int(11) NOT NULL,
  CategoryID int(11) NOT NULL default '0',
  AddressID int(11) NOT NULL default '0',
  Delay int(11) default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZBulkMail_UserCategoryDelay (
  ID int(11) NOT NULL,
  CategoryID int(11) default '0',
  UserID int(11) default '0',
  Delay int(11) default '0',
  MailID int(11) default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZBulkMail_UserCategoryLink (
  UserID int(11) NOT NULL default '0',
  CategoryID int(11) NOT NULL default '0',
  PRIMARY KEY (UserID, CategoryID)
);

CREATE TABLE eZBulkMail_UserCategorySettings (
  ID int(11) NOT NULL,
  CategoryID int(11) default '0',
  UserID int(11) default '0',
  Delay int(11) default '0',
  PRIMARY KEY (ID)
);