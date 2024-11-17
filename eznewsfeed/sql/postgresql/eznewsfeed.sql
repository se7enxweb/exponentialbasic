CREATE TABLE eZNewsFeed_Category (
  ID int NOT NULL,
  Name varchar(150) NOT NULL default '',
  Description text,
  ParentID int NOT NULL default '0',
  PRIMARY KEY (ID)
);

INSERT INTO eZNewsFeed_Category VALUES (1,'News from freshmeat','',0);

CREATE TABLE eZNewsFeed_News (
  ID int NOT NULL,
  IsPublished int NOT NULL default '0',
  PublishingDate int NOT NULL,
  OriginalPublishingDate int NOT NULL,
  Name varchar(150) NOT NULL default '',
  Intro text,
  KeyWords varchar(200) default NULL,
  URL varchar(200) default NULL,
  Origin varchar(150) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZNewsFeed_NewsCategoryLink (
  ID int NOT NULL,
  NewsID int NOT NULL default '0',
  CategoryID int NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZNewsFeed_SourceSite (
  ID int DEFAULT '0' NOT NULL,
  URL varchar(250),
  Login varchar(30),
  Password varchar(30),
  CategoryID int NOT NULL default '0',
  Name varchar(100),
  Decoder varchar(50),
  IsActive int default '0',
  AutoPublish int NOT NULL default '0',
  PRIMARY KEY (ID)
);

INSERT INTO eZNewsFeed_SourceSite VALUES (1,'http://freshmeat.net/backend/fm.rdf','','',1,'Freshmeat','rdf',1,1);
