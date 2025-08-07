CREATE TABLE eZBug_Bug (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(150) DEFAULT NULL,
  Description text DEFAULT NULL,
  UserID int(11) NOT NULL DEFAULT 0,
  Created int(11) DEFAULT NULL,
  IsHandled int(11) NOT NULL DEFAULT 0,
  PriorityID int(11) NOT NULL DEFAULT 0,
  StatusID int(11) NOT NULL DEFAULT 0,
  IsClosed int(11) DEFAULT 0,
  Version varchar(150) DEFAULT '',
  UserEmail varchar(100) DEFAULT '',
  OwnerID int(11) DEFAULT NULL,
  IsPrivate int(11) DEFAULT 0,
  PRIMARY KEY (`ID`)
);

INSERT INTO eZBug_Bug VALUES (1,'Help!','It dosent work!',33,997357856,0,0,0,null,'','',null,'0');

CREATE TABLE eZBug_BugCategoryLink (
  `ID` int(11) NOT NULL DEFAULT 0,
  CategoryID int(11) DEFAULT NULL,
  BugID int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
);

INSERT INTO eZBug_BugCategoryLink VALUES (1,2,1);

CREATE TABLE eZBug_BugFileLink (
  `ID` int(11) NOT NULL DEFAULT 0,
  BugID int(11) NOT NULL DEFAULT 0,
  FileID int(11) NOT NULL DEFAULT 0,
  Created int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZBug_BugImageLink (
  `ID` int(11) NOT NULL DEFAULT 0,
  BugID int(11) NOT NULL DEFAULT 0,
  ImageID int(11) NOT NULL DEFAULT 0,
  Created int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZBug_BugModuleLink (
  `ID` int(11) NOT NULL DEFAULT 0,
  ModuleID int(11) DEFAULT NULL,
  BugID int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
);

INSERT INTO eZBug_BugModuleLink VALUES (1,1,1);

CREATE TABLE eZBug_Category (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(150) DEFAULT NULL,
  Description text DEFAULT NULL,
  PRIMARY KEY (`ID`)
);

INSERT INTO eZBug_Category VALUES (1,'GUI','');
INSERT INTO eZBug_Category VALUES (2,'Feature request','');
INSERT INTO eZBug_Category VALUES (3,'Suggestion','');
INSERT INTO eZBug_Category VALUES (4,'Enhancement','');
INSERT INTO eZBug_Category VALUES (5,'Bug','');

CREATE TABLE eZBug_Log (
  `ID` int(11) NOT NULL DEFAULT 0,
  BugID int(11) NOT NULL DEFAULT 0,
  UserID int(11) NOT NULL DEFAULT 0,
  Description text DEFAULT NULL,
  Created int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`)
);


CREATE TABLE eZBug_Module (
  `ID` int(11) NOT NULL DEFAULT 0,
  ParentID int(11) DEFAULT NULL,
  `Name` varchar(150) DEFAULT NULL,
  Description text DEFAULT NULL,
  OwnerGroupID int(11) DEFAULT 0,
  PRIMARY KEY (`ID`)
);

INSERT INTO eZBug_Module VALUES (1,0,'My program','', 1);

CREATE TABLE eZBug_Priority (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(150) NOT NULL DEFAULT '',
  `Value` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
);

INSERT INTO eZBug_Priority VALUES (1,'High',NULL);
INSERT INTO eZBug_Priority VALUES (2,'Medium',NULL);
INSERT INTO eZBug_Priority VALUES (3,'Low',NULL);
INSERT INTO eZBug_Priority VALUES (4,'Critical',NULL);

CREATE TABLE eZBug_Status (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(150) NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`)
);

INSERT INTO eZBug_Status VALUES (1,'Fixed');
INSERT INTO eZBug_Status VALUES (2,'Duplicate');
INSERT INTO eZBug_Status VALUES (3,'Invalid');
INSERT INTO eZBug_Status VALUES (4,'Will not fix');
INSERT INTO eZBug_Status VALUES (5,'Works here');
INSERT INTO eZBug_Status VALUES (6,'Future addition');


CREATE TABLE eZBug_ModulePermission (
  `ID` int(11) NOT NULL DEFAULT 0,
  ObjectID int(11) DEFAULT NULL,
  GroupID int(11) DEFAULT NULL,
  ReadPermission int(11) DEFAULT 0,
  WritePermission int(11) DEFAULT 0,
  PRIMARY KEY (`ID`)
);