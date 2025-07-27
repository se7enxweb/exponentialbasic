DROP TABLE IF EXISTS eZGroupEventCalendar_Event;
#
# Table structure for table 'eZGroupEventCalendar_Event'
#
CREATE TABLE eZGroupEventCalendar_Event (
   ID int(11) NOT NULL auto_increment,
   GroupID int(11) DEFAULT '0' NOT NULL,
   Date int(11),
   Duration time,
   Name varchar(255),
   Description text,
   Location varchar(255) default NULL,
   Url text default NULL,
   EMailNotice int(11) DEFAULT '0',
   EventAlarmNotice int(11) DEFAULT '0' NOT NULL,
   IsPrivate int(11),
   Priority int(11) DEFAULT '1' NOT NULL,
   Status int(11) DEFAULT '1' NOT NULL,
   EventTypeID int(11) DEFAULT '0' NOT NULL,
   EventCategoryID int(11) DEFAULT '0' NOT NULL,
   IsRecurring int default '0',
   RecurFreq int default NULL,
   RecurType varchar(255) default NULL,
   RecurDay varchar(255) default NULL,
   RecurMonthlyType varchar(32) default NULL,
   RecurMonthlyTypeInfo varchar(64) default NULL,
   RepeatForever int(11) DEFAULT '0' NOT NULL,
   RepeatTimes int(11) DEFAULT '0' NOT NULL,
   RepeatUntilDate int(11),
   RecurExceptions text default NULL,
   RecurFinishDate int(11),
   PRIMARY KEY (ID)
);
DROP TABLE IF EXISTS eZGroupEventCalendar_EventCategory;
#
# Table structure for table 'eZGroupEventCalendar_EventCategory'
#
CREATE TABLE eZGroupEventCalendar_EventCategory (
   ID int(11) NOT NULL auto_increment,
   ParentID int(11) DEFAULT '0' NOT NULL,
   Description text,
   Name varchar(255),
   ExcludeFromSearch int(1) DEFAULT '0' NOT NULL,
   PRIMARY KEY (ID)
);

#
# Dumping data for table 'ezGroupEventCalendar_Event
#

INSERT INTO eZGroupEventCalendar_EventCategory VALUES (1,0,'Personal Events','Personal');
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (2,1,'Birthdays Events','Birthdays');
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (3,1,'Vacation Event','Vacation');
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (4,1,'Travel Event','Travel');
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (5,0,'Business Event','Business');
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (6,5,'Business Calls','Calls');
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (7,5,'Clients Events','Clients');
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (8,5,'Competition Events','Competition');
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (9,5,'Customer Events','Customer');
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (10,5,'Favorites Events','Favorites');
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (11,5,'Follow up Events','Follow up');
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (12,5,'Gifts Events','Gifts');
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (13,5,'Holidays Events','Holidays');
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (14,5,'Ideas Events','Ideas');
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (15,5,'Issues Events','Issues');
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (16,5,'Miscellaneous Events','Miscellaneous');
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (17,5,'Projects Events','Projects');
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (18,5,'Public Holiday Events','Public Holiday');
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (19,5,'Status Events','Status');
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (20,5,'Suppliers Events','Suppliers');
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (21,5,'Travel Events','Travel');


DROP TABLE IF EXISTS eZGroupEventCalendar_EventType;
#
# Table structure for table 'eZGroupEventCalendar_EventType'
#
CREATE TABLE eZGroupEventCalendar_EventType (
   ID int(11) NOT NULL auto_increment,
   ParentID int(11) DEFAULT '0' NOT NULL,
   Description text,
   Name varchar(255),
   ExcludeFromSearch int(1) DEFAULT '0' NOT NULL,
   PRIMARY KEY (ID)
);


INSERT INTO eZGroupEventCalendar_EventType VALUES (1,0,'General Event Type','General');
INSERT INTO eZGroupEventCalendar_EventType VALUES (2,0,'Calendar Event Type','Calendar');
INSERT INTO eZGroupEventCalendar_EventType VALUES (3,4,'Web Work Event Type','Web Work');
INSERT INTO eZGroupEventCalendar_EventType VALUES (4,0,'Office Work Event Type','Office Work');
INSERT INTO eZGroupEventCalendar_EventType VALUES (5,4,'Staff Meeting Event Type','Staff Meeting');
INSERT INTO eZGroupEventCalendar_EventType VALUES (6,4,'Meeting','Meeting');
INSERT INTO eZGroupEventCalendar_EventType VALUES (7,4,'Client Meeting','Client Meeting');
INSERT INTO eZGroupEventCalendar_EventType VALUES (8,0,'We are not always quite so serious, get your groove on . . . have fun.','Fun');

DROP TABLE IF EXISTS eZGroupEventCalendar_GroupEditor;
#'
# Table structure for table 'eZGroupEventCalendar_GroupEditor'
#
CREATE TABLE eZGroupEventCalendar_GroupEditor (
   ID int(11) NOT NULL auto_increment,
   UserID int(11),
   GroupID int(11) DEFAULT '0' NOT NULL,
   PRIMARY KEY (ID),
   UNIQUE ID (ID),
   KEY ID_2 (ID)
);

DROP TABLE IF EXISTS eZGroupEventCalendar_GroupNoShow;
#
# Table structure for table 'eZGroupEventCalendar_GroupNoShow'
#
CREATE TABLE eZGroupEventCalendar_GroupNoShow (
   ID int(11) NOT NULL auto_increment,
   GroupID int(11) DEFAULT '0' NOT NULL,
   PRIMARY KEY (ID)
);

DROP TABLE IF EXISTS eZGroupEventCalendar_EventForumLink;
#
# Table structure for table 'eZGroupEventCalendar_EventForumLink'
#

CREATE TABLE `eZGroupEventCalendar_EventForumLink` (
  `ID` int(11) NOT NULL default '0',
  `EventID` int(11) NOT NULL default '0',
  `ForumID` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
);


DROP TABLE IF EXISTS eZGroupEventCalendar_EventFileLink;
#
# Table structure for table 'eZGroupEventCalendar_EventFileLink'
#

CREATE TABLE `eZGroupEventCalendar_EventFileLink` (
  `ID` int(11) NOT NULL default '0',
  `EventID` int(11) NOT NULL default '0',
  `FileID` int(11) NOT NULL default '0',
  `Created` int(11) NOT NULL,
  PRIMARY KEY  (`ID`)
);

# 
# Dumping data for table `eZForum_Category`
# 

INSERT INTO eZForum_Category VALUES (1000,'Community Calendar','Calendar Event Forum',0,1);

