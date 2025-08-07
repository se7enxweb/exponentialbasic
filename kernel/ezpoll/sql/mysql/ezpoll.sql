CREATE TABLE eZPoll_MainPoll (
   `ID` int(11) NOT NULL DEFAULT 0,
  PollID int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZPoll_Poll (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(100) DEFAULT NULL,
  Description text DEFAULT NULL,
  Anonymous int(11) NOT NULL DEFAULT 0,
  IsEnabled int(11) NOT NULL DEFAULT 0,
  IsClosed int(11) NOT NULL DEFAULT 0,
  ShowResult int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZPoll_PollChoice (
  `ID` int(11) NOT NULL DEFAULT 0,
  PollID int(11) DEFAULT NULL,
  `Name` varchar(100) DEFAULT NULL,
  Offs int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZPoll_PollForumLink (
  `ID` int(11) NOT NULL DEFAULT 0,
  PollID int(11) NOT NULL DEFAULT 0,
  ForumID int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZPoll_Vote (
  `ID` int(11) NOT NULL DEFAULT 0,
  PollID int(11) DEFAULT NULL,
  ChoiceID int(11) DEFAULT NULL,
  VotingIP varchar(20) DEFAULT NULL,
  UserID int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
);

