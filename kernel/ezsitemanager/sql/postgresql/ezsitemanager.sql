CREATE TABLE eZSiteManager_Section (
  ID int NOT NULL,
  Name varchar(200) default NULL,
  Created int NOT NULL,
  Description varchar(255),
  SiteDesign varchar(30) default NULL,
  TemplateStyle varchar(30) default NULL,
  Language varchar(5) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZSiteManager_SectionFrontPageRow (
  ID int NOT NULL default '0',
  SettingID int default '0',
  CategoryID int default '0',
  Placement int default '0',
  PRIMARY KEY (ID)
);
 
CREATE TABLE eZSiteManager_SectionFrontPageRowLink (
  ID int NOT NULL default '0',
  FrontPageID int default '0',
  SectionID int default '0',
  PRIMARY KEY (ID)
);
 
CREATE TABLE eZSiteManager_SectionFrontPageSetting (
  ID int NOT NULL default '0',
  Name varchar(30) default NULL,
  PRIMARY KEY (ID)
);
 
INSERT INTO eZSiteManager_SectionFrontPageSetting VALUES (1,'1column');
INSERT INTO eZSiteManager_SectionFrontPageSetting VALUES (2,'2column');
INSERT INTO eZSiteManager_SectionFrontPageSetting VALUES (3,'1short');
INSERT INTO eZSiteManager_SectionFrontPageSetting VALUES (4,'1columnProduct');
INSERT INTO eZSiteManager_SectionFrontPageSetting VALUES (5,'2columnProduct');
INSERT INTO eZSiteManager_SectionFrontPageSetting VALUES (6,'ad');

INSERT INTO eZSiteManager_Section ( ID,  Name, Created, Description, SiteDesign, TemplateStyle ) VALUES ( 1, 'Standard Section', 1, NULL, 'standard', NULL );


CREATE TABLE eZSiteManager_Menu (
  ID int NOT NULL default '0',
  Name varchar(40) default NULL,
  Link varchar(40) default NULL,
  Type int default '1',
  ParentID int default '0',
  PRIMARY KEY  (ID)
);

CREATE TABLE eZSiteManager_MenuType (
  ID int NOT NULL default '0',
  Name varchar(30) default NULL,
  PRIMARY KEY  (ID)
);

alter table eZStats_Archive_RefererURL add Language varchar(5) default NULL;
alter table eZStats_Archive_UniqueVisits add Language varchar(5) default NULL;