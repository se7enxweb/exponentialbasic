CREATE TABLE eZImageCatalogue_Category (
  ID int NOT NULL,
  Name varchar(100) default NULL,
  Description lvarchar,
  ParentID int default NULL,
  UserID int default NULL,
  WritePermission int,
  ReadPermission int,
  SectionID int default '1',
  PRIMARY KEY (ID)
);

CREATE TABLE eZImageCatalogue_CategoryPermission (
  ID int NOT NULL,
  ObjectID int default NULL,
  GroupID int default NULL,
  ReadPermission int,
  WritePermission int,
  UploadPermission int default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZImageCatalogue_Image (
  ID int NOT NULL,
  Name varchar(100) default NULL,
  Caption lvarchar,
  Description lvarchar,
  PhotographerID int,
  Created int,
  FileName varchar(100) default NULL,
  OriginalFileName varchar(100) default NULL,
  ReadPermission int default '1',
  WritePermission int default '1',
  UserID int default NULL,
  Keywords lvarchar default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZImageCatalogue_ImagePermission (
  ID int NOT NULL,
  ObjectID int default NULL,
  GroupID int default NULL,
  ReadPermission int,
  WritePermission int,
  PRIMARY KEY (ID)
);

CREATE TABLE eZImageCatalogue_ImageCategoryLink (
  ID int NOT NULL,
  CategoryID int default NULL,
  ImageID int default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZImageCatalogue_ImageVariation (
  ID int NOT NULL,
  ImageID int default NULL,
  VariationGroupID int default NULL,
  ImagePath varchar(100) default NULL,
  Width int default NULL,
  Height int default NULL,
  Modification varchar(20) NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZImageCatalogue_ImageVariationGroup (
  ID int NOT NULL,
  Width int default NULL,
  Height int default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZImageCatalogue_ImageMap (
  ID int NOT NULL,
  ImageID int default NULL,
  Link varchar(50) NOT NULL,
  AltLvarchar lvarchar,
  Shape int NOT NULL,
  StartPosX int NOT NULL,
  StartPosY int NOT NULL,
  EndPosX int NOT NULL,
  EndPosY int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZImageCatalogue_ImageCategoryDefinition (
  ID int NOT NULL,
  ImageID int default NULL,
  CategoryID int default NULL,
  PRIMARY KEY (ID)
);

CREATE INDEX ImageCatalogue_ImageVariationGroup_VariationGroupID ON  eZImageCatalogue_ImageVariation  (VariationGroupID);
CREATE INDEX ImageCatalogue_ImageVariationGroup_ImageID  ON  eZImageCatalogue_ImageVariation  (ImageID);
CREATE INDEX ImageCatalogue_ImageVariationGroup_ModificationID ON  eZImageCatalogue_ImageVariation  (Modification);
