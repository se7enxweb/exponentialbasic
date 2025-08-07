CREATE TABLE eZCalendar_Appointment (
  `ID` int(11) NOT NULL DEFAULT 0,
  UserID int(11) NOT NULL DEFAULT 0,
  `Date` int(11) DEFAULT NULL,
  Duration int(11) DEFAULT NULL,
  AllDay int(11) DEFAULT NULL,
  AppointmentTypeID int(11) NOT NULL DEFAULT 0,
  EMailNotice int(11) DEFAULT 0,
  IsPrivate int(11) DEFAULT NULL,
  `Name` varchar(200) DEFAULT NULL,
  Description text DEFAULT NULL,
  Priority int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`ID`)
);

CREATE TABLE eZCalendar_AppointmentType (
  `ID` int(11) NOT NULL DEFAULT 0,
  ParentID int(11) NOT NULL DEFAULT 0,
  Description varchar(200) DEFAULT NULL,
  `Name` varchar(200) DEFAULT NULL,
  ExcludeFromSearch int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
);