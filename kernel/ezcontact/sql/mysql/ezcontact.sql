CREATE TABLE eZContact_Company (
  `ID` int(11) NOT NULL DEFAULT 0,
  CreatorID int(11) NOT NULL DEFAULT 0,
  `Name` varchar(50) NOT NULL DEFAULT '',
  `Comment` text DEFAULT NULL,
  ContactType int(11) NOT NULL DEFAULT 0,
  CompanyNo varchar(20) NOT NULL DEFAULT '',
  ContactID int(11) DEFAULT 0,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZContact_CompanyAddressDict (
  CompanyID int(11) NOT NULL DEFAULT 0,
  AddressID int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (CompanyID,AddressID)
);

CREATE TABLE eZContact_CompanyImageDefinition (
  CompanyID int(11) NOT NULL DEFAULT 0,
  CompanyImageID int(11) NOT NULL DEFAULT 0,
  LogoImageID int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (CompanyID)
);

CREATE TABLE eZContact_CompanyImageDict (
  CompanyID int(11) NOT NULL DEFAULT 0,
  ImageID int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (CompanyID,ImageID)
);

CREATE TABLE eZContact_CompanyIndex (
  CompanyID int(11) NOT NULL DEFAULT 0,
  `Value` varchar(255) NOT NULL DEFAULT '',
  `Type` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (CompanyID,`Value`)
);

CREATE TABLE eZContact_CompanyOnlineDict (
  CompanyID int(11) NOT NULL DEFAULT 0,
  OnlineID int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (CompanyID,OnlineID)
);

CREATE TABLE eZContact_CompanyPersonDict (
  CompanyID int(11) NOT NULL DEFAULT 0,
  PersonID int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (CompanyID,PersonID)
);

CREATE TABLE eZContact_CompanyPhoneDict (
  CompanyID int(11) NOT NULL DEFAULT 0,
  PhoneID int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (CompanyID,PhoneID)
);

CREATE TABLE eZContact_CompanyProjectDict (
  CompanyID int(11) NOT NULL DEFAULT 0,
  ProjectID int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (CompanyID,ProjectID)
);

CREATE TABLE eZContact_CompanyType (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(50) NOT NULL DEFAULT '',
  Description text DEFAULT NULL,
  ParentID int(11) NOT NULL DEFAULT 0,
  ImageID int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`),
  KEY CompanyType_ParentID (ParentID),
  KEY CompanyType_Name (`Name`)
);

CREATE INDEX CompanyType_ParentID ON eZContact_CompanyType (ParentID);
CREATE INDEX CompanyType_Name ON eZContact_CompanyType (Name);

CREATE TABLE eZContact_CompanyTypeDict (
  CompanyTypeID int NOT NULL,
  CompanyID int NOT NULL,
  PRIMARY KEY (CompanyTypeID,CompanyID)
);

CREATE TABLE eZContact_CompanyView (
  `ID` int(11) NOT NULL DEFAULT 0,
  CompanyID int(11) NOT NULL DEFAULT 0,
  Count int(11) NOT NULL DEFAULT 0,
  `Date` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`,CompanyID,`Date`)
);

