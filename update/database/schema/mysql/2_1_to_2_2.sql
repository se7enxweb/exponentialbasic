#
# Table structure for table 'eZExample_Test'
#

DROP TABLE IF EXISTS eZExample_Test;
CREATE TABLE eZExample_Test (
  ID int(11) NOT NULL auto_increment,
  Text char(100) default NULL,
  Created timestamp,
  PRIMARY KEY (ID)
);


#
# Dumping data for table 'eZExample_Test'
#


#
# Table structure for table 'eZQuiz_Alternative'
#

DROP TABLE IF EXISTS eZQuiz_Alternative;
CREATE TABLE eZQuiz_Alternative (
  ID int(11) NOT NULL auto_increment,
  QuestionID int(11) default '0',
  Name char(100) default NULL,
  IsCorrect int(11) default '0',
  PRIMARY KEY (ID)
)

#
# Dumping data for table 'eZQuiz_Alternative'
#

INSERT INTO eZQuiz_Alternative VALUES (1,1,'',0);
INSERT INTO eZQuiz_Alternative VALUES (2,2,'test 1',1);
INSERT INTO eZQuiz_Alternative VALUES (3,2,'test 2',0);

#
# Table structure for table 'eZQuiz_Answer'
#

DROP TABLE IF EXISTS eZQuiz_Answer;
CREATE TABLE eZQuiz_Answer (
  ID int(11) NOT NULL auto_increment,
  UserID int(11) default '0',
  AlternativeID int(11) default '0',
  PRIMARY KEY (ID)
)

#
# Dumping data for table 'eZQuiz_Answer'
#


#
# Table structure for table 'eZQuiz_Game'
#

DROP TABLE IF EXISTS eZQuiz_Game;
CREATE TABLE eZQuiz_Game (
  ID int(11) NOT NULL auto_increment,
  Name varchar(30) default NULL,
  Description text,
  StartDate date default NULL,
  StopDate date default NULL,
  PRIMARY KEY (ID)
)

#
# Dumping data for table 'eZQuiz_Game'
#

INSERT INTO eZQuiz_Game VALUES (1,'test','wegwegweg','2001-12-12','0000-00-00');

#
# Table structure for table 'eZQuiz_Question'
#

DROP TABLE IF EXISTS eZQuiz_Question;
CREATE TABLE eZQuiz_Question (
  ID int(11) NOT NULL auto_increment,
  Name char(100) default NULL,
  GameID int(11) default '0',
  Placement int(11) default '0',
  Score int(11) default '0',
  PRIMARY KEY (ID)
)

#
# Dumping data for table 'eZQuiz_Question'
#

INSERT INTO eZQuiz_Question VALUES (1,'hei � h�',1,0,0);
INSERT INTO eZQuiz_Question VALUES (2,'',1,1,0);

#
# Table structure for table 'eZQuiz_Score'
#

DROP TABLE IF EXISTS eZQuiz_Score;
CREATE TABLE eZQuiz_Score (
  ID int(11) NOT NULL auto_increment,
  GameID int(11) default '0',
  UserID int(11) default '0',
  TotalScore int(11) default '0',
  LastQuestion int(11) default '0',
  FinishedGame int(1) default '1',
  PRIMARY KEY (ID)
)

#
# Dumping data for table 'eZQuiz_Score'
#

#
# Table structure for table 'eZQuiz_AllTimeScore'
#

DROP TABLE IF EXISTS eZQuiz_AllTimeScore;
CREATE TABLE eZQuiz_AllTimeScore (
  ID int(11) NOT NULL auto_increment,
  UserID int(11) default '0',
  TotalScore int(11) default '0',
  GamesPlayed int(11) default '0',
  PRIMARY KEY (ID)
)

#
# Dumping data for table 'eZQuiz_AllTimeScore'
#


#
# Table structure for table 'eZSession_Preferences'
#

DROP TABLE IF EXISTS eZSession_Preferences;
CREATE TABLE eZSession_Preferences (
  ID int(11) NOT NULL auto_increment,
  UserID int(11) NOT NULL default '0',
  Name char(50) default NULL,
  Value char(255) default NULL,
  GroupName char(50) default NULL,
  PRIMARY KEY (ID),
  KEY GroupName(GroupName,Name)
)


alter table eZTrade_OrderOptionValue add RemoteID varchar(100) default ''; 



# Author list
create table eZUser_Author( ID int primary key auto_increment, Name char(255), EMail char(255) );

# Photographer list
create table eZUser_Photographer( ID int primary key auto_increment, Name char(255), EMail char(255) );

#
# convert old author fields to new
#

# create author list
insert into eZUser_Author( Name ) select AuthorText from eZArticle_Article Group By AuthorText;

# Create a temp table
CREATE TABLE eZArticle_ArticleTmp (
  ID int(11) NOT NULL auto_increment,
  Name varchar(100) default NULL,
  Contents text,
  ContentsWriterID int default NULL,
  LinkText varchar(50) default NULL,
  AuthorID int(11) NOT NULL default '0',
  Modified timestamp(14) NOT NULL,
  Created timestamp(14) NOT NULL,
  PageCount int(11) default NULL,
  IsPublished enum('true','false') default 'false',
  Published timestamp(14) NOT NULL,
  Keywords text,
  Discuss int(11) default '0',
  PRIMARY KEY (ID)
)


insert into eZArticle_ArticleTmp( ID, Name, Contents, ContentsWriterID, LinkText, AuthorID, Modified, 
Created, PageCount, IsPublished, Published, Keywords, Discuss ) 
select  
Article.ID,
Article.Name,
Article.Contents,
Author.ID,
Article.LinkText,
Article.AuthorID,
Article.Modified,
Article.Created,
Article.PageCount,
Article.IsPublished,
Article.Published,
Article.Keywords,
Article.Discuss
from eZArticle_Article as Article, eZUser_Author as Author where Article.AuthorText=Author.Name;

# rename tables
alter table eZArticle_Article rename eZArticle_Article_backup;
alter table eZArticle_ArticleTmp rename eZArticle_Article;


# IMPORTANT !!
# If you need to restore the article table restore 
# drop table eZArticle_Article;
# alter table eZArticle_Article_backup rename eZArticle_Article;
# if not you can delete the backup table 
# drop table eZArticle_Article_backup;


# Article topic
create table eZArticle_Topic( ID int primary key auto_increment, Name char(255), Description text );
alter table eZArticle_Article add TopicID int not null default 0;   


#
# Table structure for table 'eZArticle_Attribute'
#
DROP TABLE IF EXISTS eZArticle_Attribute;
CREATE TABLE eZArticle_Attribute (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  TypeID int(11),
  Name char(150),
  Placement int(11),
  Created timestamp(14),
  PRIMARY KEY (ID),
  INDEX( Placement )
)

#
# Dumping data for table 'eZArticle_Attribute'
#

#
# Table structure for table 'eZArticle_AttributeValue'
#
DROP TABLE IF EXISTS eZArticle_AttributeValue;
CREATE TABLE eZArticle_AttributeValue (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ArticleID int(11),
  AttributeID int(11),
  Value text,
  PRIMARY KEY (ID),
  INDEX( ArticleID, AttributeID )
)

#
# Dumping data for table 'eZArticle_AttributeValue'
#

