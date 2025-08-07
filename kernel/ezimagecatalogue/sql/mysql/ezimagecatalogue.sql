CREATE TABLE eZImageCatalogue_Category (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(100) DEFAULT NULL,
  Description text DEFAULT NULL,
  ParentID int(11) DEFAULT NULL,
  UserID int(11) DEFAULT NULL,
  WritePermission int(11) DEFAULT 1,
  ReadPermission int(11) DEFAULT 1,
  SectionID int(11) DEFAULT 1,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZImageCatalogue_CategoryPermission (
  `ID` int(11) NOT NULL DEFAULT 0,
  ObjectID int(11) DEFAULT NULL,
  GroupID int(11) DEFAULT NULL,
  ReadPermission int(11) DEFAULT 0,
  WritePermission int(11) DEFAULT 0,
  UploadPermission int(11) DEFAULT 0,
  PRIMARY KEY (`ID`),
  KEY eZImageCatalogue_CategoryPermission_GroupID (GroupID),
  KEY eZImageCatalogue_CategoryPermission_ObjectID (ObjectID),
  KEY eZImageCatalogue_CategoryPermission_ReadPermission (ReadPermission)
);

CREATE TABLE eZImageCatalogue_Image (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(100) DEFAULT NULL,
  Caption text DEFAULT NULL,
  Description text DEFAULT NULL,
  PhotographerID int(11) DEFAULT NULL,
  Created int(11) DEFAULT NULL,
  FileName varchar(100) DEFAULT NULL,
  OriginalFileName varchar(100) DEFAULT NULL,
  ReadPermission int(11) DEFAULT 1,
  WritePermission int(11) DEFAULT 1,
  UserID int(11) DEFAULT NULL,
  Keywords varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY eZImageCatalogue_Image_OriginalFileName (OriginalFileName)
);

CREATE TABLE eZImageCatalogue_ImageCategoryDefinition (
  `ID` int(11) NOT NULL DEFAULT 0,
  ImageID int(11) DEFAULT NULL,
  CategoryID int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZImageCatalogue_ImageCategoryLink (
  `ID` int(11) NOT NULL DEFAULT 0,
  CategoryID int(11) DEFAULT NULL,
  ImageID int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZImageCatalogue_ImageForumLink (
  `ID` int(11) NOT NULL DEFAULT 0,
  ImageID int(11) NOT NULL DEFAULT 0,
  ForumID int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZImageCatalogue_ImageMap (
  `ID` int(11) NOT NULL DEFAULT 0,
  ImageID int(11) DEFAULT NULL,
  Link varchar(50) NOT NULL DEFAULT '',
  AltText text DEFAULT NULL,
  Shape int(11) NOT NULL DEFAULT 0,
  StartPosX int(11) NOT NULL DEFAULT 0,
  StartPosY int(11) NOT NULL DEFAULT 0,
  EndPosX int(11) NOT NULL DEFAULT 0,
  EndPosY int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZImageCatalogue_ImagePermission (
  `ID` int(11) NOT NULL DEFAULT 0,
  ObjectID int(11) DEFAULT NULL,
  GroupID int(11) DEFAULT NULL,
  ReadPermission int(11) DEFAULT 0,
  WritePermission int(11) DEFAULT 0,
  PRIMARY KEY (`ID`),
  KEY eZImageCatalogue_ImagePermission_GroupID (GroupID),
  KEY eZImageCatalogue_ImagePermission_ObjectID (ObjectID),
  KEY eZImageCatalogue_ImagePermission_ReadPermission (ReadPermission)
);

CREATE TABLE eZImageCatalogue_ImageVariation (
  `ID` int(11) NOT NULL DEFAULT 0,
  ImageID int(11) NOT NULL DEFAULT 0,
  VariationGroupID int(11) NOT NULL DEFAULT 0,
  ImagePath varchar(100) DEFAULT NULL,
  Width int(11) DEFAULT NULL,
  Height int(11) DEFAULT NULL,
  Modification varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`),
  KEY ImageCatalogue_ImageVariationGroup_VariationGroupID (VariationGroupID),
  KEY ImageCatalogue_ImageVariationGroup_ImageID (ImageID),
  KEY ImageCatalogue_ImageVariationGroup_ModificationID (Modification)
);

CREATE TABLE eZImageCatalogue_ImageVariationGroup (
  `ID` int(11) NOT NULL DEFAULT 0,
  Width int(11) DEFAULT NULL,
  Height int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
);


CREATE INDEX ImageCatalogue_ImageVariationGroup_VariationGroupID ON  eZImageCatalogue_ImageVariation  (VariationGroupID);
CREATE INDEX ImageCatalogue_ImageVariationGroup_ImageID  ON  eZImageCatalogue_ImageVariation  (ImageID);
CREATE INDEX ImageCatalogue_ImageVariationGroup_ModificationID ON  eZImageCatalogue_ImageVariation  (Modification);
CREATE INDEX eZImageCatalogue_Image_OriginalFileName ON eZImageCatalogue_Image(OriginalFileName);
CREATE INDEX eZImageCatalogue_ImagePermission_GroupID ON eZImageCatalogue_ImagePermission(GroupID);
CREATE INDEX eZImageCatalogue_ImagePermission_ObjectID ON eZImageCatalogue_ImagePermission(ObjectID);
CREATE INDEX eZImageCatalogue_ImagePermission_ReadPermission ON eZImageCatalogue_ImagePermission(ReadPermission);
CREATE INDEX eZImageCatalogue_CategoryPermission_GroupID ON eZImageCatalogue_CategoryPermission(GroupID);
CREATE INDEX eZImageCatalogue_CategoryPermission_ObjectID ON eZImageCatalogue_CategoryPermission(ObjectID);
CREATE INDEX eZImageCatalogue_CategoryPermission_ReadPermission ON eZImageCatalogue_CategoryPermission(ReadPermission);

