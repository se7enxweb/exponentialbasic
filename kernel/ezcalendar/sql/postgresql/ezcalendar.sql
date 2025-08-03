CREATE TABLE eZCalendar_Appointment (
  ID int NOT NULL,
  UserID int DEFAULT '0' NOT NULL,
  Date int,
  Duration int,
  AllDay int,
  AppointmentTypeID int DEFAULT '0' NOT NULL,
  EMailNotice int DEFAULT '0',
  IsPrivate int,
  Name varchar(200),
  Description text,
  Priority int DEFAULT '1' NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZCalendar_AppointmentType (
  ID int NOT NULL,
  ParentID int DEFAULT '0' NOT NULL,
  Description varchar(200) DEFAULT NULL,
  Name varchar(200),
  PRIMARY KEY (ID)
);
