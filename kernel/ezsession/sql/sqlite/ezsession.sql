CREATE TABLE eZSession_Preferences (
  `ID` int(11) NOT NULL DEFAULT 0,
  UserID int(11) NOT NULL DEFAULT 0,
  `Name` char(50) DEFAULT NULL,
  `Value` char(255) DEFAULT NULL,
  GroupName char(50) DEFAULT NULL);

CREATE TABLE eZSession_Session (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Hash` char(33) NOT NULL DEFAULT '',
  Created int(11) NOT NULL DEFAULT 0,
  LastAccessed int(11) NOT NULL DEFAULT 0);

CREATE TABLE eZSession_SessionVariable (
  `ID` int(11) NOT NULL DEFAULT 0,
  SessionID int(11) NOT NULL DEFAULT 0,
  `Name` varchar(25) NOT NULL DEFAULT '',
  `Value` text NOT NULL DEFAULT '',
  GroupName varchar(50) NOT NULL DEFAULT '');


CREATE INDEX Session_Hash  ON eZSession_Session (Hash);
CREATE INDEX Session_Created  ON eZSession_Session (Created);
CREATE INDEX Session_LastAccessed  ON eZSession_Session (LastAccessed);

CREATE INDEX Session_VariableName  ON eZSession_SessionVariable (Name);
CREATE INDEX Session_VariableGroupName  ON eZSession_SessionVariable (GroupName);
CREATE INDEX Session_VariableSessionID  ON eZSession_SessionVariable (SessionID);



