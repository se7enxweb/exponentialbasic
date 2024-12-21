CREATE TABLE eZSiteManager_Section (
  ID int(11) NOT NULL ,
  Name varchar(200) default NULL,
  Description text,
  SiteDesign varchar(30) default NULL,
  Created int(11) default NULL,
  TemplateStyle varchar(255) default NULL,
  Language varchar(5) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZSiteManager_SectionFrontPageRow (
  ID int(11) NOT NULL default '0',
  SettingID int(11) default '0',
  CategoryID int(11) default '0',
  Placement int(11) default '0',
  PRIMARY KEY (ID)
);
 
CREATE TABLE eZSiteManager_SectionFrontPageRowLink (
  ID int(11) NOT NULL default '0',
  FrontPageID int(11) default '0',
  SectionID int(11) default '0',
  PRIMARY KEY (ID)
);
 
CREATE TABLE eZSiteManager_SectionFrontPageSetting (
  ID int(11) NOT NULL default '0',
  Name varchar(30) default NULL,
  PRIMARY KEY (ID)
);
 
INSERT INTO eZSiteManager_SectionFrontPageSetting VALUES (1,'1column');
INSERT INTO eZSiteManager_SectionFrontPageSetting VALUES (2,'2column');
INSERT INTO eZSiteManager_SectionFrontPageSetting VALUES (3,'1short');
INSERT INTO eZSiteManager_SectionFrontPageSetting VALUES (4,'1columnProduct');
INSERT INTO eZSiteManager_SectionFrontPageSetting VALUES (5,'2columnProduct');
INSERT INTO eZSiteManager_SectionFrontPageSetting VALUES (6,'ad');

INSERT INTO eZSiteManager_Section ( ID,  Name, Created, Description, SiteDesign, TemplateStyle, Language) VALUES ( 1, 'Standard Section', 1, NULL, 'standard', NULL, NULL);


CREATE TABLE eZSiteManager_Menu (
  ID int(11) NOT NULL default '0',
  Name varchar(40) default NULL,
  Link varchar(40) default NULL,
  Type int(11) default '1',
  ParentID int(11) default '0',
  PRIMARY KEY  (ID)
);

CREATE TABLE eZSiteManager_MenuType (
  ID int(11) NOT NULL default '0',
  Name varchar(30) default NULL,
  PRIMARY KEY  (ID)
);