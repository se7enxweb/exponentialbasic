CREATE TABLE eZModule_LinkModuleType (
 `ID` int(11) NOT NULL DEFAULT 0,
  Module varchar(40) NOT NULL DEFAULT '',
  `Type` varchar(40) NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`,Module,`Type`)
);

