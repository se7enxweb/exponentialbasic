CREATE TABLE eZForum_Category (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(255) DEFAULT NULL,
  Description varchar(255) DEFAULT NULL,
  IsPrivate int(11) DEFAULT NULL,
  SectionID int(11) DEFAULT 1);

CREATE TABLE eZForum_Forum (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(255) NOT NULL DEFAULT '',
  Description varchar(255) DEFAULT NULL,
  IsPrivate int(11) DEFAULT NULL,
  ModeratorID int(11) NOT NULL DEFAULT 0,
  IsModerated int(11) NOT NULL DEFAULT 0,
  GroupID int(11) DEFAULT 0,
  IsAnonymous int(11) NOT NULL DEFAULT 0);

CREATE TABLE eZForum_ForumCategoryLink (
  `ID` int(11) NOT NULL DEFAULT 0,
  ForumID int(11) NOT NULL DEFAULT 0,
  CategoryID int(11) NOT NULL DEFAULT 0);

CREATE TABLE eZForum_Message (
  `ID` int(11) NOT NULL DEFAULT 0,
  ForumID int(11) NOT NULL DEFAULT 0,
  Topic varchar(255) DEFAULT NULL,
  `Body` text DEFAULT NULL,
  UserName varchar(60) DEFAULT NULL,
  UserID int(11) DEFAULT NULL,
  Parent int(11) DEFAULT NULL,
  EmailNotice int(11) NOT NULL DEFAULT 0,
  PostingTime int(11) NOT NULL DEFAULT 0,
  TreeID int(11) NOT NULL DEFAULT 0,
  ThreadID int(11) NOT NULL DEFAULT 0,
  Depth int(11) NOT NULL DEFAULT 0,
  IsApproved int(11) NOT NULL DEFAULT 1,
  IsTemporary int(11) NOT NULL DEFAULT 0);

CREATE TABLE eZForum_MessageWordLink (
  MessageID int(11) NOT NULL DEFAULT 0,
  Frequency float DEFAULT 0.2,
  WordID int(11) NOT NULL DEFAULT 0);

CREATE TABLE eZForum_Word (
  `ID` int(11) NOT NULL DEFAULT 0,
  Frequency float DEFAULT 0.2,
  Word varchar(50) NOT NULL DEFAULT '');

CREATE INDEX Forum_TreeID ON eZForum_Message (TreeID);
CREATE INDEX Forum_PostingTime ON eZForum_Message (PostingTime);
CREATE INDEX Forum_ThreadID ON eZForum_Message (ThreadID);
CREATE INDEX Forum_Depth ON eZForum_Message (Depth);
CREATE INDEX Forum_ForumID ON eZForum_Message (ForumID);

CREATE INDEX ForumMessage_IsTemporary ON eZForum_Message (IsTemporary);
CREATE INDEX ForumMessage_IsApproved ON eZForum_Message (IsApproved);
CREATE INDEX ForumMessage_PostingTime ON eZForum_Message (PostingTime);
CREATE INDEX ForumMessage_TreeID ON eZForum_Message (TreeID);

CREATE INDEX ForumWordLink_MessageID ON eZForum_MessageWordLink (MessageID);
CREATE INDEX ForumWordLink_WordID ON eZForum_MessageWordLink (WordID);
CREATE INDEX ForumWord_Word ON eZForum_Word (Word);
CREATE UNIQUE INDEX ForumWord_ID ON eZForum_Word (ID);

CREATE INDEX eZForumForumCategoryLink_ForumID ON eZForum_ForumCategoryLink (ForumID);

DROP TABLE IF EXISTS eZGroupEventCalendar_Event;
#
# Table structure for table 'eZGroupEventCalendar_Event'
#
INSERT INTO eZForum_Category VALUES (1000,'Community Calendar','Calendar Event Forum',0,1);

