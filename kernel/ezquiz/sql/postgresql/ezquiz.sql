CREATE TABLE eZQuiz_Alternative (
  ID int NOT NULL,
  QuestionID int default '0',
  Name varchar(100) default NULL,
  IsCorrect int default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZQuiz_Answer (
  ID int NOT NULL,
  UserID int default '0',
  AlternativeID int default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZQuiz_Game (
  ID int NOT NULL,
  Name varchar(30) default NULL,
  Description text,
  StartDate int default NULL,
  StopDate int default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZQuiz_Question (
  ID int NOT NULL,
  Name varchar(100) default NULL,
  GameID int default '0',
  Placement int default '0',
  Score int default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZQuiz_Score (
  ID int NOT NULL,
  GameID int default '0',
  UserID int default '0',
  TotalScore int default '0',
  LastQuestion int default '0',
  FinishedGame int default '1',
  PRIMARY KEY (ID)
);

CREATE TABLE eZQuiz_AllTimeScore (
  ID int NOT NULL,
  UserID int default '0',
  TotalScore int default '0',
  GamesPlayed int default '0',
  PRIMARY KEY (ID)
);
