CREATE TABLE eZAd_Ad(
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(150) DEFAULT NULL,
  ImageID int(11) DEFAULT NULL,
  URL varchar(200) DEFAULT NULL,
  Description text DEFAULT NULL,
  IsActive int(11) NOT NULL DEFAULT 0,
  ViewPrice float DEFAULT 0,
  ClickPrice float DEFAULT 0,
  HTMLBanner text DEFAULT NULL,
  UseHTML int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZAd_AdCategoryLink (
  `ID` int(11) NOT NULL DEFAULT 0,
  CategoryID int(11) DEFAULT NULL,
  AdID int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZAd_Category (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(150) DEFAULT NULL,
  Description text DEFAULT NULL,
  ParentID int(11) NOT NULL DEFAULT 0,
  ExcludeFromSearch int(11) DEFAULT 0,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZAd_Click (
  `ID` int(11) NOT NULL DEFAULT 0,
  AdID int(11) DEFAULT NULL,
  PageViewID int(11) DEFAULT NULL,
  ClickPrice float(10,2) DEFAULT NULL,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZAd_View (
  `ID` int(11) NOT NULL DEFAULT 0,
  AdID int(11) DEFAULT NULL,
  ViewCount int(11) NOT NULL DEFAULT 0,
  ViewOffsetCount int(11) NOT NULL DEFAULT 0,
  ViewPrice float(10,2) NOT NULL DEFAULT 0.00,
  `Date` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
);