#
# Table structure for table 'eZArticle_Type'
#
DROP TABLE IF EXISTS eZArticle_Type;
CREATE TABLE eZArticle_Type (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(150),
  PRIMARY KEY (ID)
)

#
# Dumping data for table 'eZArticle_Type'
#

#
# Table structure for table 'eZArticle_ArticleTypeLink'
#
DROP TABLE IF EXISTS eZArticle_ArticleTypeLink;
CREATE TABLE eZArticle_ArticleTypeLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ArticleID int(11),
  TypeID int(11),
  PRIMARY KEY (ID)
)

#
# Dumping data for table 'eZArticle_ArticleTypeLink'
#

alter table eZArticle_Topic add Created timestamp;   

create table eZArticle_Log( ID int primary key auto_increment, ArticleID int not null, Created timestamp not null, Message text not null );     
alter table eZArticle_Log add UserID int not null;

alter table eZArticle_Article add StartDate timestamp default 0;  
alter table eZArticle_Article add StopDate timestamp default 0;

#alter table eZArticle_Category add SectionID int(11) default 0;

#
# Table structure for table 'eZArticle_ArticleFormDict'
#
DROP TABLE IF EXISTS eZArticle_ArticleFormDict;
CREATE TABLE eZArticle_ArticleFormDict (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ArticleID int(11),
  FormID int(11),
  PRIMARY KEY (ID)
)

#
# Table structure for table 'eZImageCatalogue_ImageCategoryDefinition'
#
DROP TABLE IF EXISTS eZImageCatalogue_ImageCategoryDefinition;
CREATE TABLE eZImageCatalogue_ImageCategoryDefinition (
  ID int(11) NOT NULL auto_increment,
  ImageID int,
  CategoryID int,
  PRIMARY KEY (ID)
)

#
# Table structure for table 'eZImageCatalogue_ImageMap'
#
DROP TABLE IF EXISTS eZImageCatalogue_ImageMap;
CREATE TABLE eZImageCatalogue_ImageMap (
  ID int(11) NOT NULL auto_increment,
  ImageID int(11) default NULL,
  Link varchar(50) NOT NULL default '',
  AltText text default '',
  Shape int(11) NOT NULL default '0',
  StartPosX int(11) NOT NULL default '0',
  StartPosY int(11) NOT NULL default '0',
  EndPosX int(11) NOT NULL default '0',
  EndPosY int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
)

#
# convert to new database types for database independence
#

# eZ session conversion
alter table eZSession_Session drop SecondLastAccessed;

alter table eZSession_Session add LastAccessedTmp int;
update eZSession_Session set LastAccessedTmp= UNIX_TIMESTAMP( LastAccessed );
alter table eZSession_Session drop LastAccessed; 
alter table eZSession_Session change LastAccessedTmp LastAccessed int; 

alter table eZSession_Session add CreatedTmp int;
update eZSession_Session set CreatedTmp= UNIX_TIMESTAMP( Created );
alter table eZSession_Session drop Created; 
alter table eZSession_Session change CreatedTmp Created int;

# eZ user conversion
alter table eZUser_User add InfoSubscriptionTmp int default '0';
update eZUser_User set InfoSubscriptionTmp='1' where InfoSubscription='true';
alter table eZUser_User drop InfoSubscription;
alter table eZUser_User change InfoSubscriptionTmp InfoSubscription int;

alter table eZUser_Cookie add TimeTmp int;
update eZUser_Cookie set TimeTmp= UNIX_TIMESTAMP( Time );
alter table eZUser_Cookie drop Time; 
alter table eZUser_Cookie change TimeTmp Time int; 

alter table eZUser_Forgot add TimeTmp int;
update eZUser_Forgot set TimeTmp= UNIX_TIMESTAMP( Time );
alter table eZUser_Forgot drop Time; 
alter table eZUser_Forgot change TimeTmp Time int; 

alter table eZUser_GroupPermissionLink add IsEnabledTmp int default '0';
update eZUser_GroupPermissionLink set IsEnabledTmp='1' where IsEnabled='true';
alter table eZUser_GroupPermissionLink drop IsEnabled;
alter table eZUser_GroupPermissionLink change IsEnabledTmp IsEnabled int;

# eZ sitemanager

alter table eZSiteManager_Section add CreatedTmp int;
update eZSiteManager_Section set CreatedTmp= UNIX_TIMESTAMP( Created );
alter table eZSiteManager_Section drop Created; 
alter table eZSiteManager_Section change CreatedTmp Created int;

# eZ link
alter table eZLink_Hit add TimeTmp int;
update eZLink_Hit set TimeTmp= UNIX_TIMESTAMP( Time );
alter table eZLink_Hit drop Time; 
alter table eZLink_Hit change TimeTmp Time int; 

alter table eZLink_Link add ModifiedTmp int;
update eZLink_Link set ModifiedTmp= UNIX_TIMESTAMP( Modified );
alter table eZLink_Link drop Modified; 
alter table eZLink_Link change ModifiedTmp Modified int; 

alter table eZLink_Link add AcceptedTmp int default '0';
update eZLink_Link set AcceptedTmp='1' where Accepted='y';
alter table eZLink_Link drop Accepted;
alter table eZLink_Link change AcceptedTmp Accepted int;

alter table eZLink_Link add CreatedTmp int;
update eZLink_Link set CreatedTmp= UNIX_TIMESTAMP( Created );
alter table eZLink_Link drop Created; 
alter table eZLink_Link change CreatedTmp Created int; 


DROP TABLE IF EXISTS eZLink_Category;
alter table eZLink_LinkGroup RENAME to eZLink_Category;


# eZ urltranslator
alter table eZURLTranslator_URL add CreatedTmp int;
update eZURLTranslator_URL set CreatedTmp= UNIX_TIMESTAMP( Created );
alter table eZURLTranslator_URL drop Created; 
alter table eZURLTranslator_URL change CreatedTmp Created int; 

CREATE TABLE eZLink_LinkGroup (
  ID int NOT NULL,
  Parent int default '0',
  Title varchar(100) default NULL,
  ImageID int default NULL,
  Description text,
  PRIMARY KEY (ID)
);

# eZ article
alter table eZArticle_Article add IsPublishedTmp int default '0';
update eZArticle_Article set IsPublishedTmp='1' where IsPublished='true';
alter table eZArticle_Article drop IsPublished;
alter table eZArticle_Article change IsPublishedTmp IsPublished int;
alter table eZArticle_Article add ImportID varchar(255); 


alter table eZArticle_Article add CreatedTmp int;
update eZArticle_Article set CreatedTmp= UNIX_TIMESTAMP( Created );
alter table eZArticle_Article drop Created; 
alter table eZArticle_Article change CreatedTmp Created int; 

alter table eZArticle_Article add ModifiedTmp int;
update eZArticle_Article set ModifiedTmp= UNIX_TIMESTAMP( Modified );
alter table eZArticle_Article drop Modified; 
alter table eZArticle_Article change ModifiedTmp Modified int; 

alter table eZArticle_Article add PublishedTmp int;
update eZArticle_Article set PublishedTmp= UNIX_TIMESTAMP( Published );
alter table eZArticle_Article drop Published; 
alter table eZArticle_Article change PublishedTmp Published int; 

alter table eZArticle_Article add StartDateTmp int;
update eZArticle_Article set StartDateTmp= UNIX_TIMESTAMP( StartDate );
alter table eZArticle_Article drop StartDate; 
alter table eZArticle_Article change StartDateTmp StartDate int; 

