CREATE TABLE eZTodo_Category (
  `ID` int(11) NOT NULL DEFAULT 0,
  Description text DEFAULT NULL,
  `Name` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZTodo_Log (
  `ID` int(11) NOT NULL DEFAULT 0,
  Log text DEFAULT NULL,
  Created int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZTodo_Priority (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZTodo_Status (
 `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(30) DEFAULT NULL,
  Description text DEFAULT NULL,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZTodo_Todo (
`ID` int(11) NOT NULL DEFAULT 0,
  Category int(11) DEFAULT NULL,
  Priority int(11) DEFAULT NULL,
  Permission int(11) DEFAULT 0,
  UserID int(11) DEFAULT NULL,
  OwnerID int(11) DEFAULT NULL,
  `Name` varchar(30) DEFAULT NULL,
  `Date` int(11) DEFAULT NULL,
  Due int(11) DEFAULT NULL,
  Description text DEFAULT NULL,
  `Status` int(11) DEFAULT 0,
  IsPublic int(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`)
);


CREATE TABLE eZTodo_TodoLogLink (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  TodoID int(11) DEFAULT NULL,
  LogID int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
);

INSERT INTO eZTodo_Category VALUES (1,'','Work');    

INSERT INTO eZTodo_Status (Description, ID, Name) VALUES (NULL,1,'Done');
INSERT INTO eZTodo_Status (Description, ID, Name) VALUES (NULL,2,'Not Done');

INSERT INTO eZTodo_Priority (ID, Name) VALUES (1,'Low');
INSERT INTO eZTodo_Priority (ID, Name) VALUES (2,'Medium');
INSERT INTO eZTodo_Priority (ID, Name) VALUES (3,'High');


