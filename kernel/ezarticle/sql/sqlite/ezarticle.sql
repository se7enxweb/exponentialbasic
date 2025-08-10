CREATE TABLE eZArticle_Article (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(100) DEFAULT NULL,
  Contents text DEFAULT NULL,
  ContentsWriterID int(11) DEFAULT NULL,
  LinkText varchar(255) DEFAULT NULL,
  AuthorID int(11) NOT NULL DEFAULT 0,
  Modified int(11) NOT NULL DEFAULT 0,
  Created int(11) NOT NULL DEFAULT 0,
  Published int(11) NOT NULL DEFAULT 0,
  PageCount int(11) DEFAULT NULL,
  IsPublished int(11) DEFAULT 0,
  Keywords text DEFAULT NULL,
  Discuss int(11) DEFAULT 0,
  TopicID int(11) NOT NULL DEFAULT 0,
  StartDate int(11) NOT NULL DEFAULT 0,
  StopDate int(11) NOT NULL DEFAULT 0,
  ImportID varchar(255) DEFAULT NULL);

CREATE TABLE eZArticle_ArticleCategoryDefinition (
  `ID` int(11) NOT NULL DEFAULT 0,
  ArticleID int(11) NOT NULL DEFAULT 0,
  CategoryID int(11) NOT NULL DEFAULT 0);


CREATE TABLE eZArticle_ArticleCategoryLink (
  `ID` int(11) NOT NULL DEFAULT 0,
  ArticleID int(11) NOT NULL DEFAULT 0,
  CategoryID int(11) NOT NULL DEFAULT 0,
  Placement int(11) NOT NULL DEFAULT 0);

CREATE TABLE eZArticle_ArticleFileLink (
  `ID` int(11) NOT NULL DEFAULT 0,
  ArticleID int(11) NOT NULL DEFAULT 0,
  FileID int(11) NOT NULL DEFAULT 0,
  Created int(11) NOT NULL DEFAULT 0);

CREATE TABLE eZArticle_ArticleFormDict (
  `ID` int(11) NOT NULL DEFAULT 0,
  ArticleID int(11) DEFAULT NULL,
  FormID int(11) DEFAULT NULL);

CREATE TABLE eZArticle_ArticleForumLink (
  `ID` int(11) NOT NULL DEFAULT 0,
  ArticleID int(11) NOT NULL DEFAULT 0,
  ForumID int(11) NOT NULL DEFAULT 0);

CREATE TABLE eZArticle_ArticleImageDefinition (
  `ID` int(11) NOT NULL DEFAULT 0,
  ArticleID int(11) NOT NULL DEFAULT 0,
  ForumID int(11) NOT NULL DEFAULT 0);

CREATE TABLE eZArticle_ArticleImageLink (
  `ID` int(11) NOT NULL DEFAULT 0,
  ArticleID int(11) NOT NULL DEFAULT 0,
  ImageID int(11) NOT NULL DEFAULT 0,
  Created int(11) NOT NULL DEFAULT 0,
  Placement int(11) NOT NULL DEFAULT 0);

CREATE TABLE eZArticle_ArticleKeyword (
  `ID` int(11) NOT NULL DEFAULT 0,
  ArticleID int(11) NOT NULL DEFAULT 0,
  Keyword varchar(50) NOT NULL DEFAULT '',
  Automatic int(11) NOT NULL DEFAULT 0);

CREATE TABLE eZArticle_ArticlePermission (
  `ID` int(11) NOT NULL DEFAULT 0,
  ObjectID int(11) DEFAULT NULL,
  GroupID int(11) DEFAULT NULL,
  ReadPermission int(11) DEFAULT 0,
  WritePermission int(11) DEFAULT 0);

CREATE TABLE eZArticle_ArticleSectionDict (
  `ID` int(11) NOT NULL DEFAULT 0,
  ArticleID int(11) NOT NULL DEFAULT 0,
  ImageID int(11) NOT NULL DEFAULT 0,
  Created int(11) NOT NULL DEFAULT 0,
  Placement int(11) NOT NULL DEFAULT 0);

CREATE TABLE eZArticle_ArticleTypeLink (
 `ID` int(11) NOT NULL DEFAULT 0,
  ArticleID int(11) DEFAULT NULL,
  TypeID int(11) DEFAULT NULL);


CREATE TABLE eZArticle_Attribute (
  `ID` int(11) NOT NULL DEFAULT 0,
  TypeID int(11) DEFAULT NULL,
  `Name` char(150) DEFAULT NULL,
  Placement int(11) DEFAULT NULL,
  Created int(11) NOT NULL DEFAULT 0);

CREATE TABLE eZArticle_AttributeValue (
  `ID` int(11) NOT NULL DEFAULT 0,
  ArticleID int(11) DEFAULT NULL,
  AttributeID int(11) DEFAULT NULL,
  `Value` text DEFAULT NULL);

CREATE TABLE eZArticle_BulkMailCategoryLink (
  ArticleCategoryID int(11) NOT NULL DEFAULT 0,
  BulkMailCategoryID int(11) NOT NULL DEFAULT 0);

