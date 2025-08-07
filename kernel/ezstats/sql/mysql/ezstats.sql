CREATE TABLE eZStats_Archive_BrowserType (
  `ID` int(11) NOT NULL,
  Browser varchar(250) DEFAULT NULL,
  Count int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`),
  KEY eZStats_Archive_BrowserTypeBrowser (Browser)
);

CREATE TABLE eZStats_Archive_PageView (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Hour` int(11) NOT NULL DEFAULT 0,
  Count int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`),
);

CREATE TABLE eZStats_Archive_RefererURL (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Month` int(11) NOT NULL DEFAULT 0,
  Domain varchar(100) DEFAULT NULL,
  URI varchar(200) DEFAULT NULL,
  Count int(11) NOT NULL DEFAULT 0,
  `Language` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY eZStats_Archive_RefererURLMonth (`Month`),
  KEY eZStats_Archive_RefererURLURI (URI),
  KEY eZStats_Archive_RefererURLDomain (Domain)
);

CREATE TABLE eZStats_Archive_RemoteHost (
  `ID` int(11) NOT NULL DEFAULT 0,
  IP varchar(15) DEFAULT NULL,
  HostName varchar(150) DEFAULT NULL,
  Count int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`),
  KEY eZStats_Archive_RemoteHostIP (IP),
  KEY eZStats_Archive_RemoteHostHostName (HostName)
);


CREATE TABLE eZStats_Archive_RequestedPage (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Month` int(11) DEFAULT NULL,
  URI varchar(250) DEFAULT NULL,
  Count int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`),
  KEY eZStats_Archive_RequestedPageMonth (`Month`),
  KEY eZStats_Archive_RequestedPageURI (URI)
);

CREATE TABLE eZStats_Archive_UniqueVisits (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Day` int(11) NOT NULL DEFAULT 0,
  Count int(11) NOT NULL DEFAULT 0,
  `Language` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZStats_Archive_Users (
  `ID` int(11) NOT NULL DEFAULT 0,
  UserID int(11) NOT NULL DEFAULT 0,
  `Month` int(11) NOT NULL DEFAULT 0,
  Count int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`),
  KEY eZStats_Archive_UsersUserID (UserID),
  KEY eZStats_Archive_UsersMonth (`Month`)
);



CREATE TABLE eZStats_BrowserType (
  `ID` int(11) NOT NULL DEFAULT 0,
  BrowserType varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZStats_PageView (
  `ID` int(11) NOT NULL DEFAULT 0,
  UserID int(11) NOT NULL DEFAULT 0,
  BrowserTypeID int(11) NOT NULL DEFAULT 0,
  RemoteHostID int(11) NOT NULL DEFAULT 0,
  RefererURLID int(11) NOT NULL DEFAULT 0,
  `Date` int(11) NOT NULL DEFAULT 0,
  RequestPageID int(11) NOT NULL DEFAULT 0,
  DateValue int(11) NOT NULL DEFAULT 0,
  TimeValue int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`),
  KEY PageView_TimeValue (TimeValue),
  KEY eZStats_PageViewDate (`Date`)
);

CREATE TABLE eZStats_RefererURL (
  `ID` int(11) NOT NULL DEFAULT 0,
  Domain varchar(100) DEFAULT NULL,
  URI varchar(200) DEFAULT NULL,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZStats_RemoteHost (
  `ID` int(11) NOT NULL DEFAULT 0,
  IP varchar(15) DEFAULT NULL,
  HostName varchar(150) DEFAULT NULL,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZStats_RequestPage (
  `ID` int(11) NOT NULL DEFAULT 0,
  URI varchar(250) DEFAULT NULL,
  PRIMARY KEY (`ID`)
);

CREATE INDEX PageView_TimeValue ON eZStats_PageView (TimeValue);

CREATE INDEX eZStats_Archive_RequestedPageMonth ON eZStats_Archive_RequestedPage (Month);
CREATE INDEX eZStats_Archive_RequestedPageURI ON eZStats_Archive_RequestedPage (URI);
CREATE INDEX eZStats_Archive_RefererURLMonth ON eZStats_Archive_RefererURL (Month);
CREATE INDEX eZStats_Archive_RemoteHostIP ON eZStats_Archive_RemoteHost (IP);
CREATE INDEX eZStats_Archive_PageViewHour ON eZStats_Archive_PageView (Hour);

CREATE INDEX eZStats_PageViewDate ON eZStats_PageView(Date);
CREATE INDEX eZStats_Archive_RefererURLURI ON eZStats_Archive_RefererURL(URI);
CREATE INDEX eZStats_Archive_RefererURLDomain ON eZStats_Archive_RefererURL(Domain);
CREATE INDEX eZStats_Archive_RemoteHostHostName ON eZStats_Archive_RemoteHost(HostName);
CREATE INDEX eZStats_Archive_UsersUserID ON eZStats_Archive_Users(UserID);
CREATE INDEX eZStats_Archive_UsersMonth ON eZStats_Archive_Users(Month);
CREATE INDEX eZStats_Archive_BrowserTypeBrowser ON eZStats_Archive_BrowserType(Browser);


