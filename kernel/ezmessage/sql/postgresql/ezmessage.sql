CREATE TABLE eZMessage (
  ID int NOT NULL,
  UserID int NOT NULL default '0',
  Subject varchar(255) NOT NULL default '',
  Description text,
  PRIMARY KEY (ID)
);

CREATE TABLE eZMessage_Message (
  ID int NOT NULL,
  FromUserID int NOT NULL default '0',
  ToUserID int NOT NULL default '0',
  Created int NOT NULL,
  IsRead int NOT NULL default '0',
  Subject varchar(255) NOT NULL default '',
  Description text,
  PRIMARY KEY (ID)
);
