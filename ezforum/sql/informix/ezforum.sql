CREATE TABLE eZForum_Category (
  ID int NOT NULL,
  Name varchar(255) default NULL,
  Description varchar(255) default NULL,
  IsPrivate int default NULL,
  SectionID int DEFAULT 1,
  PRIMARY KEY (ID)
);

CREATE TABLE eZForum_Forum (
  ID int NOT NULL,
  Name varchar(255) NOT NULL,
  Description varchar(255) not null,
  IsPrivate int default NULL,
  ModeratorID int NOT NULL,
  IsModerated int NOT NULL,
  GroupID int,
  IsAnonymous int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZForum_ForumCategoryLink (
  ID int NOT NULL,
  ForumID int NOT NULL,
  CategoryID int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZForum_Message (
  ID int NOT NULL,
  ForumID int NOT NULL,
  Topic varchar(255) default NULL,
  Body lvarchar,
  UserName varchar(60) DEFAULT NULL,
  UserID int default NULL,
  Parent int default NULL,
  EmailNotice int NOT NULL,
  PostingTime int NOT NULL,
  TreeID int default NULL,
  ThreadID int default NULL,
  Depth int default NULL,
  IsApproved int NOT NULL,
  IsTemporary int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZForum_MessageWordLink (
  MessageID int NOT NULL DEFAULT '0',
  Frequency float DEFAULT 0.2,
  WordID int NOT NULL DEFAULT '0'
);

CREATE TABLE eZForum_Word (
  ID int NOT NULL DEFAULT '0',
  Frequency float DEFAULT 0.2,
  Word varchar(50) NOT NULL DEFAULT ''
);

CREATE INDEX Forum_TreeID ON eZForum_Message (TreeID);
CREATE INDEX Forum_PostingTime ON eZForum_Message (PostingTime);
CREATE INDEX Forum_ThreadID ON eZForum_Message (ThreadID);
CREATE INDEX Forum_Depth ON eZForum_Message (Depth);
CREATE INDEX Forum_ForumID ON eZForum_Message (ForumID);

CREATE INDEX ForumWordLink_MessageID ON eZForum_MessageWordLink (MessageID);
CREATE INDEX ForumWordLink_WordID ON eZForum_MessageWordLink (WordID);
CREATE INDEX ForumWord_Word ON eZForum_Word (Word);
CREATE UNIQUE INDEX ForumWord_ID ON eZForum_Word (ID);


