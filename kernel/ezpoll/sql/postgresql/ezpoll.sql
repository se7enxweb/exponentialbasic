CREATE TABLE eZPoll_MainPoll (
  ID int NOT NULL,
  PollID int default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZPoll_Poll (
  ID int NOT NULL,
  Name varchar(100) default NULL,
  Description text,
  Anonymous int NOT NULL default '0',
  IsEnabled int NOT NULL default '0',
  IsClosed int NOT NULL default '0',
  ShowResult int NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZPoll_PollChoice (
  ID int NOT NULL,
  PollID int default NULL,
  Name varchar(100) default NULL,
  Offs int default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZPoll_Vote (
  ID int NOT NULL,
  PollID int default NULL,
  ChoiceID int default NULL,
  VotingIP varchar(20) default NULL,
  UserID int default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZPoll_PollForumLink (
  ID int NOT NULL,
  PollID int NOT NULL default '0',
  ForumID int NOT NULL default '0',
  PRIMARY KEY (ID)
);
