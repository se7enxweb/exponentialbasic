CREATE TABLE eZFileManager_FileFolderLink (
 `ID` int(11) NOT NULL DEFAULT 0,
  FolderID int(11) NOT NULL DEFAULT 0,
  FileID int(11) NOT NULL DEFAULT 0);

CREATE TABLE eZFileManager_FilePageViewLink (
  `ID` int(11) NOT NULL DEFAULT 0,
  PageViewID int(11) NOT NULL DEFAULT 0,
  FileID int(11) NOT NULL DEFAULT 0);

CREATE TABLE eZFileManager_FolderPermission (
  `ID` int(11) NOT NULL DEFAULT 0,
  ObjectID int(11) DEFAULT NULL,
  GroupID int(11) DEFAULT NULL,
  ReadPermission int(11) DEFAULT 0,
  WritePermission int(11) DEFAULT 0,
  UploadPermission int(11) DEFAULT 0);

CREATE TABLE eZFileManager_Folder (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(100) DEFAULT NULL,
  Description text DEFAULT NULL,
  ParentID int(11) NOT NULL DEFAULT 0,
  ReadPermission int(11) DEFAULT 1,
  WritePermission int(11) DEFAULT 1,
  SectionID int(11) DEFAULT 1,
  UserID int(11) DEFAULT NULL);

CREATE TABLE eZFileManager_FilePermission (

  `ID` int(11) NOT NULL DEFAULT 0,
  `ObjectID` int(11) DEFAULT NULL,
  `GroupID` int(11) DEFAULT NULL,
  `ReadPermission` int(11) DEFAULT 0,
  `WritePermission` int(11) DEFAULT 0,
  `UploadPermission` int(11) DEFAULT 0);CREATE TABLE eZForm_Form (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(255) DEFAULT NULL,
  Receiver varchar(255) DEFAULT NULL,
  CC varchar(255) DEFAULT NULL,
  Sender varchar(255) DEFAULT NULL,
  SendAsUser varchar(1) DEFAULT NULL,
  CompletedPage varchar(255) DEFAULT NULL,
  InstructionPage varchar(255) DEFAULT NULL,
  Counter int(11) DEFAULT NULL);

