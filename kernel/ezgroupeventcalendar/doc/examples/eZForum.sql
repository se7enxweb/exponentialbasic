--
-- Table structure for table `eZForum_Category`
--

CREATE TABLE eZForum_Category (
  ID int(11) NOT NULL default '0',
  Name varchar(20) default NULL,
  Description varchar(40) default NULL,
  IsPrivate int(11) default NULL,
  SectionID int(11) default '1',
  PRIMARY KEY  (ID)
) TYPE=MyISAM;

--
-- Dumping data for table `eZForum_Category`
--
INSERT INTO eZForum_Category VALUES (1000,'Community Calendar','Calendar Event Forum',0,5);

--
-- Table structure for table `eZForum_Forum`
--

CREATE TABLE eZForum_Forum (
  ID int(11) NOT NULL default '0',
  Name varchar(20) NOT NULL default '',
  Description varchar(40) default NULL,
  IsPrivate int(11) default NULL,
  ModeratorID int(11) NOT NULL default '0',
  IsModerated int(11) NOT NULL default '0',
  GroupID int(11) default '0',
  IsAnonymous int(11) NOT NULL default '0',
  PRIMARY KEY  (ID)
) TYPE=MyISAM;

--
-- Dumping data for table `eZForum_Forum`
--

--
-- Table structure for table `eZForum_ForumCategoryLink`
--

CREATE TABLE eZForum_ForumCategoryLink (
  ID int(11) NOT NULL default '0',
  ForumID int(11) NOT NULL default '0',
  CategoryID int(11) NOT NULL default '0',
  PRIMARY KEY  (ID),
  KEY eZForumForumCategoryLink_ForumID (ForumID)
) TYPE=MyISAM;

--
-- Dumping data for table `eZForum_ForumCategoryLink`
--


--
-- Table structure for table `eZForum_Message`
--

CREATE TABLE eZForum_Message (
  ID int(11) NOT NULL default '0',
  ForumID int(11) NOT NULL default '0',
  Topic varchar(60) default NULL,
  Body text,
  UserName varchar(60) default NULL,
  UserID int(11) default NULL,
  Parent int(11) default NULL,
  EmailNotice int(11) NOT NULL default '0',
  PostingTime int(11) NOT NULL default '0',
  TreeID int(11) NOT NULL default '0',
  ThreadID int(11) NOT NULL default '0',
  Depth int(11) NOT NULL default '0',
  IsApproved int(11) NOT NULL default '1',
  IsTemporary int(11) NOT NULL default '0',
  PRIMARY KEY  (ID),
  KEY Forum_TreeID (TreeID),
  KEY Forum_PostingTime (PostingTime),
  KEY Forum_ThreadID (ThreadID),
  KEY Forum_Depth (Depth),
  KEY Forum_ForumID (ForumID),
  KEY ForumMessage_IsTemporary (IsTemporary),
  KEY ForumMessage_IsApproved (IsApproved),
  KEY ForumMessage_PostingTime (PostingTime),
  KEY ForumMessage_TreeID (TreeID)
) TYPE=MyISAM;

--
-- Dumping data for table `eZForum_Message`
--


--
-- Table structure for table `eZForum_MessageWordLink`
--

CREATE TABLE eZForum_MessageWordLink (
  MessageID int(11) NOT NULL default '0',
  Frequency float default '0.2',
  WordID int(11) NOT NULL default '0',
  KEY ForumWordLink_MessageID (MessageID),
  KEY ForumWordLink_WordID (WordID)
) TYPE=MyISAM;

--
-- Dumping data for table `eZForum_MessageWordLink`
--


--
-- Table structure for table `eZForum_Word`
--

CREATE TABLE eZForum_Word (
  ID int(11) NOT NULL default '0',
  Frequency float default '0.2',
  Word varchar(50) NOT NULL default '',
  UNIQUE KEY ForumWord_ID (ID),
  KEY ForumWord_Word (Word)
) TYPE=MyISAM;

--
-- Dumping data for table `eZForum_Word`
--

