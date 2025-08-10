CREATE TABLE eZMediaCatalogue_Attribute (
  `ID` int(11) NOT NULL DEFAULT 0,
  TypeID int(11) DEFAULT NULL,
  `Name` varchar(150) DEFAULT NULL,
  Created int(11) DEFAULT NULL,
  Placement int(11) DEFAULT 0,
  Unit varchar(8) DEFAULT NULL,
  DefaultValue varchar(100) DEFAULT NULL);

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

DROP TABLE IF EXISTS `eZMediaCatalogue_AttributeValue`;
CREATE TABLE eZMediaCatalogue_AttributeValue (
  `ID` int(11) NOT NULL,
  MediaID int(11) DEFAULT NULL,
  AttributeID int(11) DEFAULT NULL,
  `Value` varchar(200) DEFAULT NULL);

DROP TABLE IF EXISTS `eZMediaCatalogue_Category`;
CREATE TABLE eZMediaCatalogue_Category (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(100) DEFAULT NULL,
  Description text DEFAULT NULL,
  ParentID int(11) DEFAULT NULL,
  UserID int(11) DEFAULT NULL,
  WritePermission int(11) DEFAULT 1,
  ReadPermission int(11) DEFAULT 1);

DROP TABLE IF EXISTS `eZMediaCatalogue_CategoryPermission`;
CREATE TABLE eZMediaCatalogue_CategoryPermission (
  `ID` int(11) NOT NULL DEFAULT 0,
  ObjectID int(11) DEFAULT NULL,
  GroupID int(11) DEFAULT NULL,
  ReadPermission int(11) DEFAULT 0,
  WritePermission int(11) DEFAULT 0);

DROP TABLE IF EXISTS `eZMediaCatalogue_Media`;
CREATE TABLE eZMediaCatalogue_Media (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(100) DEFAULT NULL,
  Caption text DEFAULT NULL,
  Description text DEFAULT NULL,
  FileName varchar(100) DEFAULT NULL,
  OriginalFileName varchar(100) DEFAULT NULL,
  ReadPermission int(11) DEFAULT 1,
  WritePermission int(11) DEFAULT 1,
  UserID int(11) DEFAULT NULL,
  PhotographerID int(11) DEFAULT NULL,
  Created int(11) DEFAULT NULL);


DROP TABLE IF EXISTS `eZMediaCatalogue_MediaCategoryDefinition`;
CREATE TABLE eZMediaCatalogue_MediaCategoryDefinition (
  `ID` int(11) NOT NULL DEFAULT 0,
  MediaID int(11) DEFAULT NULL,
  CategoryID int(11) DEFAULT NULL);

DROP TABLE IF EXISTS `eZMediaCatalogue_MediaCategoryLink`;
CREATE TABLE eZMediaCatalogue_MediaCategoryLink (
  `ID` int(11) NOT NULL DEFAULT 0,
  CategoryID int(11) DEFAULT NULL,
  MediaID int(11) DEFAULT NULL);

DROP TABLE IF EXISTS `eZMediaCatalogue_MediaPermission`;
CREATE TABLE eZMediaCatalogue_MediaPermission (
  `ID` int(11) NOT NULL DEFAULT 0,
  ObjectID int(11) DEFAULT NULL,
  GroupID int(11) DEFAULT NULL,
  ReadPermission int(11) DEFAULT 0,
  WritePermission int(11) DEFAULT 0);

DROP TABLE IF EXISTS `eZMediaCatalogue_Type`;
CREATE TABLE eZMediaCatalogue_Type (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(150) DEFAULT NULL);

DROP TABLE IF EXISTS `eZMediaCatalogue_TypeLink`;
CREATE TABLE eZMediaCatalogue_TypeLink (
  `ID` int(11) NOT NULL DEFAULT 0,
  TypeID int(11) DEFAULT 0,
  MediaID int(11) DEFAULT 0);

INSERT INTO eZMediaCatalogue_Type VALUES (1,'QuickTime');
INSERT INTO eZMediaCatalogue_Type VALUES (2,'Windows Media Player');
INSERT INTO eZMediaCatalogue_Type VALUES (3,'ShockWave Flash');
INSERT INTO eZMediaCatalogue_Type VALUES (4,'Real Player');

