CREATE TABLE eZQuiz_AllTimeScore (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  UserID int(11) DEFAULT 0,
  TotalScore int(11) DEFAULT 0,
  GamesPlayed int(11) DEFAULT 0,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZQuiz_Alternative (
  `ID` int(11) NOT NULL DEFAULT 0,
  QuestionID int(11) DEFAULT 0,
  `Name` char(100) DEFAULT NULL,
  IsCorrect int(11) DEFAULT 0,
  PRIMARY KEY (`ID`)
);

INSERT INTO eZQuiz_Alternative VALUES (1,1,'',0);
INSERT INTO eZQuiz_Alternative VALUES (2,2,'test 1',1);
INSERT INTO eZQuiz_Alternative VALUES (3,2,'test 2',0);

CREATE TABLE eZQuiz_Answer (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  UserID int(11) DEFAULT 0,
  AlternativeID int(11) DEFAULT 0,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZQuiz_Game (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(30) DEFAULT NULL,
  Description text DEFAULT NULL,
  StartDate int(11) DEFAULT NULL,
  StopDate int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
);

INSERT INTO eZQuiz_Game VALUES (1,'test','wegwegweg',UNIX_TIMESTAMP('2001-12-12'),null);

CREATE TABLE eZQuiz_Question (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` char(100) DEFAULT NULL,
  GameID int(11) DEFAULT 0,
  Placement int(11) DEFAULT 0,
  Score int(11) DEFAULT 0,
  PRIMARY KEY (`ID`)
);

INSERT INTO eZQuiz_Question VALUES (1,'hei for you',1,0,0);
INSERT INTO eZQuiz_Question VALUES (2,'',1,1,0);

CREATE TABLE eZQuiz_Score (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  GameID int(11) DEFAULT 0,
  UserID int(11) DEFAULT 0,
  TotalScore int(11) DEFAULT 0,
  LastQuestion int(11) DEFAULT 0,
  FinishedGame int(1) DEFAULT 1,
  PRIMARY KEY (`ID`)
);


