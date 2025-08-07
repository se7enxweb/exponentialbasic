DROP TABLE IF EXISTS eZGroupEventCalendar_Event;
#
# Table structure for table 'eZGroupEventCalendar_Event'
#
CREATE TABLE eZGroupEventCalendar_Event (
 `ID` int(11) NOT NULL AUTO_INCREMENT,
  GroupID int(11) NOT NULL DEFAULT 0,
  `Date` varchar(14) DEFAULT NULL,
  Duration time DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  Description text DEFAULT NULL,
  Location varchar(255) DEFAULT NULL,
  Url text DEFAULT NULL,
  EMailNotice int(11) DEFAULT 0,
  EventAlarmNotice int(11) NOT NULL DEFAULT 0,
  IsPrivate int(11) DEFAULT NULL,
  Priority int(11) NOT NULL DEFAULT 1,
  `Status` int(11) NOT NULL DEFAULT 1,
  EventTypeID int(11) NOT NULL DEFAULT 0,
  EventCategoryID int(11) NOT NULL DEFAULT 0,
  IsRecurring int(11) DEFAULT 0,
  RecurFreq int(11) DEFAULT NULL,
  RecurType varchar(255) DEFAULT NULL,
  RecurDay varchar(255) DEFAULT NULL,
  RecurMonthlyType varchar(32) DEFAULT NULL,
  RecurMonthlyTypeInfo varchar(64) DEFAULT NULL,
  RepeatForever int(11) NOT NULL DEFAULT 0,
  RepeatTimes int(11) NOT NULL DEFAULT 0,
  RepeatUntilDate varchar(14) DEFAULT NULL,
  RecurExceptions text DEFAULT NULL,
  RecurFinishDate varchar(14) DEFAULT NULL,
  PRIMARY KEY (`ID`)
);
DROP TABLE IF EXISTS eZGroupEventCalendar_EventCategory;
#
# Table structure for table 'eZGroupEventCalendar_EventCategory'
#
CREATE TABLE eZGroupEventCalendar_EventCategory (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  ParentID int(11) NOT NULL DEFAULT 0,
  Description text DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  ExcludeFromSearch int(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`)
);

#
# Dumping data for table 'ezGroupEventCalendar_Event
#

INSERT INTO eZGroupEventCalendar_EventCategory VALUES (1,0,'Personal Events','Personal',0);
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (2,1,'Birthdays Events','Birthdays',0);
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (3,1,'Vacation Event','Vacation',0);
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (4,1,'Travel Event','Travel',0);
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (5,0,'Business Event','Business',0);
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (6,5,'Business Calls','Calls',0);
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (7,5,'Clients Events','Clients',0);
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (8,5,'Competition Events','Competition',0);
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (9,5,'Customer Events','Customer',0);
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (10,5,'Favorites Events','Favorites',0);
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (11,5,'Follow up Events','Follow up',0);
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (12,5,'Gifts Events','Gifts',0);
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (13,5,'Holidays Events','Holidays',0);
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (14,5,'Ideas Events','Ideas',0);
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (15,5,'Issues Events','Issues',0);
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (16,5,'Miscellaneous Events','Miscellaneous',0);
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (17,5,'Projects Events','Projects',0);
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (18,5,'Public Holiday Events','Public Holiday',0);
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (19,5,'Status Events','Status',0);
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (20,5,'Suppliers Events','Suppliers',0);
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (21,5,'Travel Events','Travel',0);

DROP TABLE IF EXISTS eZGroupEventCalendar_EventFileLink;
#
# Table structure for table 'eZGroupEventCalendar_EventFileLink'
#

CREATE TABLE `eZGroupEventCalendar_EventFileLink` (
  `ID` int(11) NOT NULL DEFAULT 0,
  EventID int(11) NOT NULL DEFAULT 0,
  FileID int(11) NOT NULL DEFAULT 0,
  Created int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`)
);

DROP TABLE IF EXISTS eZGroupEventCalendar_EventForumLink;
#
# Table structure for table 'eZGroupEventCalendar_EventForumLink'
#

CREATE TABLE `eZGroupEventCalendar_EventForumLink` (
  `ID` int(11) NOT NULL DEFAULT 0,
  EventID int(11) NOT NULL DEFAULT 0,
  ForumID int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`)
);

DROP TABLE IF EXISTS eZGroupEventCalendar_EventType;
#
# Table structure for table 'eZGroupEventCalendar_EventType'
#
CREATE TABLE eZGroupEventCalendar_EventType (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  ParentID int(11) NOT NULL DEFAULT 0,
  Description text DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  ExcludeFromSearch int(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`)
);

INSERT INTO eZGroupEventCalendar_EventType VALUES (1,0,'General Event Type','General',0);
INSERT INTO eZGroupEventCalendar_EventType VALUES (2,0,'Calendar Event Type','Calendar',0);
INSERT INTO eZGroupEventCalendar_EventType VALUES (3,4,'Web Work Event Type','Web Work',0);
INSERT INTO eZGroupEventCalendar_EventType VALUES (4,0,'Office Work Event Type','Office Work',0);
INSERT INTO eZGroupEventCalendar_EventType VALUES (5,4,'Staff Meeting Event Type','Staff Meeting',0);
INSERT INTO eZGroupEventCalendar_EventType VALUES (6,4,'Meeting','Meeting',0);
INSERT INTO eZGroupEventCalendar_EventType VALUES (7,4,'Client Meeting','Client Meeting',0);
INSERT INTO eZGroupEventCalendar_EventType VALUES (8,0,'We are not always quite so serious, get your groove on . . . have fun.','Fun',0);

DROP TABLE IF EXISTS eZGroupEventCalendar_GroupEditor;
#'
# Table structure for table 'eZGroupEventCalendar_GroupEditor'
#
CREATE TABLE eZGroupEventCalendar_GroupEditor (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  UserID int(11) DEFAULT NULL,
  GroupID int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`),
  KEY ID_2 (`ID`)
);

DROP TABLE IF EXISTS eZGroupEventCalendar_GroupNoShow;
#
# Table structure for table 'eZGroupEventCalendar_GroupNoShow'
#
CREATE TABLE eZGroupEventCalendar_GroupNoShow (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  GroupID int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`)
);

# 
# Dumping data for table `eZForum_Category`
# 

INSERT INTO eZForum_Category VALUES (1000,'Community Calendar','Calendar Event Forum',0,1);

