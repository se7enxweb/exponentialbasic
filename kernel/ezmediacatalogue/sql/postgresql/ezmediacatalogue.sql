CREATE TABLE eZMediaCatalogue_Attribute (
  ID int NOT NULL,
  TypeID int default NULL,
  Name varchar(150) default NULL,
  Created int default NULL,
  Placement int default '0',
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
INSERT INTO eZMediaCatalogue_Attribute VALUES (12,3,'pluginspage',996137887,11,'','http://www.macromedia.com/shockwave/download/index.cgi?P1_=Prod_Version=3DShockwaveFlash"');
INSERT INTO eZMediaCatalogue_Attribute VALUES (13,3,'type',996137896,12,'','application/x-shockwave-flash');
INSERT INTO eZMediaCatalogue_Attribute VALUES (14,3,'width',996137906,13,'','');
INSERT INTO eZMediaCatalogue_Attribute VALUES (15,3,'height',996137917,14,'','');
INSERT INTO eZMediaCatalogue_Attribute VALUES (16,2,'type',996139826,15,'','application/x-mplayer2');
INSERT INTO eZMediaCatalogue_Attribute VALUES (17,4,'width',1004640070,16,'','');
INSERT INTO eZMediaCatalogue_Attribute VALUES (18,4,'height',1004640076,17,'','');
INSERT INTO eZMediaCatalogue_Attribute VALUES (19,4,'controls',1004640087,18,'','ImageWindow');
INSERT INTO eZMediaCatalogue_Attribute VALUES (20,4,'autostart',1004640100,19,'','true');

CREATE TABLE eZMediaCatalogue_Category (
  ID int NOT NULL,
  Name varchar(100) default NULL,
  Description text,
  ParentID int default NULL,
  UserID int default NULL,
  WritePermission int default '1',
  ReadPermission int default '1',
  PRIMARY KEY (ID)
);

CREATE TABLE eZMediaCatalogue_Media (
  ID int NOT NULL,		    
  Name varchar(100) default NULL,
  Caption text,
  Description text,
  FileName varchar(100) default NULL,
  OriginalFileName varchar(100) default NULL,
  ReadPermission int default '1',
  WritePermission int default '1',
  UserID int default NULL,
  PhotographerID int default NULL,
  Created int default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZMediaCatalogue_CategoryPermission (
  ID int NOT NULL,
  ObjectID int default NULL,
  GroupID int default NULL,
  ReadPermission int default '0',
  WritePermission int default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZMediaCatalogue_MediaCategoryDefinition (
  ID int NOT NULL,
  MediaID int default NULL,
  CategoryID int default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZMediaCatalogue_MediaCategoryLink (
  ID int NOT NULL,		    
  CategoryID int default NULL,
  MediaID int default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZMediaCatalogue_MediaPermission (
  ID int NOT NULL,		    
  ObjectID int default NULL,
  GroupID int default NULL,
  ReadPermission int default '0',
  WritePermission int default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZMediaCatalogue_Type (
  ID int NOT NULL,		  
  Name varchar(150) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZMediaCatalogue_TypeLink (
  ID int NOT NULL,		  
  TypeID int default 0,
  MediaID int default 0,
  PRIMARY KEY (ID)
);

INSERT INTO eZMediaCatalogue_Type VALUES (1,'QuickTime');
INSERT INTO eZMediaCatalogue_Type VALUES (2,'Windows Media Player');
INSERT INTO eZMediaCatalogue_Type VALUES (3,'ShockWave Flash');
INSERT INTO eZMediaCatalogue_Type VALUES (4,'Real Player');

CREATE TABLE eZMediaCatalogue_AttributeValue (
  ID int NOT NULL,
  MediaID int default NULL,
  AttributeID int default NULL,
  Value varchar(200) default NULL,
  PRIMARY KEY (ID)
);

INSERT INTO eZMediaCatalogue_AttributeValue VALUES (33,1,16,'application/x-mplayer2');
INSERT INTO eZMediaCatalogue_AttributeValue VALUES (32,1,10,'true');
INSERT INTO eZMediaCatalogue_AttributeValue VALUES (31,1,9,'false');
INSERT INTO eZMediaCatalogue_AttributeValue VALUES (30,1,8,'false');
INSERT INTO eZMediaCatalogue_AttributeValue VALUES (29,1,7,'300');
INSERT INTO eZMediaCatalogue_AttributeValue VALUES (28,1,6,'300');
INSERT INTO eZMediaCatalogue_AttributeValue VALUES (7,3,11,'high');
INSERT INTO eZMediaCatalogue_AttributeValue VALUES (8,3,12,'http://www.macromedia.com/shockwave/download/index.cgi?P1_P rod_Version=ShockwaveFlash');
INSERT INTO eZMediaCatalogue_AttributeValue VALUES (9,3,13,'application/x-shockwave-flash');
INSERT INTO eZMediaCatalogue_AttributeValue VALUES (10,3,14,'500');
INSERT INTO eZMediaCatalogue_AttributeValue VALUES (11,3,15,'400');
INSERT INTO eZMediaCatalogue_AttributeValue VALUES (38,2,15,'400');
INSERT INTO eZMediaCatalogue_AttributeValue VALUES (37,2,14,'500');
INSERT INTO eZMediaCatalogue_AttributeValue VALUES (36,2,13,'application/x-shockwave-flash');
INSERT INTO eZMediaCatalogue_AttributeValue VALUES (35,2,12,'http://www.macromedia.com/shockwave/download/index.cgi?P1_P rod_Version=ShockwaveFlash');
INSERT INTO eZMediaCatalogue_AttributeValue VALUES (34,2,11,'high');
INSERT INTO eZMediaCatalogue_AttributeValue VALUES (17,4,1,'500');
INSERT INTO eZMediaCatalogue_AttributeValue VALUES (18,4,2,'400');
INSERT INTO eZMediaCatalogue_AttributeValue VALUES (19,4,3,'video/quicktime');
INSERT INTO eZMediaCatalogue_AttributeValue VALUES (20,4,4,'true');
INSERT INTO eZMediaCatalogue_AttributeValue VALUES (21,4,5,'true');
INSERT INTO eZMediaCatalogue_AttributeValue VALUES (44,5,16,'application/x-mplayer2');
INSERT INTO eZMediaCatalogue_AttributeValue VALUES (43,5,10,'true');
INSERT INTO eZMediaCatalogue_AttributeValue VALUES (42,5,9,'false');
INSERT INTO eZMediaCatalogue_AttributeValue VALUES (41,5,8,'false');
INSERT INTO eZMediaCatalogue_AttributeValue VALUES (40,5,7,'300');
INSERT INTO eZMediaCatalogue_AttributeValue VALUES (39,5,6,'300');