
CREATE TABLE eZPoll_MainPoll (
  ID int NOT NULL,
  PollID int default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZPoll_Poll (
  ID int NOT NULL,
  Name varchar(100) default NULL,
  Description lvarchar,
  Anonymous int NOT NULL,
  IsEnabled int NOT NULL,
  IsClosed int NOT NULL,
  ShowResult int NOT NULL,
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


