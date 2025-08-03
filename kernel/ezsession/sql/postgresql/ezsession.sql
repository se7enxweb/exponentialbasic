CREATE TABLE eZSession_Session(
  ID int NOT NULL,
  Hash varchar(33) default NULL,
  Created int NOT NULL,
  LastAccessed int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZSession_SessionVariable(
  ID int NOT NULL,
  SessionID int not NULL,
  Name varchar(25) not NULL,
  Value text not NULL,
  GroupName varchar(50) default NULL,
  PRIMARY KEY (ID)
);


CREATE TABLE eZSession_Preferences (
  ID int NOT NULL,
  UserID int NOT NULL,
  Name varchar(50) default NULL,
  Value varchar(255) default NULL,
  GroupName varchar(50) default NULL,
  PRIMARY KEY (ID)
);
