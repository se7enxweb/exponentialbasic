CREATE TABLE eZLink_Attribute (
  `ID` int(11) NOT NULL DEFAULT 0,
  TypeID int(11) DEFAULT NULL,
  `Name` varchar(150) DEFAULT NULL,
  Created int(11) DEFAULT NULL,
  Placement int(11) DEFAULT 0,
  Unit varchar(8) DEFAULT NULL,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZLink_AttributeValue (
  `ID` int(11) NOT NULL DEFAULT 0,
  LinkID int(11) DEFAULT NULL,
  AttributeID int(11) DEFAULT NULL,
  `Value` char(200) DEFAULT NULL,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZLink_Category (
  `ID` int(11) NOT NULL DEFAULT 0,
  Parent int(11) NOT NULL DEFAULT 0,
  `Name` varchar(100) DEFAULT NULL,
  ImageID int(11) NOT NULL DEFAULT 0,
  Description varchar(200) DEFAULT NULL,
  SectionID int(11) DEFAULT 1,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZLink_Hit (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(100) DEFAULT NULL,
  Description text DEFAULT NULL,
  KeyWords text DEFAULT NULL,
  Modified int(11) NOT NULL DEFAULT 0,
  Accepted int(11) DEFAULT NULL,
  Created int(11) DEFAULT NULL,
  Url text DEFAULT NULL,
  ImageID int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZLink_Link (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(100) DEFAULT NULL,
  Description text DEFAULT NULL,
  KeyWords text DEFAULT NULL,
  Modified int(11) NOT NULL DEFAULT 0,
  Accepted int(11) DEFAULT NULL,
  Created int(11) DEFAULT NULL,
  Url text DEFAULT NULL,
  ImageID int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`) 
);

CREATE TABLE eZLink_LinkCategoryDefinition (
  `ID` int(11) NOT NULL DEFAULT 0,
  LinkID int(11) NOT NULL DEFAULT 0,
  CategoryID int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZLink_LinkCategoryLink (
  `ID` int(11) NOT NULL DEFAULT 0,
  LinkID int(11) NOT NULL DEFAULT 0,
  CategoryID int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZLink_LinkGroup (
  `ID` int(11) NOT NULL DEFAULT 0,
  Parent int(11) DEFAULT 0,
  Title varchar(100) DEFAULT NULL,
  ImageID int(11) DEFAULT NULL,
  Description text DEFAULT NULL,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZLink_Type (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZLink_TypeLink (
  `ID` int(11) NOT NULL DEFAULT 0,
  LinkID int(11) DEFAULT NULL,
  TypeID int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
);
