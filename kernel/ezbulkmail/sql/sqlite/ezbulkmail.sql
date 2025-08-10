CREATE TABLE eZBulkMail_Mail (
  `ID` int(11) NOT NULL DEFAULT 0,
  UserID int(11) DEFAULT 0,
  FromField varchar(100) DEFAULT NULL,
  FromName varchar(100) DEFAULT NULL,
  ReplyTo varchar(100) DEFAULT NULL,
  `Subject` varchar(255) DEFAULT NULL,
  BodyText text DEFAULT NULL,
  SentDate int(14) DEFAULT 0,
  IsDraft int(1) NOT NULL DEFAULT 0);

CREATE TABLE eZBulkMail_MailCategoryLink (
  MailID int(11) NOT NULL DEFAULT 0,
  CategoryID int(11) NOT NULL DEFAULT 0);

CREATE TABLE eZBulkMail_MailTemplateLink (
  MailID int(11) NOT NULL DEFAULT 0,
  TemplateID int(11) NOT NULL DEFAULT 0);

CREATE TABLE eZBulkMail_SentLog (
  `ID` int(11) NOT NULL DEFAULT 0,
  MailID int(11) NOT NULL DEFAULT 0,
  Mail varchar(255) DEFAULT NULL,
  SentDate int(11) DEFAULT NULL);

CREATE TABLE eZBulkMail_SubscriptionAddress (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Password` varchar(50) NOT NULL DEFAULT '',
  EMail varchar(255) DEFAULT NULL);

CREATE TABLE eZBulkMail_SubscriptionLink (
  CategoryID int(11) NOT NULL DEFAULT 0,
  AddressID int(11) NOT NULL DEFAULT 0);

CREATE TABLE eZBulkMail_Template (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(200) DEFAULT NULL,
  Description text DEFAULT NULL,
  Header text DEFAULT NULL,
  Footer text DEFAULT NULL);

CREATE TABLE eZBulkMail_GroupCategoryLink (
  CategoryID int(11) NOT NULL DEFAULT 0,
  GroupID int(11) NOT NULL DEFAULT 0);

CREATE TABLE eZBulkMail_Forgot (
  `ID` int(11) NOT NULL DEFAULT 0,
  Mail varchar(255) NOT NULL DEFAULT '',
  `Password` varchar(50) NOT NULL DEFAULT '',
  `Hash` varchar(33) DEFAULT NULL,
  `Time` int(11) DEFAULT NULL);

CREATE TABLE eZBulkMail_CategoryDelay (
  `ID` int(11) NOT NULL DEFAULT 0,
  CategoryID int(11) NOT NULL DEFAULT 0,
  AddressID int(11) NOT NULL DEFAULT 0,
  Delay int(11) DEFAULT 0,
  MailID int(11) DEFAULT 0);

CREATE TABLE eZBulkMail_Offset (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Hour` int(11) DEFAULT NULL,
  Daily int(11) DEFAULT NULL,
  Weekly int(11) DEFAULT NULL,
  Monthly int(11) DEFAULT NULL);

CREATE TABLE eZBulkMail_SubscriptionCategorySettings (
  `ID` int(11) NOT NULL DEFAULT 0,
  CategoryID int(11) NOT NULL DEFAULT 0,
  AddressID int(11) NOT NULL DEFAULT 0,
  Delay int(11) DEFAULT 0);

CREATE TABLE eZBulkMail_UserCategoryDelay (
  `ID` int(11) NOT NULL DEFAULT 0,
  CategoryID int(11) DEFAULT 0,
  UserID int(11) DEFAULT 0,
  Delay int(11) DEFAULT 0,
  MailID int(11) DEFAULT 0);

CREATE TABLE eZBulkMail_UserCategoryLink (
  UserID int(11) NOT NULL DEFAULT 0,
  CategoryID int(11) NOT NULL DEFAULT 0);

CREATE TABLE eZBulkMail_UserCategorySettings (
  `ID` int(11) NOT NULL DEFAULT 0,
  CategoryID int(11) DEFAULT 0,
  UserID int(11) DEFAULT 0,
  Delay int(11) DEFAULT 0);CREATE TABLE eZCalendar_Appointment (
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
  Priority int(11) NOT NULL DEFAULT 1);

