CREATE TABLE eZTip_Category (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(150) DEFAULT NULL,
  Description text DEFAULT NULL,
  ParentID int(11) NOT NULL DEFAULT 0,
  SectionID text NOT NULL DEFAULT '',
  IsPublished int(11) NOT NULL DEFAULT 0,
  LocationID int(11) NOT NULL DEFAULT 0,
  ExcludeFromSearch int(11) DEFAULT 0);

CREATE TABLE eZTip_Click (
  `ID` int(11) NOT NULL DEFAULT 0,
  TipID int(11) DEFAULT NULL,
  PageViewID int(11) DEFAULT NULL,
  ClickPrice float(10,2) DEFAULT NULL);

CREATE TABLE eZTip_Tip(
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(150) DEFAULT NULL,
  ImageID int(11) DEFAULT NULL,
  URL varchar(200) DEFAULT NULL,
  Description text DEFAULT NULL,
  IsActive int(11) NOT NULL DEFAULT 0,
  ViewPrice float DEFAULT 0,
  ClickPrice float DEFAULT 0,
  HTMLBanner text DEFAULT NULL,
  UseHTML int(11) NOT NULL DEFAULT 0);

CREATE TABLE eZTip_TipCategoryLink (
  `ID` int(11) NOT NULL DEFAULT 0,
  CategoryID int(11) DEFAULT NULL,
  TipID int(11) DEFAULT NULL);

CREATE TABLE eZTip_View (
  `ID` int(11) NOT NULL DEFAULT 0,
  TipID int(11) DEFAULT NULL,
  ViewCount int(11) NOT NULL DEFAULT 0,
  ViewOffsetCount int(11) NOT NULL DEFAULT 0,
  ViewPrice float(10,2) NOT NULL DEFAULT 0.00,
  `Date` int(11) DEFAULT NULL);

-- Data : Required to boot

INSERT INTO `eZTip_Category` VALUES(1, 'Tip #1', 'Tip #1 - Description Text', 0, 0, 0, 0, 0);
INSERT INTO `eZTip_Category` VALUES(2, 'Tip #2', 'Tip #2 - Even More Description Text', 0, 0, 0, 0, 0);


