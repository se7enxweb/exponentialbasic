CREATE TABLE eZURLTranslator_URL (
  ID int NOT NULL,
  Source varchar(200) default NULL,
  Dest varchar(200) default NULL,
  Created int NOT NULL,
  PRIMARY KEY (ID)
);