alter table eZArticle_Article add StopDateTmp int;
update eZArticle_Article set StopDateTmp= UNIX_TIMESTAMP( StopDate );
alter table eZArticle_Article drop StopDate; 
alter table eZArticle_Article change StopDateTmp StopDate int; 


alter table eZArticle_ArticleFileLink add CreatedTmp int;
update eZArticle_ArticleFileLink set CreatedTmp= UNIX_TIMESTAMP( Created );
alter table eZArticle_ArticleFileLink drop Created; 
alter table eZArticle_ArticleFileLink change CreatedTmp Created int; 

alter table eZArticle_ArticleImageLink add CreatedTmp int;
update eZArticle_ArticleImageLink set CreatedTmp= UNIX_TIMESTAMP( Created );
alter table eZArticle_ArticleImageLink drop Created; 
alter table eZArticle_ArticleImageLink change CreatedTmp Created int; 


alter table eZArticle_Attribute add CreatedTmp int;
update eZArticle_Attribute set CreatedTmp= UNIX_TIMESTAMP( Created );
alter table eZArticle_Attribute drop Created; 
alter table eZArticle_Attribute change CreatedTmp Created int; 

alter table eZArticle_Category add ExcludeFromSearchTmp int default '0';
update eZArticle_Category set ExcludeFromSearchTmp='1' where ExcludeFromSearch='true';
alter table eZArticle_Category drop ExcludeFromSearch;
alter table eZArticle_Category change ExcludeFromSearchTmp ExcludeFromSearch int;


alter table eZArticle_CategoryReaderLink add CreatedTmp int;
update eZArticle_CategoryReaderLink set CreatedTmp= UNIX_TIMESTAMP( Created );
alter table eZArticle_CategoryReaderLink drop Created; 
alter table eZArticle_CategoryReaderLink change CreatedTmp Created int; 

alter table eZArticle_Log add CreatedTmp int;
update eZArticle_Log set CreatedTmp= UNIX_TIMESTAMP( Created );
alter table eZArticle_Log drop Created; 
alter table eZArticle_Log change CreatedTmp Created int; 

alter table eZArticle_Topic add CreatedTmp int;
update eZArticle_Topic set CreatedTmp= UNIX_TIMESTAMP( Created );
alter table eZArticle_Topic drop Created; 
alter table eZArticle_Topic change CreatedTmp Created int; 


# eZ forum
alter table eZForum_Message add PostingTimeTmp int;
update eZForum_Message set PostingTimeTmp= UNIX_TIMESTAMP( PostingTime );
alter table eZForum_Message drop PostingTime; 
alter table eZForum_Message change PostingTimeTmp PostingTime int; 


# eZ poll

# rename field:
alter table eZPoll_PollChoice add Offs int;
update eZPoll_PollChoice set Offs=Offset;
alter table eZPoll_PollChoice drop Offset; 

alter table eZPoll_Poll add AnonymousTmp int default '0';
update eZPoll_Poll set AnonymousTmp='1' where Anonymous='true';
alter table eZPoll_Poll drop Anonymous;
alter table eZPoll_Poll change AnonymousTmp Anonymous int;

alter table eZPoll_Poll add IsEnabledTmp int default '0';
update eZPoll_Poll set IsEnabledTmp='1' where IsEnabled='true';
alter table eZPoll_Poll drop IsEnabled;
alter table eZPoll_Poll change IsEnabledTmp IsEnabled int;

alter table eZPoll_Poll add IsClosedTmp int default '0';
update eZPoll_Poll set IsClosedTmp='1' where IsClosed='true';
alter table eZPoll_Poll drop IsClosed;
alter table eZPoll_Poll change IsClosedTmp IsClosed int;

alter table eZPoll_Poll add ShowResultTmp int default '0';
update eZPoll_Poll set ShowResultTmp='1' where ShowResult='true';
alter table eZPoll_Poll drop ShowResult;
alter table eZPoll_Poll change ShowResultTmp ShowResult int;

# ez newfeed 

alter table eZNewsFeed_News add IsPublishedTmp int default '0';
update eZNewsFeed_News set IsPublishedTmp='1' where IsPublished='true';
alter table eZNewsFeed_News drop IsPublished;
alter table eZNewsFeed_News change IsPublishedTmp IsPublished int;

alter table eZNewsFeed_News add PublishingDateTmp int;
update eZNewsFeed_News set PublishingDateTmp= UNIX_TIMESTAMP( PublishingDate );
alter table eZNewsFeed_News drop PublishingDate; 
alter table eZNewsFeed_News change PublishingDateTmp PublishingDate int; 

alter table eZNewsFeed_News add OriginalPublishingDateTmp int;
update eZNewsFeed_News set OriginalPublishingDateTmp= UNIX_TIMESTAMP( OriginalPublishingDate );
alter table eZNewsFeed_News drop OriginalPublishingDate;
alter table eZNewsFeed_News change OriginalPublishingDateTmp OriginalPublishingDate int; 

alter table eZNewsFeed_SourceSite add IsActiveTmp int default '0';
update eZNewsFeed_SourceSite set IsActiveTmp='1' where IsActive='true';
alter table eZNewsFeed_SourceSite drop IsActive;
alter table eZNewsFeed_SourceSite change IsActiveTmp IsActive int; 

alter table eZNewsFeed_Category change Name Name varchar(150);

alter table eZNewsFeed_News change Name Name varchar(150);
alter table eZNewsFeed_News change KeyWords KeyWords varchar(200);
alter table eZNewsFeed_News change URL URL varchar(200);
alter table eZNewsFeed_News change Origin Origin varchar(150);

alter table eZNewsFeed_SourceSite change URL URL varchar(250);
alter table eZNewsFeed_SourceSite change Login Login varchar(30);
alter table eZNewsFeed_SourceSite change Password Password varchar(30);
alter table eZNewsFeed_SourceSite change Decoder Decoder varchar(50);

# ez trade
alter table eZTrade_Product change ShowPrice ShowPrice int default '1';
alter table eZTrade_Product change ShowProduct ShowProduct int default '1';
alter table eZTrade_Product change Discontinued Discontinued int default '0';
alter table eZTrade_Product change IsHotDeal IsHotDeal int default '0';
alter table eZTrade_Product drop InheritOptions;
alter table eZTrade_Product drop Altered;


alter table eZImageCatalogue_Image add PhotographerID int;
alter table eZImageCatalogue_Image add Created int;


# Product type
alter table eZTrade_Product add ProductType int default 1;

# Attributes in eZLink

DROP TABLE IF EXISTS eZLink_Attribute;
CREATE TABLE eZLink_Attribute (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  TypeID int(11),
  Name varchar(150),
  Created int(11),
  Placement int(11) DEFAULT '0',
  Unit varchar(8),
  PRIMARY KEY(ID)
);

DROP TABLE IF EXISTS eZLink_AttributeValue;
CREATE TABLE eZLink_AttributeValue (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  LinkID int(11),
  AttributeID int(11),
  Value char(200),
  PRIMARY KEY(ID)
);

DROP TABLE IF EXISTS eZLink_Type;
CREATE TABLE eZLink_Type (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(150),
  PRIMARY KEY(ID)
);

