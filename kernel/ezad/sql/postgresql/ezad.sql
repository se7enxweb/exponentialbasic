CREATE TABLE eZAd_Ad(
  ID int NOT NULL,
  Name varchar(150) default NULL,
  ImageID int default NULL,
  URL varchar(200) default NULL,
  Description text,
  IsActive int not null,
  ViewPrice float default 0.0,
  ClickPrice float default 0.0,
  HTMLBanner text default null,
  UseHTML int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZAd_AdCategoryLink (
  ID int NOT NULL,
  CategoryID int default NULL,
  AdID int default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZAd_Category (
  ID int NOT NULL,
  Name varchar(150) default NULL,
  Description text,
  ParentID int not NULL,
  ExcludeFromSearch int default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZAd_Click (
  ID int NOT NULL,
  AdID int default NULL,
  PageViewID int default NULL,
  ClickPrice decimal(10,2) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZAd_View (
  ID int NOT NULL,
  AdID int default NULL,
  ViewCount int NOT NULL,
  ViewOffsetCount int NOT NULL,
  ViewPrice decimal(10,2) NOT NULL,
  Date int default NULL,
  PRIMARY KEY (ID)
);