CREATE TABLE eZArticle_Category (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(100) DEFAULT NULL,
  Description text DEFAULT NULL,
  ParentID int(11) DEFAULT 0,
  ExcludeFromSearch int(11) DEFAULT 0,
  SortMode int(11) NOT NULL DEFAULT 1,
  OwnerID int(11) DEFAULT 0,
  Placement int(11) DEFAULT 0,
  SectionID int(11) NOT NULL DEFAULT 0,
  ImageID int(11) DEFAULT NULL,
  EditorGroupID int(11) DEFAULT 0,
  ListLimit int(11) DEFAULT 0);

CREATE TABLE eZArticle_CategoryPermission (
  `ID` int(11) NOT NULL DEFAULT 0,
  ObjectID int(11) DEFAULT NULL,
  GroupID int(11) DEFAULT NULL,
  ReadPermission int(11) DEFAULT 0,
  WritePermission int(11) DEFAULT 0);

CREATE TABLE eZArticle_CategoryReaderLink (
  `ID` int(11) NOT NULL DEFAULT 0,
  CategoryID int(11) NOT NULL DEFAULT 0,
  GroupID int(11) NOT NULL DEFAULT 0,
  Created int(11) NOT NULL DEFAULT 0);

CREATE TABLE eZArticle_Link (
  `ID` int(11) NOT NULL DEFAULT 0,
  SectionID int(11) NOT NULL DEFAULT 0,
  `Name` varchar(60) DEFAULT NULL,
  URL text DEFAULT NULL,
  Placement int(11) NOT NULL DEFAULT 0,
  ModuleType int(11) NOT NULL DEFAULT 0);

CREATE TABLE `eZArticle_LinkSection` (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(30) DEFAULT NULL);

CREATE TABLE eZArticle_Log (
  `ID` int(11) NOT NULL DEFAULT 0,
  ArticleID int(11) NOT NULL DEFAULT 0,
  Created int(11) NOT NULL DEFAULT 0,
  Message text NOT NULL DEFAULT '',
  UserID int(11) NOT NULL DEFAULT 0);

CREATE TABLE eZArticle_Topic (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(255) DEFAULT NULL,
  Description text DEFAULT NULL,
  Created int(11) NOT NULL DEFAULT 0);

CREATE TABLE eZArticle_Type (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(150) DEFAULT NULL);

CREATE TABLE eZArticle_Word (
  `ID` int(11) NOT NULL DEFAULT 0,
  Frequency float DEFAULT 0.2,
  Word varchar(50) NOT NULL DEFAULT '');

CREATE TABLE eZArticle_ArticleMediaLink (
  `ID` int(11) NOT NULL DEFAULT 0,
  ArticleID int(11) NOT NULL DEFAULT 0,
  MediaID int(11) NOT NULL DEFAULT 0,
  Created int(11) DEFAULT NULL);

CREATE TABLE eZArticle_ArticleWordLink (
  ArticleID int(11) NOT NULL DEFAULT 0,
  Frequency float DEFAULT 0.2,
  WordID int(11) NOT NULL DEFAULT 0);

CREATE TABLE eZArticle_ArticleKeywordFirstLetter (
  `ID` int(11) NOT NULL DEFAULT 0,
  Letter char(1) NOT NULL DEFAULT ''
);


CREATE INDEX Article_Name ON eZArticle_Article (Name);
CREATE INDEX Article_Published ON eZArticle_Article (Published);
# CREATE FULLTEXT INDEX Article_Fulltext ON eZArticle_Article (Contents);
# CREATE FULLTEXT INDEX Article_FulltextName ON eZArticle_Article (Name);

CREATE INDEX Link_ArticleID ON eZArticle_ArticleCategoryLink (ArticleID);
CREATE INDEX Link_CategoryID ON eZArticle_ArticleCategoryLink (CategoryID);
CREATE INDEX Link_Placement ON eZArticle_ArticleCategoryLink (Placement);

CREATE INDEX WordLink_ArticleID ON eZArticle_ArticleWordLink (ArticleID);
CREATE INDEX WordLink_WordID ON eZArticle_ArticleWordLink (WordID);
CREATE INDEX Word_Word ON eZArticle_Word (Word);
CREATE UNIQUE INDEX Word_ID ON eZArticle_Word (ID);
CREATE UNIQUE INDEX ArticleID ON eZArticle_ArticleImageDefinition (ArticleID);

CREATE INDEX ArticlePermission_ObjectID ON eZArticle_ArticlePermission (ObjectID);
CREATE INDEX ArticlePermission_GroupID ON eZArticle_ArticlePermission (GroupID);
CREATE INDEX ArticlePermission_WritePermission ON eZArticle_ArticlePermission (WritePermission);
CREATE INDEX ArticlePermission_ReadPermission ON eZArticle_ArticlePermission (ReadPermission);

CREATE INDEX Def_ArticleID ON eZArticle_ArticleCategoryDefinition (ArticleID);
CREATE INDEX Def_CategoryID ON eZArticle_ArticleCategoryDefinition (CategoryID);

CREATE INDEX ArticleKeyword_Keyword ON eZArticle_ArticleKeyword (Keyword);
CREATE INDEX ArticleKeyword_ArticleID ON eZArticle_ArticleKeyword (ArticleID);

CREATE INDEX ArticleAttribute_Placement ON eZArticle_Attribute (Placement);
CREATE INDEX ArticleAttributeValue_ArticleID ON eZArticle_AttributeValue (ArticleID, AttributeID);
