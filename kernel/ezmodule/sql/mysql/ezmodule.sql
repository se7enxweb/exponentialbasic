CREATE TABLE eZModule_LinkModuleType (
  ID int NOT NULL,
  Module varchar(40) NOT NULL default '',
  Type varchar(40) NOT NULL default '',
  PRIMARY KEY (ID,Module,Type)
);