DROP TABLE IF EXISTS eZLink_TypeLink;
CREATE TABLE eZLink_TypeLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  LinkID int(11),
  TypeID int(11),
  PRIMARY KEY(ID)
);

DROP TABLE IF EXISTS eZStats_Archive_RequestedPage;
CREATE TABLE eZStats_Archive_RequestedPage (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Month int(11),
  URI char(250),
  Count int DEFAULT '0' NOT NULL,
  PRIMARY KEY(ID)
);

DROP TABLE IF EXISTS eZStats_Archive_PageView;
CREATE TABLE eZStats_Archive_PageView (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Hour int(11),
  Count int DEFAULT '0' NOT NULL,
  PRIMARY KEY(ID)
);

DROP TABLE IF EXISTS eZStats_Archive_UniqueVisits;
CREATE TABLE eZStats_Archive_UniqueVisits (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Day int(11),
  Count int DEFAULT '0' NOT NULL,
  PRIMARY KEY(ID)
);

DROP TABLE IF EXISTS eZStats_Archive_BrowserType;
CREATE TABLE eZStats_Archive_BrowserType (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Browser char(250),
  Count int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY(ID)
);

DROP TABLE IF EXISTS eZStats_Archive_RefererURL;
CREATE TABLE eZStats_Archive_RefererURL (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Month int(11),
  Domain char(100) default NULL,
  URI char(200) default NULL,
  Count int DEFAULT '0' NOT NULL,
  PRIMARY KEY(ID)
);

DROP TABLE IF EXISTS eZStats_Archive_RemoteHost;
CREATE TABLE eZStats_Archive_RemoteHost (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  IP char(15) default NULL,
  HostName char(150) default NULL,
  Count int DEFAULT '0' NOT NULL,
  PRIMARY KEY(ID)
);

DROP TABLE IF EXISTS eZStats_Archive_Users;
CREATE TABLE eZStats_Archive_Users (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  UserID int(11),
  Month int(11),
  Count int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY(ID)
);

alter table eZStats_PageView add DateTmp int;
update eZStats_PageView set DateTmp= UNIX_TIMESTAMP( Date );
alter table eZStats_PageView drop Date; 
alter table eZStats_PageView change DateTmp Date int; 

#
#alter table eZStats_PageView add DateTimeTmp int;
#update eZStats_PageView set DateTimeTmp= UNIX_TIMESTAMP( DateTime );
#alter table eZStats_PageView drop DateTime; 
#alter table eZStats_PageView change DateTimeTmp Date int; 

alter table eZStats_PageView add DateValueTmp int;
update eZStats_PageView set  DateValueTmp= UNIX_TIMESTAMP( DateValue );
alter table eZStats_PageView drop DateValue; 
alter table eZStats_PageView change DateValueTmp DateValue int; 


INSERT INTO eZLink_LinkCategoryLink ( LinkID, CategoryID ) SELECT ID, LinkGroup from eZLink_Link;
INSERT INTO eZLink_LinkCategoryDefinition ( LinkID, CategoryID ) SELECT ID, LinkGroup from eZLink_Link;
ALTER TABLE eZLink_Link DROP LinkGroup;

alter table eZLink_Link change Title Name varchar(100);
alter table eZLink_Category change Title Name varchar(100);

# eZBulkMail

alter table eZBulkMail_SentLog add SentDateTmp int;
update eZBulkMail_SentLog set SentDateTmp= UNIX_TIMESTAMP( SentDate );
alter table eZBulkMail_SentLog drop SentDate; 
alter table eZBulkMail_SentLog change SentDateTmp SentDate int; 

# eZ forum
alter table eZBulkMail_Mail add SentDateTmp int;
update eZBulkMail_Mail set SentDateTmp= UNIX_TIMESTAMP( SentDate );
alter table eZBulkMail_Mail drop SentDate; 
alter table eZBulkMail_Mail change SentDateTmp SentDate int; 

alter table eZBulkMail_Forgot add TimeTmp int;
update eZBulkMail_Forgot set TimeTmp= UNIX_TIMESTAMP( Time );
alter table eZBulkMail_Forgot drop Time; 
alter table eZBulkMail_Forgot change TimeTmp Time int; 


## fulltext search index tables
Create table eZArticle_Word ( 
  ID int not null,
  Word varchar(50) not null,
  PRIMARY KEY (ID)
);

CREATE TABLE eZArticle_ArticleWordLink (
  ArticleID int not null,
  WordID int not null
);


CREATE INDEX ArticleWord_Word ON eZArticle_Word (Word);
CREATE INDEX ArticleWordLink_ArticleID ON eZArticle_ArticleWordLink (ArticleID);
CREATE INDEX ArticleWordLink_WordID ON eZArticle_ArticleWordLink (WordID);

CREATE INDEX ArticlePermissionObjectID ON eZArticle_ArticlePermission (ObjectID);
CREATE INDEX ArticlePermissionGroupID ON eZArticle_ArticlePermission (GroupID);
CREATE INDEX ArticlePermissionWritePermission ON eZArticle_ArticlePermission (WritePermission);
CREATE INDEX ArticlePermissionReadPermission ON eZArticle_ArticlePermission (ReadPermission);


CREATE INDEX Article_Name ON eZArticle_Article (Name);
CREATE INDEX Article_Published ON eZArticle_Article (Published);

CREATE INDEX Link_ArticleID ON eZArticle_ArticleCategoryLink (ArticleID);
CREATE INDEX Link_CategoryID ON eZArticle_ArticleCategoryLink (CategoryID);
CREATE INDEX Link_Placement ON eZArticle_ArticleCategoryLink (Placement);

CREATE INDEX Def_ArticleID ON eZArticle_ArticleCategoryDefinition (ArticleID);
CREATE INDEX Def_CategoryID ON eZArticle_ArticleCategoryDefinition (CategoryID);

# eZ mediacatalogue

DROP TABLE IF EXISTS eZMediaCatalogue_Category;
CREATE TABLE eZMediaCatalogue_Category (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(100),
  Description text,
  ParentID int(11),
  UserID int(11),
  WritePermission int(11) DEFAULT '1',
  ReadPermission int(11) DEFAULT '1',
  PRIMARY KEY (ID)
);

DROP TABLE IF EXISTS eZMediaCatalogue_CategoryPermission;
CREATE TABLE eZMediaCatalogue_CategoryPermission (
  ID int(11) NOT NULL auto_increment,
  ObjectID int(11) default NULL,
  GroupID int(11) default NULL,
  ReadPermission int(11) default '0',
  WritePermission int(11) default '0',
  PRIMARY KEY (ID)
)

DROP TABLE IF EXISTS eZArticle_ArticleMediaLink;
CREATE TABLE eZArticle_ArticleMediaLink (
  ID int(11) NOT NULL auto_increment,
  ArticleID int(11) NOT NULL default '0',
  MediaID int(11) NOT NULL default '0',
  Created int(11) default NULL,
  PRIMARY KEY (ID)
)


DROP TABLE IF EXISTS eZUser_Trustees;
CREATE TABLE eZUser_Trustees (
  ID int(11) NOT NULL auto_increment,
  OwnerID int(11) NOT NULL,
  UserID int(11) NOT NULL,
  PRIMARY KEY (ID)
)



