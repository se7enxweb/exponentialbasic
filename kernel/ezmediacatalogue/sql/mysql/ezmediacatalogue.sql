DROP TABLE IF EXISTS `eZMediaCatalogue_Attribute`;
CREATE TABLE eZMediaCatalogue_Attribute (
  ID int(11) NOT NULL,
  TypeID int(11) default NULL,
  Name varchar(150) default NULL,
  Created int(11) default NULL,
  Placement int(11) default '0',
  Unit varchar(8) default NULL,
  DefaultValue varchar(100) default NULL,
  PRIMARY KEY (ID)
);

INSERT INTO eZMediaCatalogue_Attribute VALUES (1,1,'width',996137421,0,'','');
INSERT INTO eZMediaCatalogue_Attribute VALUES (2,1,'height',996137432,1,'','');
INSERT INTO eZMediaCatalogue_Attribute VALUES (3,1,'type',996137440,2,'','video/quicktime');
INSERT INTO eZMediaCatalogue_Attribute VALUES (4,1,'controller',996137447,3,'','true');
INSERT INTO eZMediaCatalogue_Attribute VALUES (5,1,'autoplay',996137455,4,'','true');
INSERT INTO eZMediaCatalogue_Attribute VALUES (6,2,'width',996137483,5,'','');
INSERT INTO eZMediaCatalogue_Attribute VALUES (7,2,'height',996137631,6,'','');
INSERT INTO eZMediaCatalogue_Attribute VALUES (8,2,'controller',996137641,7,'','true');
INSERT INTO eZMediaCatalogue_Attribute VALUES (9,2,'loop',996137662,8,'','false');
INSERT INTO eZMediaCatalogue_Attribute VALUES (10,2,'autoplay',996137674,9,'','true');
INSERT INTO eZMediaCatalogue_Attribute VALUES (11,3,'quality',996137872,10,'','high');
INSERT INTO eZMediaCatalogue_Attribute VALUES (12,3,'pluginspage',996137887,11,'','http://www.macromedia.com/shockwave/download/index.cgi?P1_=Prod_Version=3DShockwaveFlash');
INSERT INTO eZMediaCatalogue_Attribute VALUES (13,3,'type',996137896,12,'','application/x-shockwave-flash');
INSERT INTO eZMediaCatalogue_Attribute VALUES (14,3,'width',996137906,13,'','');
INSERT INTO eZMediaCatalogue_Attribute VALUES (15,3,'height',996137917,14,'','');
INSERT INTO eZMediaCatalogue_Attribute VALUES (16,2,'type',996139826,15,'','application/x-mplayer2');
INSERT INTO eZMediaCatalogue_Attribute VALUES (17,4,'width',1004640070,16,'','');
INSERT INTO eZMediaCatalogue_Attribute VALUES (18,4,'height',1004640076,17,'','');
INSERT INTO eZMediaCatalogue_Attribute VALUES (19,4,'controls',1004640087,18,'','ImageWindow');
INSERT INTO eZMediaCatalogue_Attribute VALUES (20,4,'autostart',1004640100,19,'','true');

DROP TABLE IF EXISTS `eZMediaCatalogue_Category`;
CREATE TABLE eZMediaCatalogue_Category (
  ID int(11) NOT NULL,
  Name varchar(100) default NULL,
  Description text,
  ParentID int(11) default NULL,
  UserID int(11) default NULL,
  WritePermission int(11) default '1',
  ReadPermission int(11) default '1',
  PRIMARY KEY (ID)
);

DROP TABLE IF EXISTS `eZMediaCatalogue_Media`;
CREATE TABLE eZMediaCatalogue_Media (
  ID int(11) NOT NULL,		    
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
  PRIMARY KEY (ID)
);

DROP TABLE IF EXISTS `eZMediaCatalogue_CategoryPermission`;
CREATE TABLE eZMediaCatalogue_CategoryPermission (
  ID int(11) NOT NULL,
  ObjectID int(11) default NULL,
  GroupID int(11) default NULL,
  ReadPermission int(11) default '0',
  WritePermission int(11) default '0',
  PRIMARY KEY (ID)
);


DROP TABLE IF EXISTS `eZMediaCatalogue_MediaCategoryDefinition`;
CREATE TABLE eZMediaCatalogue_MediaCategoryDefinition (
  ID int(11) NOT NULL,
  MediaID int(11) default NULL,
  CategoryID int(11) default NULL,
  PRIMARY KEY (ID)
);

DROP TABLE IF EXISTS `eZMediaCatalogue_MediaCategoryLink`;
CREATE TABLE eZMediaCatalogue_MediaCategoryLink (
  ID int(11) NOT NULL,		    
  CategoryID int(11) default NULL,
  MediaID int(11) default NULL,
  PRIMARY KEY (ID)
);

DROP TABLE IF EXISTS `eZMediaCatalogue_MediaPermission`;
CREATE TABLE eZMediaCatalogue_MediaPermission (
  ID int(11) NOT NULL,		    
  ObjectID int(11) default NULL,
  GroupID int(11) default NULL,
  ReadPermission int(11) default '0',
  WritePermission int(11) default '0',
  PRIMARY KEY (ID)
);

DROP TABLE IF EXISTS `eZMediaCatalogue_Type`;
CREATE TABLE eZMediaCatalogue_Type (
  ID int(11) NOT NULL,		  
  Name varchar(150) default NULL,
  PRIMARY KEY (ID)
);

DROP TABLE IF EXISTS `eZMediaCatalogue_TypeLink`;
CREATE TABLE eZMediaCatalogue_TypeLink (
  ID int(11) NOT NULL,		  
  TypeID int(11) default 0,
  MediaID int(11) default 0,
  PRIMARY KEY (ID)
);

INSERT INTO eZMediaCatalogue_Type VALUES (1,'QuickTime');
INSERT INTO eZMediaCatalogue_Type VALUES (2,'Windows Media Player');
INSERT INTO eZMediaCatalogue_Type VALUES (3,'ShockWave Flash');
INSERT INTO eZMediaCatalogue_Type VALUES (4,'Real Player');

DROP TABLE IF EXISTS `eZMediaCatalogue_AttributeValue`;
CREATE TABLE eZMediaCatalogue_AttributeValue (
  ID int(11) NOT NULL auto_increment,
  MediaID int(11) default NULL,
  AttributeID int(11) default NULL,
  Value varchar(200) default NULL,
  PRIMARY KEY (ID)
);