CREATE TABLE eZStats_BrowserType (
  ID int NOT NULL,
  BrowserType varchar(250) NOT NULL default '',
  PRIMARY KEY (ID)
);

CREATE TABLE eZStats_PageView (
  ID int NOT NULL,
  UserID int NOT NULL default '0',
  BrowserTypeID int NOT NULL default '0',
  RemoteHostID int NOT NULL default '0',
  RefererURLID int NOT NULL default '0',
  Date int NOT NULL,
  RequestPageID int NOT NULL default '0',
  DateValue int NOT NULL,
  TimeValue int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZStats_RefererURL (
  ID int NOT NULL,
  Domain varchar(100) default NULL,
  URI varchar(200) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZStats_RemoteHost (
  ID int NOT NULL,
  IP varchar(15) default NULL,
  HostName varchar(150) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZStats_RequestPage (
  ID int NOT NULL,
  URI varchar(250) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZStats_Archive_RequestedPage (
  ID int NOT NULL,
  Month int,
  URI varchar(250) default NULL,
  Count int NOT NULL DEFAULT '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZStats_Archive_PageView (
  ID int NOT NULL,
  Hour int NOT NULL,
  Count int DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZStats_Archive_UniqueVisits (
  ID int NOT NULL,
  Day int NOT NULL,
  Count int NOT NULL DEFAULT '0',
  Language varchar(5) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZStats_Archive_BrowserType (
  ID int NOT NULL,
  Browser varchar(250) default NULL,
  Count int NOT NULL DEFAULT '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZStats_Archive_RefererURL (
  ID int NOT NULL,
  Month int NOT NULL,
  Domain varchar(100) default NULL,
  URI varchar(200) default NULL,
  Count int  NOT NULL DEFAULT '0',
  Language varchar(5) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZStats_Archive_Users (
  ID int NOT NULL,
  UserID int NOT NULL,
  Month int NOT NULL,
  Count int DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZStats_Archive_RemoteHost (
  ID int NOT NULL,
  IP varchar(15) default NULL,
  HostName varchar(150) default NULL,
  Count int NOT NULL DEFAULT '0',
  PRIMARY KEY (ID)
);

CREATE INDEX eZStats_Archive_RequestedPageMonth ON eZStats_Archive_RequestedPage (Month);
CREATE INDEX eZStats_Archive_RequestedPageURI ON eZStats_Archive_RequestedPage (URI);
CREATE INDEX eZStats_Archive_RefererURLMonth ON eZStats_Archive_RefererURL (Month);
CREATE INDEX eZStats_Archive_RemoteHostIP ON eZStats_Archive_RemoteHost (IP);
CREATE INDEX eZStats_Archive_PageViewHour ON eZStats_Archive_PageView (Hour);

