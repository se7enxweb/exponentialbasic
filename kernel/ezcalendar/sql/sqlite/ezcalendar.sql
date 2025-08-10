CREATE TABLE eZCalendar_AppointmentType (
  `ID` int(11) NOT NULL DEFAULT 0,
  ParentID int(11) NOT NULL DEFAULT 0,
  Description varchar(200) DEFAULT NULL,
  `Name` varchar(200) DEFAULT NULL,
  ExcludeFromSearch int(11) DEFAULT NULL);CREATE TABLE eZContact_Company (
  `ID` int(11) NOT NULL DEFAULT 0,
  CreatorID int(11) NOT NULL DEFAULT 0,
  `Name` varchar(50) NOT NULL DEFAULT '',
  `Comment` text DEFAULT NULL,
  ContactType int(11) NOT NULL DEFAULT 0,
  CompanyNo varchar(20) NOT NULL DEFAULT '',
  ContactID int(11) DEFAULT 0);

