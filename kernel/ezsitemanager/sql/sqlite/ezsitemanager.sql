CREATE TABLE eZSiteManager_Menu (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(40) DEFAULT NULL,
  Link varchar(40) DEFAULT NULL,
  `Type` int(11) DEFAULT 1,
  ParentID int(11) DEFAULT 0);

CREATE TABLE eZSiteManager_MenuType (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(30) DEFAULT NULL);

CREATE TABLE eZSiteManager_Section (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(200) DEFAULT NULL,
  Description text DEFAULT NULL,
  SiteDesign varchar(30) DEFAULT NULL,
  Created int(11) DEFAULT NULL,
  TemplateStyle varchar(255) DEFAULT NULL,
  `Language` varchar(5) DEFAULT NULL);

CREATE TABLE eZSiteManager_SectionFrontPageRow (
  `ID` int(11) NOT NULL DEFAULT 0,
  SettingID int(11) DEFAULT 0,
  CategoryID int(11) DEFAULT 0,
  Placement int(11) DEFAULT 0);
 
CREATE TABLE eZSiteManager_SectionFrontPageRowLink (
  `ID` int(11) NOT NULL DEFAULT 0,
  FrontPageID int(11) DEFAULT 0,
  SectionID int(11) DEFAULT 0);
 
CREATE TABLE eZSiteManager_SectionFrontPageSetting (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(30) DEFAULT NULL);
 
INSERT INTO eZSiteManager_SectionFrontPageSetting VALUES (1,'1column');
INSERT INTO eZSiteManager_SectionFrontPageSetting VALUES (2,'2column');
INSERT INTO eZSiteManager_SectionFrontPageSetting VALUES (3,'1short');
INSERT INTO eZSiteManager_SectionFrontPageSetting VALUES (4,'1columnProduct');
INSERT INTO eZSiteManager_SectionFrontPageSetting VALUES (5,'2columnProduct');
INSERT INTO eZSiteManager_SectionFrontPageSetting VALUES (6,'ad');

INSERT INTO eZSiteManager_Section ( ID,  Name, Created, Description, SiteDesign, TemplateStyle, Language) VALUES ( 1, 'Standard Section', 1, NULL, 'standard', NULL, NULL);


