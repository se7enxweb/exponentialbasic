CREATE TABLE eZStats_BrowserType (
  ID int NOT NULL,
  BrowserType varchar(250) NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZStats_PageView (
  ID int NOT NULL,
  UserID int NOT NULL,
  BrowserTypeID int NOT NULL,
  RemoteHostID int NOT NULL,
  RefererURLID int NOT NULL,
  Date int NOT NULL,
  RequestPageID int NOT NULL,
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
  Count int NOT NULL ,
  PRIMARY KEY (ID)
);

CREATE TABLE eZStats_Archive_PageView (
  ID int NOT NULL,
  Hour int NOT NULL,
  Count int  NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZStats_Archive_UniqueVisits (
  ID int NOT NULL,
  Day int NOT NULL,
  Count int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZStats_Archive_BrowserType (
  ID int NOT NULL,
  Browser varchar(250) default NULL,
  Count int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZStats_Archive_RefererURL (
  ID int NOT NULL,
  Month int NOT NULL,
  Domain varchar(100) default NULL,
  URI varchar(200) default NULL,
  Count int  NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZStats_Archive_Users (
  ID int NOT NULL,
  UserID int NOT NULL,
  Month int NOT NULL,
  Count int  NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZStats_Archive_RemoteHost (
  ID int NOT NULL,
  IP varchar(15) default NULL,
  HostName varchar(150) default NULL,
  Count int NOT NULL,
  PRIMARY KEY (ID)
);