DROP TABLE IF EXISTS eZMessage_Message;
CREATE TABLE eZMessage_Message (
  ID int(11) NOT NULL auto_increment,
  FromUserID int(11) NOT NULL default '0',
  ToUserID int(11) NOT NULL default '0',
  Created timestamp(14) NOT NULL,
  IsRead int(11) NOT NULL default '0',
  Subject varchar(255) NOT NULL default '',
  Description text,
  PRIMARY KEY (ID)
)

alter table eZAddress_Country add HasVAT int default 1;
alter table eZTrade_OrderItem add PriceIncVAT float(10,2);
alter table eZTrade_OrderItem add VATValue int;
alter table eZTrade_Order add IsVATInc int default 0;

CREATE TABLE eZPoll_PollForumLink (
  ID int NOT NULL,
  PollID int NOT NULL default '0',
  ForumID int NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_ProductPermissionLink (
  ID int NOT NULL,
  ProductID int NOT NULL default '0',
  GroupID int NOT NULL default '0',
  PRIMARY KEY (ID)
);

alter table eZBug_Bug change Created Created int;
alter table eZBug_Bug change IsHandled IsHandled int default 0;
alter table eZBug_Bug change IsClosed IsClosed int default 0;
alter table eZBug_Bug change OwnerID OwnerID int default 0;
alter table eZBug_Bug change IsPrivate IsPrivate int default 0;

#alter table eZBug_Module add OwnerGroupID int default 0;

#CREATE TABLE eZBug_BugFileLink (
#  ID int NOT NULL,
#  BugID int NOT NULL default '0',
#  FileID int NOT NULL default '0',
#  Created int NOT NULL,
#  PRIMARY KEY (ID)
#);
 
#CREATE TABLE eZBug_BugImageLink (
#  ID int NOT NULL,
#  BugID int NOT NULL default '0',
#  ImageID int NOT NULL default '0',
#  Created int NOT NULL,
#  PRIMARY KEY (ID)
#);


alter table eZBug_Log change Created Created int;

alter table eZUser_Group add GroupURL varchar(200);

CREATE TABLE eZUser_UserGroupDefinition (
  ID int NOT NULL,
  UserID int NOT NULL default '0',
  GroupID int NOT NULL default '0',
  PRIMARY KEY (ID)
);

alter table eZArticle_Category add EditorGroupID int default 0;

alter table eZSiteManager_Section add TemplateStyle varchar(255);

CREATE TABLE eZMail_MailContactLink (
  ID int NOT NULL,
  MailID int NOT NULL default '0',
  PersonID int,
  CompanyID int,
  PRIMARY KEY (ID)
);

ALTER TABLE eZArticle_ArticleImageLink ADD Placement int not null default 0;

ALTER TABLE eZTrade_Product ADD ExpiryTime int not null default 0;


# ez ad

alter table eZAd_Ad add IsActiveTmp int default '0';
update eZAd_Ad set IsActiveTmp='1' where IsActive='true';
alter table eZAd_Ad drop IsActive;
alter table eZAd_Ad change IsActiveTmp IsActive int;

alter table eZAd_Category add ExcludeFromSearchTmp int default '0';
update eZAd_Category set ExcludeFromSearchTmp='1' where ExcludeFromSearch='true';
alter table eZAd_Category drop ExcludeFromSearch;
alter table eZAd_Category change ExcludeFromSearchTmp ExcludeFromSearch int;

alter table eZAd_Ad drop ViewRule;
alter table eZAd_Ad drop ViewStartDate;
alter table eZAd_Ad drop ViewStopDate;

alter table eZAd_View add DateTmp int default '0';
update eZAd_View set DateTmp= UNIX_TIMESTAMP( Date );
alter table eZAd_View drop Date;
alter table eZAd_View change DateTmp Date int;

alter table eZAd_View add ViewOffsetCount int;

alter table eZTrade_OrderItem add ExpiryDate int;             

#
# Table structure for table 'eZTrade_Voucher'
#
 
CREATE TABLE eZTrade_Voucher (
  ID int(11) NOT NULL default '0',
  Created int(11) default '0',
  Price float default '0',
  Available int(11) default '0',
  KeyNumber varchar(50) default NULL,
  MailMethod int(11) default '1',
  UserID int(11) default '0',
  ProductID int(11) default '0',
  PRIMARY KEY  (ID)
)

#
# Table structure for table 'eZTrade_CategoryPermission'
#
 
CREATE TABLE eZTrade_CategoryPermission (
  ID int(11) NOT NULL auto_increment,
  ObjectID int(11) default NULL,
  GroupID int(11) default NULL,
  ReadPermission int(11) default '0',
  WritePermission int(11) default '0',
  PRIMARY KEY (ID)
)
 
#
# Table structure for table 'eZTrade_ProductPermission'
#
 
CREATE TABLE eZTrade_ProductPermission (
  ID int(11) NOT NULL auto_increment,
  ObjectID int(11) default NULL,
  GroupID int(11) default NULL,
  ReadPermission int(11) default '0',
  WritePermission int(11) default '0',
  PRIMARY KEY (ID),
  KEY ProductPermissionObjectID(ObjectID),
  KEY ProductPermissionGroupID(GroupID),
  KEY ProductPermissionWritePermission(WritePermission),
  KEY ProductPermissionReadPermission(ReadPermission)
)

# eZTrade_Product

alter table eZTrade_Product add PublishedTmp int;
update eZTrade_Product set PublishedTmp= UNIX_TIMESTAMP( Published );
alter table eZTrade_Product drop Published; 
alter table eZTrade_Product change PublishedTmp Published int;

ALTER TABLE eZImageCatalogue_Image ADD Keywords varchar(255);

alter table eZContact_Consultation add DateTmp int;
update eZContact_Consultation set DateTmp= UNIX_TIMESTAMP( Date );
alter table eZContact_Consultation drop Date; 
alter table eZContact_Consultation change DateTmp Date int;

drop table if exists eZContact_ImageType;

alter table eZContact_CompanyView add DateTmp int;
update eZContact_CompanyView set DateTmp= UNIX_TIMESTAMP( Date );
alter table eZContact_CompanyView drop Date; 
alter table eZContact_CompanyView change DateTmp Date int;

CREATE TABLE eZContact_CompanyImageDict (
  CompanyID int DEFAULT '0' NOT NULL,
  ImageID int DEFAULT '0' NOT NULL,
  PRIMARY KEY (CompanyID,ImageID)
);

alter table eZCalendar_Appointment add DateTmp int;
update eZCalendar_Appointment set DateTmp= UNIX_TIMESTAMP( Date );
alter table eZCalendar_Appointment drop Date; 
alter table eZCalendar_Appointment change DateTmp Date int;

alter table eZCalendar_AppointmentType change Description Description varchar(200);

alter table eZArticle_Article_backup add ModifiedTmp int;
update eZArticle_Article_backup set ModifiedTmp= UNIX_TIMESTAMP( Modified );
alter table eZArticle_Article_backup drop Modified; 
alter table eZArticle_Article_backup change ModifiedTmp Modified int;

alter table eZArticle_Article_backup add CreatedTmp int;
update eZArticle_Article_backup set CreatedTmp= UNIX_TIMESTAMP( Created );
alter table eZArticle_Article_backup drop Created; 
alter table eZArticle_Article_backup change CreatedTmp Created int;

alter table eZArticle_Article_backup add PublishedTmp int;
update eZArticle_Article_backup set PublishedTmp= UNIX_TIMESTAMP( Published );
alter table eZArticle_Article_backup drop Published; 
alter table eZArticle_Article_backup change PublishedTmp Published int;

alter table eZExample_Test add CreatedTmp int;
update eZExample_Test set CreatedTmp= UNIX_TIMESTAMP( Created );
alter table eZExample_Test drop Created; 
alter table eZExample_Test change CreatedTmp Created int;

# eZTrade:

alter table eZTrade_AlternativeCurrency add CreatedTmp int;
update eZTrade_AlternativeCurrency set CreatedTmp= UNIX_TIMESTAMP( Created );
alter table eZTrade_AlternativeCurrency drop Created; 
alter table eZTrade_AlternativeCurrency change CreatedTmp Created int;

alter table eZTrade_Attribute add CreatedTmp int;
update eZTrade_Attribute set CreatedTmp= UNIX_TIMESTAMP( Created );
alter table eZTrade_Attribute drop Created; 
alter table eZTrade_Attribute change CreatedTmp Created int;

alter table eZTrade_OrderStatus add AlteredTmp int;
update eZTrade_OrderStatus set AlteredTmp= UNIX_TIMESTAMP( Altered );
alter table eZTrade_OrderStatus drop Altered; 
alter table eZTrade_OrderStatus change AlteredTmp Altered int;

alter table eZTrade_PreOrder add CreatedTmp int;
update eZTrade_PreOrder set CreatedTmp= UNIX_TIMESTAMP( Created );
alter table eZTrade_PreOrder drop Created; 
alter table eZTrade_PreOrder change CreatedTmp Created int;

alter table eZTrade_ProductImageLink add CreatedTmp int;
update eZTrade_ProductImageLink set CreatedTmp= UNIX_TIMESTAMP( Created );
alter table eZTrade_ProductImageLink drop Created; 
alter table eZTrade_ProductImageLink change CreatedTmp Created int;

alter table eZTrade_ShippingGroup add CreatedTmp int;
update eZTrade_ShippingGroup set CreatedTmp= UNIX_TIMESTAMP( Created );
alter table eZTrade_ShippingGroup drop Created; 
alter table eZTrade_ShippingGroup change CreatedTmp Created int;

alter table eZTrade_ShippingType add CreatedTmp int;
update eZTrade_ShippingType set CreatedTmp= UNIX_TIMESTAMP( Created );
alter table eZTrade_ShippingType drop Created; 
alter table eZTrade_ShippingType change CreatedTmp Created int;

alter table eZTrade_VATType add CreatedTmp int;
update  eZTrade_VATType set CreatedTmp= UNIX_TIMESTAMP( Created );
alter table  eZTrade_VATType drop Created; 
alter table  eZTrade_VATType change CreatedTmp Created int;

# eZMessage

alter table eZMessage_Message add CreatedTmp int;
update  eZMessage_Message set CreatedTmp= UNIX_TIMESTAMP( Created );
alter table  eZMessage_Message drop Created; 
alter table  eZMessage_Message change CreatedTmp Created int;

alter table eZAddress_Address add Name varchar(50);

alter table eZTrade_OrderItem drop PriceIncVAT;
alter table eZTrade_OrderItem change VATValue VAT float(10,2);
alter table eZContact_Person change BirthDate BirthDate int;

# XML contents in trade
alter table eZTrade_Product add Contents text not null;
Update eZTrade_Product set Contents=Concat( "<?xml version=\"1.0\"?><article><generator>qdom</generator><intro>", Brief, "</intro><body><page>", Description, "</page></body></article>" );  

alter table eZTrade_ProductImageLink add Placement int not null default 0; 
alter table eZForum_Message add UserName varchar(60) default null;

#Languages in sections
alter table eZSiteManager_Section add Language varchar(5) default NULL;

alter table eZTrade_Option add RemoteID varchar(100);
alter table eZTrade_CartOptionValue add Count int default 1;
alter table eZTrade_Cart add PersonID int default 0;
alter table eZTrade_Cart add CompanyID int default 0;

alter table eZTrade_Order add Comment text;

/* speeding up the keywords */
alter table eZArticle_ArticleKeyword add index (Keyword);
alter table eZArticle_ArticleKeyword add index (ArticleID);
create table eZBulkMail_UserCategoryLink ( UserID int default 0, CategoryID int default 0 ); 

create table eZBulkMail_UserSubscriptionCategorySettings( CategoryID int default 0, UserID int default 0, Delay int default 0 );

# Article search
alter table eZArticle_ArticleWordLink add Frequency float default 0.2;
alter table eZArticle_Word add Frequency float default 0.2;

# cache of article index
CREATE TABLE eZArticle_ArticleKeywordFirstLetter (
  ID int(11) NOT NULL default '0',
  Letter char(1) NOT NULL default ''
);

CREATE TABLE eZTrade_VoucherUsed (
  ID int(11) NOT NULL default '0',
  Used int(11) default '0',
  Price float default NULL,
  VoucherID int(11) default '0',
  OrderID int(11) default '0',
  UserID int(11) default '0',
  PRIMARY KEY  (ID)
);

CREATE TABLE eZTrade_ProductPriceRange( ID int NOT NULL, Min int default 0, Max int default 0, ProductID int default 0 );

alter table eZBulkMail_UserSubscriptionCategorySettings rename eZBulkMail_UserCategorySettings;  

create table eZBulkMail_UserCategoryDelay ( ID int NOT NULL, CategoryID int default 0, UserID int default 0, Delay int default 0, MailID int default 0 );   

alter table eZTrade_Product add IncludesVAT int(1) default '0';

alter table eZArticle_Category add ListLimit int default '0';

alter table eZCalendar_Appointment add AllDay int default 0;
alter table eZFileManager_FolderPermission add UploadPermission int(11) default '0';
alter table eZImageCatalogue_CategoryPermission add UploadPermission int(11) default '0';

insert into eZUser_Permission (ModuleID, Name) VALUES ('6', 'Buy');

alter table eZTrade_CartItem add VoucherInformationID int default 0;

alter table eZTrade_Category add SectionID int default 1;

alter table eZForum_Category add SectionID int default 1;       

alter table eZFileManager_Folder add SectionID int default 1;

alter table eZImageCatalogue_Category add SectionID int default '1';

alter table eZLink_Category add SectionID int default '1';

CREATE TABLE eZTrade_VoucherInformation (
  ID int(11) NOT NULL default '0',
  VoucherID int(11) default '0',
  OnlineID int(11) default '0',
  ToAddressID int(11) default '0',
  Description text,
  PreOrderID int(11) default '0',
  Price int(11) default '0',
  MailMethod int(11) default '1',
  ToName varchar(80) default NULL,
  FromName varchar(80) default NULL,
  FromOnlineID int(11) default '0',
  FromAddressID int(11) default '0',
  ProductID int(11) default '0',
  PRIMARY KEY  (ID)
);

CREATE TABLE eZForum_MessageWordLink (
  MessageID int(11) NOT NULL default '0',
  Frequency float default 0.2,
  WordID int(11) NOT NULL default '0'
);

CREATE TABLE eZForum_Word (
  ID int(11) NOT NULL default '0',
  Frequency float default 0.2,
  Word varchar(50) NOT NULL default ''
);

alter table eZSession_SessionVariable change Value Value text;

alter table eZTrade_ProductImageLink change Placement Placement int default 0;  
update eZTrade_ProductImageLink set Placement='0' where Placement IS NULL;
alter table eZTrade_OrderOptionValue change OptionName OptionName text;
alter table eZTrade_OrderOptionValue change ValueName ValueName text;

CREATE TABLE eZSiteManager_SectionFrontPageRow (
  ID int(11) NOT NULL default '0',
  SettingID int(11) default '0',
  CategoryID int(11) default '0',
  Placement int(11) default '0',
  PRIMARY KEY (ID)
)
 
CREATE TABLE eZSiteManager_SectionFrontPageRowLink (
  ID int(11) NOT NULL default '0',
  FrontPageID int(11) default '0',
  SectionID int(11) default '0',
  PRIMARY KEY (ID)
)
 
CREATE TABLE eZSiteManager_SectionFrontPageSetting (
  ID int(11) NOT NULL default '0',
  Name varchar(30) default NULL,
  PRIMARY KEY (ID)
)
 
INSERT INTO eZSiteManager_SectionFrontPageSetting VALUES (1,'1column');
INSERT INTO eZSiteManager_SectionFrontPageSetting VALUES (2,'2column');
INSERT INTO eZSiteManager_SectionFrontPageSetting VALUES (3,'1short');
INSERT INTO eZSiteManager_SectionFrontPageSetting VALUES (4,'1columnProduct');
INSERT INTO eZSiteManager_SectionFrontPageSetting VALUES (5,'2columnProduct');
INSERT INTO eZSiteManager_SectionFrontPageSetting VALUES (6,'ad');

CREATE TABLE eZForm_FormElementFixedValues (
  ID int(11) NOT NULL default '0',
  Value varchar(80) default NULL,
  PRIMARY KEY (ID)
)
 
CREATE TABLE eZForm_FormElementFixedValueLink (
  ID int(11) NOT NULL default '0',
  ElementID int(11) default '0',
  FixedValueID int(11) default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZContact_CompanyIndex (
  CompanyID int(11) NOT NULL default '0',
  Value varchar(255) NOT NULL default '',
  Type int(11) NOT NULL default '0',
  PRIMARY KEY (CompanyID,Value)
);

CREATE TABLE eZContact_PersonIndex (
  PersonID int(11) NOT NULL default '0',
  Value varchar(255) NOT NULL default '',
  Type int(11) NOT NULL default '0',
  PRIMARY KEY (PersonID,Value)
)


CREATE TABLE eZForm_FormElementType (
  ID int(11) NOT NULL,
  Name varchar(255) default NULL,
  Description text,
  PRIMARY KEY (ID)
);

INSERT INTO eZContact_CompanyIndex (CompanyID, Value, Type) SELECT ID, lower(Name), '0' FROM eZContact_Company;
INSERT INTO eZContact_CompanyIndex (CompanyID, Value, Type) SELECT C.ID, lower(O.URL), '2' FROM eZContact_Company AS C, eZAddress_Online AS O, eZContact_CompanyOnlineDict AS OD WHERE OD.CompanyID=C.ID AND OD.OnlineID=O.ID;
INSERT INTO eZContact_CompanyIndex (CompanyID, Value, Type) SELECT C.ID, lower(P.Number), '1' FROM eZContact_Company AS C, eZAddress_Phone AS P, eZContact_CompanyPhoneDict as PD WHERE PD.CompanyID=C.ID AND PD.PhoneID=P.ID;

INSERT INTO eZContact_PersonIndex (PersonID, Value, Type) SELECT ID, lower(FirstName), '0' FROM eZContact_Person;
INSERT INTO eZContact_PersonIndex (PersonID, Value, Type) SELECT ID, lower(LastName), '0' FROM eZContact_Person;
INSERT INTO eZContact_PersonIndex (PersonID, Value, Type) SELECT P.ID, lower(O.URL), '2' FROM eZContact_Person AS P, eZAddress_Online AS O, eZContact_PersonOnlineDict AS OD WHERE OD.PersonID=P.ID AND OD.OnlineID=O.ID;
INSERT INTO eZContact_PersonIndex (PersonID, Value, Type) SELECT P.ID, lower(Ph.Number), '1' FROM eZContact_Person AS P, eZAddress_Phone AS Ph, eZContact_PersonPhoneDict as PD WHERE PD.PersonID=P.ID AND PD.PhoneID=Ph.ID;

INSERT INTO eZForm_FormElementType VALUES (1,'text_field_item','HTML text field (input type="text")');
INSERT INTO eZForm_FormElementType VALUES (2,'text_area_item','HTML text area (textarea)');
INSERT INTO eZForm_FormElementType VALUES (3,'dropdown_item','HTML Select');
INSERT INTO eZForm_FormElementType VALUES (4,'multiple_select_item','HTML Multiple Select');
INSERT INTO eZForm_FormElementType VALUES (6,'radiobox_item','HTML RadioBox');
INSERT INTO eZForm_FormElementType VALUES (5,'checkbox_item','HTML CheckBox');

create table eZUser_UserShippingLink ( ID int NOT NULL primary key, UserID int default 0, AddressID int default 0 );
insert into eZUser_UserShippingLink (ID, AddressID, UserID) select eZTrade_Order.ShippingAddressID, eZTrade_Order.ShippingAddressID, eZUser_UserAddressLink.UserID from eZTrade_Order, eZUser_UserAddressLink where eZTrade_Order.ShippingAddressID = eZUser_UserAddressLink.AddressID GROUP BY eZTrade_Order.ShippingAddressID;

ALTER TABLE eZLink_Hit CHANGE RemoteIP RemoteIP varchar(15);

CREATE TABLE eZBulkMail_CategoryDelay (
  ID int(11) NOT NULL default '0',
  CategoryID int(11) NOT NULL default '0',
  AddressID int(11) NOT NULL default '0',
  Delay int(11) default '0',
  MailID int(11) default '0',
  PRIMARY KEY  (ID)
);

CREATE TABLE eZBulkMail_Offset (
  ID int(11) NOT NULL default '0',
  Hour int(11) default NULL,
  Daily int(11) default NULL,
  Weekly int(11) default NULL,
  Monthly int(11) default NULL,
  PRIMARY KEY  (ID)
)

alter table eZBulkMail_Template add Description text;
alter table eZBulkMail_UserCategoryDelay change ID ID int NOT NULL primary key;      

DROP TABLE IF EXISTS eZFileManager_FileReadGroupLink; 
DROP TABLE IF EXISTS eZFileManager_FileWriteGroupLink; 
DROP TABLE IF EXISTS eZFileManager_FolderWriteGroupLink; 
DROP TABLE IF EXISTS eZFileManager_FolderReadGroupLink; 

CREATE TABLE eZForm_Form (
  ID int(11) NOT NULL default '0',
  Name varchar(255) default NULL,
  Receiver varchar(255) default NULL,
  CC varchar(255) default NULL,
  Sender varchar(255) default NULL,
  SendAsUser char(1) default NULL,
  CompletedPage varchar(255) default NULL,
  InstructionPage varchar(255) default NULL,
  Counter int(11) default NULL,
  PRIMARY KEY  (ID)
);

CREATE TABLE eZForm_FormElement (
  ID int(11) NOT NULL default '0',
  Name varchar(255) default NULL,
  Required int(1) default '0',
  ElementTypeID int(11) default NULL,
  Size int(11) default '0',
  Break int(11) default '0',
  PRIMARY KEY  (ID)
);

CREATE TABLE eZForm_FormElementDict (
  ID int(11) NOT NULL default '0',
  Name varchar(255) default NULL,
  FormID int(11) default NULL,
  ElementID int(11) default NULL,
  Placement int(11) default NULL,
  PRIMARY KEY  (ID)
)


CREATE INDEX Message_TreeID ON eZForum_Message (TreeID);
CREATE INDEX Message_PostingTime ON eZForum_Message (PostingTime);
CREATE INDEX Message_ThreadID ON eZForum_Message (ThreadID);
CREATE INDEX Message_Depth ON eZForum_Message (Depth);
CREATE INDEX Message_ForumID ON eZForum_Message (ForumID);

CREATE INDEX ForumWordLink_MessageID ON eZForum_MessageWordLink (MessageID);
CREATE INDEX ForumWordLink_WordID ON eZForum_MessageWordLink (WordID);

alter table eZForum_Word change ID ID int NOT NULL primary key;
CREATE INDEX Word_Word ON eZForum_Word (Word);

CREATE INDEX ImageCatalogue_ImageVariationGroup_VariationGroupID ON eZImageCatalogue_ImageVariation (VariationGroupID);
CREATE INDEX ImageCatalogue_ImageVariationGroup_ImageID ON eZImageCatalogue_ImageVariation (ImageID);
CREATE INDEX ImageCatalogue_ImageVariationGroup_ModificationID ON eZImageCatalogue_ImageVariation (Modification);

CREATE TABLE eZMediaCatalogue_Attribute (
  ID int(11) NOT NULL default '0',
  TypeID int(11) default NULL,
  Name varchar(150) default NULL,
  Created int(11) default NULL,
  Placement int(11) default '0',
  Unit varchar(8) default NULL,
  DefaultValue varchar(100) default NULL,
  PRIMARY KEY  (ID)
);

CREATE TABLE eZMediaCatalogue_AttributeValue (
  ID int(11) NOT NULL auto_increment,
  MediaID int(11) default NULL,
  AttributeID int(11) default NULL,
  Value varchar(200) default NULL,
  PRIMARY KEY  (ID)
);

CREATE TABLE eZMediaCatalogue_Media (
  ID int(11) NOT NULL default '0',
  Name varchar(100) default NULL,
  Caption text,
  Description text,
  FileName varchar(100) default NULL,
  OriginalFileName varchar(100) default NULL,
  ReadPermission int(11) default '1',
  WritePermission int(11) default '1',
  UserID int(11) default NULL,
  PhotographerID int(11) default NULL,
  Created int(11) default NULL,
  PRIMARY KEY  (ID)
);

CREATE TABLE eZMediaCatalogue_MediaCategoryDefinition (
  ID int(11) NOT NULL default '0',
  MediaID int(11) default NULL,
  CategoryID int(11) default NULL,
  PRIMARY KEY  (ID)
);

CREATE TABLE eZMediaCatalogue_MediaCategoryLink (
  ID int(11) NOT NULL default '0',
  CategoryID int(11) default NULL,
  MediaID int(11) default NULL,
  PRIMARY KEY  (ID)
);

CREATE TABLE eZMediaCatalogue_MediaPermission (
  ID int(11) NOT NULL default '0',
  ObjectID int(11) default NULL,
  GroupID int(11) default NULL,
  ReadPermission int(11) default '0',
  WritePermission int(11) default '0',
  PRIMARY KEY  (ID)
);

CREATE TABLE eZMediaCatalogue_Type (
  ID int(11) NOT NULL default '0',
  Name varchar(150) default NULL,
  PRIMARY KEY  (ID)
);

CREATE TABLE eZMediaCatalogue_TypeLink (
  ID int(11) NOT NULL default '0',
  TypeID int(11) default '0',
  MediaID int(11) default '0',
  PRIMARY KEY  (ID)
)

alter table eZPoll_Poll drop Percent;
alter table eZPoll_Poll drop Number;


CREATE INDEX Session_Hash ON eZSession_Session (Hash);
CREATE INDEX Session_Created ON eZSession_Session (Created);
CREATE INDEX Session_LastAccessed ON eZSession_Session (LastAccessed);

CREATE INDEX Session_VariableName ON eZSession_SessionVariable (Name);
CREATE INDEX Session_SessionID ON eZSession_SessionVariable (SessionID);

CREATE INDEX TradeProductOption_ValueID ON eZTrade_OptionValueContent (ValueID);

alter table eZTrade_Order add CompanyID int default '0';
alter table eZTrade_Order add PersonID int default '0';

CREATE INDEX TradeProduct_Name ON eZTrade_Product (Name);
CREATE INDEX TradeProduct_Keywords ON eZTrade_Product (Keywords);
CREATE INDEX TradeProduct_Price ON eZTrade_Product (Price);
CREATE INDEX TradeProductCategoryLink_CategoryID ON eZTrade_ProductCategoryLink (CategoryID);
CREATE INDEX TradeProductCategoryLink_ProductID ON eZTrade_ProductCategoryLink (ProductID);
CREATE INDEX TradeProductCategoryDef_ProductID ON eZTrade_ProductCategoryDefinition (ProductID);

CREATE TABLE eZTrade_ProductFormDict (
  ID int(11) NOT NULL default '0',
  ProductID int(11) default NULL,
  FormID int(11) default NULL,
  PRIMARY KEY  (ID)
);

CREATE INDEX TradeProductOptionLink_ProductID ON eZTrade_ProductOptionLink (ProductID);
CREATE INDEX TradeProductOptionLink_OptionID ON eZTrade_ProductOptionLink (OptionID);

alter table eZTrade_ProductPriceRange change ID ID int NOT NULL primary key;

CREATE INDEX UserGroup_UserID ON eZUser_UserGroupLink (UserID);
CREATE INDEX UserGroup_GroupID ON eZUser_UserGroupLink (GroupID);




















CREATE TABLE eZSiteManager_Menu (
  ID int(11) NOT NULL default '0',
  Name varchar(40) default NULL,
  Link varchar(40) default NULL,
  Type int(11) default '1',
  ParentID int(11) default '0'
);

CREATE TABLE eZSiteManager_MenuType (
  ID int(11) NOT NULL default '0',
  Name varchar(30) default NULL
);

CREATE TABLE eZTrade_UserShippingLink ( ID int NOT NULL primary key, UserID int default 0, AddressID int default 0 );

insert into eZUser_UserShippingLink (ID, AddressID, UserID) select eZTrade_Order.ShippingAddressID, eZTrade_Order.ShippingAddressID, eZUser_UserAddressLink.UserID from eZTrade_Order, eZUser_UserAddressLink where eZTrade_Order.ShippingAddressID = eZUser_UserAddressLink.AddressID GROUP BY eZTrade_Order.ShippingAddressID;
alter table eZTrade_Voucher add TotalValue int default 0;        

CREATE TABLE eZBulkMail_SubscriptionCategorySettings (
  ID int(11) NOT NULL,
  CategoryID int(11) NOT NULL default '0',
  AddressID int(11) NOT NULL default '0',
  Delay int(11) default '0',
  PRIMARY KEY (ID)
)


alter table eZAd_View change ViewPrice ViewPrice float(10,2) default 0;

alter table eZLink_Link change KeyWords KeyWords text;