CREATE TABLE eZUser_User (
  ID int NOT NULL,
  PersonID int NULL,
  Login varchar(50) NOT NULL default '',
  Password varchar(50) NOT NULL default '',
  Email varchar(50) default NULL,
  FirstName varchar(50) default NULL,
  LastName varchar(50) default NULL,
  InfoSubscription int default '0',
  Signature text NOT NULL,
  SimultaneousLogins int NOT NULL default '0',
  CookieLogin int default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZUser_UserGroupLink (
  ID int NOT NULL,
  UserID int default NULL,
  GroupID int default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZUser_UserAddressLink (
  ID int NOT NULL,
  UserID int NOT NULL default '0',
  AddressID int NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZUser_Author (
  ID int NOT NULL,
  Name varchar(255) default NULL,
  EMail varchar(255) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZUser_Cookie (
  ID int NOT NULL,
  UserID int default '0',
  Hash varchar(33) default NULL,
  Time int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZUser_Forgot (
  ID int NOT NULL,
  UserID int NOT NULL default '0',
  Hash varchar(33) default NULL,
  Time int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZUser_Group (
  ID int NOT NULL,
  Name varchar(100) default NULL,
  Description text,
  SessionTimeout int default '60',
  IsRoot int default '0',
  GroupURL varchar(200) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZUser_GroupPermissionLink (
  ID int NOT NULL,
  GroupID int default NULL,
  PermissionID int default NULL,
  IsEnabled int default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZUser_Module (
  ID int NOT NULL,
  Name varchar(100) NOT NULL default '',
  PRIMARY KEY (ID)
);

CREATE TABLE eZUser_Permission (
  ID int NOT NULL,
  ModuleID int default NULL,
  Name varchar(100) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZUser_Trustees (
  ID int NOT NULL,
  OwnerID int NOT NULL,
  UserID int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZUser_UserShippingLink (
  ID int NOT NULL default '0',
  UserID int default '0',
  AddressID int default '0',
  PRIMARY KEY  (ID)
);

INSERT INTO eZUser_Module (ID, Name) VALUES (1,'eZTrade');
INSERT INTO eZUser_Module (ID, Name) VALUES (2,'eZPoll');
INSERT INTO eZUser_Module (ID, Name) VALUES (3,'eZUser');
INSERT INTO eZUser_Module (ID, Name) VALUES (4,'eZTodo');
INSERT INTO eZUser_Module (ID, Name) VALUES (5,'eZNewsfeed');
INSERT INTO eZUser_Module (ID, Name) VALUES (6,'eZContact');
INSERT INTO eZUser_Module (ID, Name) VALUES (7,'eZForum');
INSERT INTO eZUser_Module (ID, Name) VALUES (8,'eZLink');
INSERT INTO eZUser_Module (ID, Name) VALUES (9,'eZFileManager');
INSERT INTO eZUser_Module (ID, Name) VALUES (10,'eZImageCatalogue');
INSERT INTO eZUser_Module (ID, Name) VALUES (11,'eZBug');
INSERT INTO eZUser_Module (ID, Name) VALUES (12,'eZArticle');
INSERT INTO eZUser_Module (ID, Name) VALUES (13,'eZBulkMail');
INSERT INTO eZUser_Module (ID, Name) VALUES (14,'eZStats');
INSERT INTO eZUser_Module (ID, Name) VALUES (15,'eZSysInfo');
INSERT INTO eZUser_Module (ID, Name) VALUES (16,'eZSiteManager');
INSERT INTO eZUser_Module (ID, Name) VALUES (17, 'eZMediaCatalogue');
INSERT INTO eZUser_Module (ID, Name) VALUES (18, 'eZAd');
INSERT INTO eZUser_Module (ID, Name) VALUES (19, 'eZForm');
INSERT INTO eZUser_Module (ID, Name) VALUES (20, 'eZURLTranslator');
INSERT INTO eZUser_Module (ID, Name) VALUES (21, 'eZMessage');
INSERT INTO eZUser_Module (ID, Name) VALUES (22, 'eZAddress');
INSERT INTO eZUser_Module (ID, Name) VALUES (23, 'eZCalendar');


INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (1,3,'UserAdd');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (2,3,'UserDelete');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (3,3,'UserModify');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (4,3,'GroupDelete');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (5,3,'GroupAdd');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (6,3,'GroupModify');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (11,8,'LinkGroupModify');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (8,3,'AdminLogin');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (10,8,'LinkGroupAdd');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (9,8,'LinkGroupDelete');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (12,8,'LinkModify');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (13,8,'LinkAdd');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (14,8,'LinkDelete');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (15,7,'CategoryAdd');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (16,7,'CategoryModify');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (17,7,'CategoryDelete');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (18,7,'ForumDelete');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (19,7,'ForumAdd');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (20,7,'ForumModify');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (21,7,'MessageModify');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (22,7,'MessageAdd');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (23,7,'MessageDelete');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (24,6,'PersonAdd');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (25,6,'CompanyAdd');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (26,6,'CategoryAdd');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (27,6,'PersonDelete');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (28,6,'CompanyDelete');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (29,6,'CategoryDelete');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (30,6,'PersonModify');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (31,6,'CompanyModify');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (32,6,'CategoryModify');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (33,6,'PersonView');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (34,6,'PersonList');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (35,3,'UserLogin');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (36,9,'WriteToRoot');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (39,10,'WriteToRoot');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (41,6,'CompanyView');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (42,6,'CompanyList');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (43,6,'TypeAdmin');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (44,6,'Consultation');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (45,4,'ViewOtherUsers');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (46,4,'AddOthers');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (47,4,'EditOthers');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (48,6,'CompanyStats');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (49,1,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (50,2,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (51,3,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (52,4,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (53,5,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (54,6,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (55,7,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (56,8,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (57,9,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (58,10,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (59,11,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (60,12,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (61,13,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (62,14,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (63,15,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (64,12,'WriteToRoot');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (65,16,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name )VALUES (66, 17, 'WriteToRoot');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (69, 21, 'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (68, 21, 'WriteToRoot');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (70, 9, 'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (71, 13, 'WriteToRoot');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (72, 16, 'WriteToRoot');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (73, 1, 'WriteToRoot');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (74, 14, 'WriteToRoot');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (75, 18, 'WriteToRoot');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (76, 18, 'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (77, 19, 'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (78, 19, 'WriteToRoot');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (79, 20, 'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (80, 20, 'WriteToRoot');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (81, 2, 'WriteToRoot');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (82, 8, 'WriteToRoot');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (85, 11, 'WriteToRoot');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (86, 4, 'WriteToRoot');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (87, 5, 'WriteToRoot');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (88, 7, 'WriteToRoot');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (89, 6, 'WriteToRoot');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (90, 22, 'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (91, 22, 'WriteToRoot');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (92, 23, 'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (93, 23, 'WriteToRoot');


CREATE TABLE eZUser_UserGroupDefinition (
  ID int NOT NULL,
  UserID int NOT NULL default '0',
  GroupID int NOT NULL default '0',
  PRIMARY KEY (ID)
);

INSERT INTO eZUser_User ( ID,  Login,  Password,  Email,  FirstName,  LastName,  InfoSubscription,  Signature,  SimultaneousLogins,  CookieLogin )  
VALUES (1,'admin','9b6d0bb3102b87fae57bc4a39149518e','postmaster@yourdomain','admin','user','0','',0,0);

INSERT INTO eZUser_Group (ID, Name, Description, SessionTimeout, IsRoot) VALUES (1,'Administrators','All rights',7200,1);
INSERT INTO eZUser_Group (ID, Name, Description, SessionTimeout, IsRoot) VALUES (2,'Anonymous','Anonymous users',7200,0);
INSERT INTO eZUser_UserGroupLink (ID, UserID, GroupID) VALUES (1,1,1);

CREATE TABLE eZUser_Photographer (
  ID int NOT NULL,
  Name varchar(50) default NULL,
  EMail char(50) default NULL,
  PRIMARY KEY (ID)
);

CREATE INDEX UserGroupLink_UserID ON eZUser_UserGroupLink (UserID);
CREATE INDEX UserGroupLink_GroupID ON eZUser_UserGroupLink (GroupID);

CREATE UNIQUE INDEX User_Login ON eZUser_User (Login);  