CREATE TABLE eZContact_Consultation (
  `ID` int(11) NOT NULL DEFAULT 0,
  ShortDesc varchar(100) NOT NULL DEFAULT '',
  Description text NOT NULL DEFAULT '',
  `Date` int(11) DEFAULT NULL,
  StateID int(11) NOT NULL DEFAULT 0,
  EmailNotifications varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZContact_ConsultationCompanyDict (
  ConsultationID int(11) NOT NULL DEFAULT 0,
  CompanyID int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (ConsultationID,CompanyID)
);

CREATE TABLE eZContact_ConsultationCompanyUserDict (
  ConsultationID int(11) NOT NULL DEFAULT 0,
  CompanyID int(11) NOT NULL DEFAULT 0,
  UserID int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (ConsultationID,CompanyID,UserID)
);

CREATE TABLE eZContact_ConsultationGroupsDict (
  ConsultationID int(11) NOT NULL DEFAULT 0,
  GroupID int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (ConsultationID,GroupID)
);

CREATE TABLE eZContact_ConsultationPersonUserDict (
  ConsultationID int(11) NOT NULL DEFAULT 0,
  PersonID int(11) NOT NULL DEFAULT 0,
  UserID int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (ConsultationID,PersonID,UserID)
);

CREATE TABLE eZContact_ConsultationType (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(50) DEFAULT NULL,
  ListOrder int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZContact_ContactType (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(50) NOT NULL DEFAULT '',
  Description text DEFAULT NULL,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZContact_Person (
  `ID` int(11) NOT NULL DEFAULT 0,
  FirstName varchar(50) DEFAULT NULL,
  LastName varchar(50) DEFAULT NULL,
  BirthDate int(11) DEFAULT NULL,
  `Comment` text DEFAULT NULL,
  ContactTypeID int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZContact_PersonAddressDict (
  PersonID int(11) NOT NULL DEFAULT 0,
  AddressID int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (PersonID,AddressID)
);

CREATE TABLE eZContact_PersonImageDefinition (
  PersonID int(11) NOT NULL,
  PersonImageID int(11) NOT NULL DEFAULT 0,
  LogoImageID int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (PersonID)
);

CREATE TABLE eZContact_PersonIndex (
  PersonID int(11) NOT NULL DEFAULT 0,
  `Value` varchar(255) NOT NULL DEFAULT '',
  `Type` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (PersonID,`Value`)
);

CREATE TABLE eZContact_PersonOnlineDict (
  PersonID int(11) NOT NULL DEFAULT 0,
  OnlineID int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (PersonID,OnlineID)
);

CREATE TABLE eZContact_PersonPhoneDict (
  PersonID int(11) NOT NULL DEFAULT 0,
  PhoneID int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (PersonID,PhoneID)
);

CREATE TABLE eZContact_PersonProjectDict (
  PersonID int(11) NOT NULL DEFAULT 0,
  ProjectID int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (PersonID,ProjectID)
);

CREATE TABLE eZContact_ProjectType (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(50) NOT NULL DEFAULT '',
  ListOrder int(11) NOT NULL DEFAULT 0,
  ExpiryTime int(11) NOT NULL DEFAULT 0,
  WarningTime int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZContact_UserCompanyDict (
 UserID int(11) NOT NULL DEFAULT 0,
  CompanyID int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (UserID,CompanyID),
  UNIQUE KEY eZContactUserCompanyDictCompanyID (CompanyID),
  UNIQUE KEY eZContactUserCompanyDictUserID (UserID)
);

CREATE TABLE eZContact_UserPersonDict (
  UserID int(11) NOT NULL DEFAULT 0,
  PersonID int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (UserID,PersonID),
  UNIQUE KEY eZContactUserPersonDictPersonID (PersonID),
  UNIQUE KEY eZContactUserPersonDictUserID (UserID)
);

CREATE UNIQUE INDEX eZContactUserCompanyDictCompanyID ON eZContact_UserCompanyDict(CompanyID);
CREATE UNIQUE INDEX eZContactUserCompanyDictUserID ON eZContact_UserCompanyDict(UserID);

CREATE UNIQUE INDEX eZContactUserPersonDictPersonID ON eZContact_UserPersonDict(PersonID);
CREATE UNIQUE INDEX eZContactUserPersonDictUserID ON eZContact_UserPersonDict(UserID);

INSERT INTO `eZContact_ConsultationType` (`ID`, `Name`, `ListOrder`) VALUES ('1', 'Default Consultation Type #1', '0');

INSERT INTO `eZContact_ProjectType` (`ID`, `Name`, `ListOrder`, `ExpiryTime`, `WarningTime`) VALUES ('1', 'Default Project Type #1', '0', '0', '0');

