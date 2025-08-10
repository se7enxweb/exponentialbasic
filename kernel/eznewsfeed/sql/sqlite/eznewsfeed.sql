CREATE TABLE eZNewsFeed_Category (
  `ID` int(11) NOT NULL,
  `Name` varchar(150) NOT NULL DEFAULT '',
  Description text DEFAULT NULL,
  ParentID int(11) NOT NULL DEFAULT 0);

INSERT INTO eZNewsFeed_Category VALUES (1,'News from freshmeat','',0);

CREATE TABLE eZNewsFeed_News (
  `ID` int(11) NOT NULL,
  IsPublished int(11) NOT NULL DEFAULT 0,
  PublishingDate int(11) NOT NULL DEFAULT 0,
  OriginalPublishingDate int(11) NOT NULL DEFAULT 0,
  `Name` varchar(150) NOT NULL DEFAULT '',
  Intro text DEFAULT NULL,
  KeyWords varchar(200) DEFAULT NULL,
  URL varchar(200) DEFAULT NULL,
  Origin varchar(150) DEFAULT NULL);

CREATE TABLE eZNewsFeed_NewsCategoryLink (
  `ID` int(11) NOT NULL,
  NewsID int(11) NOT NULL DEFAULT 0,
  CategoryID int(11) NOT NULL DEFAULT 0);

CREATE TABLE eZNewsFeed_SourceSite (
  `ID` int(11) NOT NULL DEFAULT 0,
  URL varchar(250) DEFAULT NULL,
  Login varchar(30) DEFAULT NULL,
  `Password` varchar(30) DEFAULT NULL,
  CategoryID int(11) NOT NULL DEFAULT 0,
  `Name` varchar(100) DEFAULT NULL,
  Decoder varchar(50) DEFAULT NULL,
  IsActive int(11) DEFAULT 0,
  AutoPublish int(11) NOT NULL DEFAULT 0);

INSERT INTO eZNewsFeed_SourceSite VALUES (1,'http://freshmeat.net/backend/fm.rdf','','',1,'Freshmeat','rdf',1,1);

