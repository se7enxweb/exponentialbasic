CREATE TABLE eZLink_Hit (
  ID int NOT NULL,
  Link int default NULL,
  Time int NOT NULL,
  RemoteIP varchar(15) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZLink_Link (
  ID int NOT NULL ,
  Name varchar(100) default NULL,
  Description text,
  KeyWords text default NULL,
  Modified int NOT NULL,
  Accepted int,
  Created int default NULL,
  Url text default NULL,
  ImageID int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZLink_LinkCategoryDefinition (
  ID int NOT NULL ,
  LinkID int NOT NULL,
  CategoryID int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZLink_LinkCategoryLink (
  ID int NOT NULL ,
  LinkID int NOT NULL,
  CategoryID int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZLink_Category (
  ID int NOT NULL,
  Parent int NOT NULL,
  Name varchar(100) default NULL,
  ImageID int NOT NULL,
  Description varchar(200),
  SectionID int default '1',
  PRIMARY KEY (ID)
);

CREATE TABLE eZLink_Attribute (
  ID int NOT NULL,
  TypeID int default NULL,
  Name varchar(150) default NULL,
  Created int default NULL,
  Placement int default 0,
  Unit varchar(8) default NULL,
  PRIMARY KEY (ID)
);


CREATE TABLE eZLink_AttributeValue (
  ID int NOT NULL,
  LinkID int default NULL,
  AttributeID int default NULL,
  Value char(200) default NULL,
  PRIMARY KEY (ID)
);


CREATE TABLE eZLink_LinkGroup (
  ID int NOT NULL,
  Parent int default '0',
  Title varchar(100) default NULL,
  ImageID int default NULL,
  Description text,
  PRIMARY KEY (ID)
);


CREATE TABLE eZLink_Type (
  ID int NOT NULL,
  Name varchar(150) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZLink_TypeLink (
  ID int NOT NULL,
  LinkID int default NULL,
  TypeID int default NULL,
  PRIMARY KEY (ID)
);
