CREATE TABLE eZTip_Tip(
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

CREATE TABLE eZTip_TipCategoryLink (
  ID int NOT NULL,
  CategoryID int default NULL,
  TipID int default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZTip_Category (
  ID int NOT NULL,
  Name varchar(150) default NULL,
  Description text,
  ParentID int not NULL,
  ExcludeFromSearch int default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTip_Click (
  ID int(11) NOT NULL,
  TipID int(11) default NULL,
  PageViewID int(11) default NULL,
  ClickPrice float(10,2) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZTip_View (
  ID int NOT NULL,
  TipID int default NULL,
  ViewCount int NOT NULL,
  ViewOffsetCount int NOT NULL,
  ViewPrice float(10,2) NOT NULL,
  Date int default NULL,
  PRIMARY KEY (ID)
);