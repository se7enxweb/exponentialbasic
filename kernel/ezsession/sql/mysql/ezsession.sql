CREATE TABLE eZSession_Preferences (
  ID int(11) NOT NULL,
  UserID int(11) DEFAULT '0' NOT NULL,
  Name char(50),
  Value char(255),
  GroupName char(50) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZSession_Session (
  ID int(11) NOT NULL,
  Hash char(33) NOT NULL,
  Created int not null,
  LastAccessed int not null,
  PRIMARY KEY (ID)
);

CREATE TABLE eZSession_SessionVariable (
  ID int(11) NOT NULL,
  SessionID int(11) not NULL,
  Name varchar(25) not NULL,
  Value Text not NULL,
  GroupName varchar(50) NOT NULL,
  PRIMARY KEY (ID)
);


CREATE INDEX Session_Hash  ON eZSession_Session (Hash);
CREATE INDEX Session_Created  ON eZSession_Session (Created);
CREATE INDEX Session_LastAccessed  ON eZSession_Session (LastAccessed);

CREATE INDEX Session_VariableName  ON eZSession_SessionVariable (Name);
CREATE INDEX Session_VariableGroupName  ON eZSession_SessionVariable (GroupName);
CREATE INDEX Session_VariableSessionID  ON eZSession_SessionVariable (SessionID);

