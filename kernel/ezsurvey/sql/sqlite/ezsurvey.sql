CREATE TABLE `eZSurvey_Question` (
  `ID` int(11) NOT NULL,
  SurveyID int(11) NOT NULL DEFAULT 0,
  QuestionTypeID int(11) NOT NULL DEFAULT 0,
  ResultID int(11) DEFAULT NULL,
  Length int(11) NOT NULL DEFAULT 0,
  Position int(11) NOT NULL DEFAULT 0,
  Content text NOT NULL DEFAULT '',
  Initial text DEFAULT NULL,
  Required CHECK( "Required" IN ("Y","N") ) NOT NULL DEFAULT 'N',
  Public varchar(3) NOT NULL DEFAULT 'Y');

--
-- Dumping data for table `eZSurvey_Question`
--

INSERT INTO `eZSurvey_Question` (`ID`, `SurveyID`, `QuestionTypeID`, `ResultID`, `Length`, `Position`, `Content`, `Initial`, `Required`, `Public`) VALUES (1,1,8,0,0,1,'Rating?','','Y',''),
(2,1,3,0,0,2,'Review (essay box)','','Y',''),
(3,2,8,0,0,3,'New Question 1','','N',''),
(4,2,8,0,0,4,'New Question 2','','N',''),
(5,2,8,0,0,5,'New Question 3','','N','');

DROP TABLE IF EXISTS `eZSurvey_QuestionChoice`;
CREATE TABLE `eZSurvey_QuestionChoice` (
  `ID` int(11) NOT NULL,
  QuestionID int(11) NOT NULL DEFAULT 0,
  Content text NOT NULL DEFAULT '',
  `Value` text DEFAULT NULL);

--
-- Dumping data for table `eZSurvey_QuestionChoice`
--

INSERT INTO `eZSurvey_QuestionChoice` (`ID`, `QuestionID`, `Content`, `Value`) VALUES (1,1,'Fit and finish',''),
(2,1,'Usability',''),
(3,1,'Value',''),
(4,3,'Choice 1',''),
(5,3,'Choice 2',''),
(6,4,'value test1?',''),
(7,5,'Choice 1',''),
(8,5,'Choice 2','');

--
-- Table structure for table `eZSurvey_QuestionType`
--

DROP TABLE IF EXISTS `eZSurvey_QuestionType`;
CREATE TABLE `eZSurvey_QuestionType` (
  `ID` int(11) NOT NULL,
  `Type` varchar(32) NOT NULL DEFAULT '',
  HasChoices CHECK( "Required" IN ("Y","N") ) NOT NULL DEFAULT 'Y',
  ResponseTable varchar(32) NOT NULL DEFAULT '');

--
-- Dumping data for table `eZSurvey_QuestionType`
--

INSERT INTO `eZSurvey_QuestionType` (`ID`, `Type`, `HasChoices`, `ResponseTable`) VALUES (1,'Yes/No','N','eZResponseBool'),
(2,'Text Box','N','eZResponseText'),
(3,'Essay Box','N','eZResponseText'),
(4,'Radio Buttons','Y','eZResponseSingle'),
(5,'Check Boxes','Y','eZResponseMultiple'),
(6,'Dropdown Box','Y','eZResponseSingle'),
(8,'Rate (scale 1..5)','Y','eZResponseRank'),
(9,'Date','N','eZResponseDate'),
(10,'Numeric','N','eZResponseText'),
(99,'Page Break','N',''),
(100,'Section Text','N','');

--
-- Table structure for table `eZSurvey_Response`
--

DROP TABLE IF EXISTS `eZSurvey_Response`;
CREATE TABLE `eZSurvey_Response` (
  `ID` int(11) NOT NULL,
  SurveyID int(11) NOT NULL DEFAULT 0,
  Submitted int(11) DEFAULT NULL,
  Complete CHECK( "Required" IN ("Y","N") ) NOT NULL DEFAULT 'N',
  UserID int(11) DEFAULT NULL);

--
-- Dumping data for table `eZSurvey_Response`
--

INSERT INTO `eZSurvey_Response` (`ID`, `SurveyID`, `Submitted`, `Complete`, `UserID`) VALUES (1,1,1050149950,'Y',1);

--
-- Table structure for table `eZSurvey_ResponseBool`
--

DROP TABLE IF EXISTS `eZSurvey_ResponseBool`;
CREATE TABLE `eZSurvey_ResponseBool` (
  `ID` int(11) NOT NULL,
  ResponseID int(11) NOT NULL DEFAULT 0,
  QuestionID int(11) NOT NULL DEFAULT 0,
  ChoiceID CHECK( "Required" IN ("Y","N") ) NOT NULL DEFAULT 'Y');

--
-- Table structure for table `eZSurvey_ResponseDate`
--

DROP TABLE IF EXISTS `eZSurvey_ResponseDate`;
CREATE TABLE `eZSurvey_ResponseDate` (
  `ID` int(11) NOT NULL,
  ResponseID int(11) NOT NULL DEFAULT 0,
  QuestionID int(11) NOT NULL DEFAULT 0,
  Response varchar(10) DEFAULT NULL);

--
-- Table structure for table `eZSurvey_ResponseMultiple`
--

DROP TABLE IF EXISTS `eZSurvey_ResponseMultiple`;
CREATE TABLE `eZSurvey_ResponseMultiple` (
 `ID` int(11) NOT NULL,
  ResponseID int(11) NOT NULL DEFAULT 0,
  QuestionID int(11) NOT NULL DEFAULT 0,
  ChoiceID int(11) NOT NULL DEFAULT 0,
  Other text DEFAULT NULL);

--
-- Table structure for table `eZSurvey_ResponseRank`
--

DROP TABLE IF EXISTS `eZSurvey_ResponseRank`;
CREATE TABLE `eZSurvey_ResponseRank` (
  `ID` int(11) NOT NULL,
  ResponseID int(11) NOT NULL DEFAULT 0,
  QuestionID int(11) NOT NULL DEFAULT 0,
  ChoiceID int(11) NOT NULL DEFAULT 0,
  Rank int(11) NOT NULL DEFAULT 0);

--
-- Dumping data for table `eZSurvey_ResponseRank`
--

INSERT INTO `eZSurvey_ResponseRank` (`ID`, `ResponseID`, `QuestionID`, `ChoiceID`, `Rank`) VALUES (1,1,1,1,2),
(2,1,1,2,3),
(3,1,1,3,2);

--
-- Table structure for table `eZSurvey_ResponseSingle`
--

DROP TABLE IF EXISTS `eZSurvey_ResponseSingle`;
CREATE TABLE `eZSurvey_ResponseSingle` (
  `ID` int(11) NOT NULL,
  ResponseID int(11) NOT NULL DEFAULT 0,
  QuestionID int(11) NOT NULL DEFAULT 0,
  ChoiceID int(11) NOT NULL DEFAULT 0,
  Other text DEFAULT NULL);

--
-- Table structure for table `eZSurvey_ResponseText`
--

DROP TABLE IF EXISTS `eZSurvey_ResponseText`;
CREATE TABLE `eZSurvey_ResponseText` (
  `ID` int(11) NOT NULL,
  ResponseID int(11) NOT NULL DEFAULT 0,
  QuestionID int(11) NOT NULL DEFAULT 0,
  Response text DEFAULT NULL);

--
-- Table structure for table `eZSurvey_Survey`
--

DROP TABLE IF EXISTS `eZSurvey_Survey`;
CREATE TABLE `eZSurvey_Survey` (
  `ID` int(11) NOT NULL,
  UserID int(11) NOT NULL DEFAULT 0,
  Public varchar(3) NOT NULL DEFAULT 'Y',
  `Status` varchar(64) NOT NULL DEFAULT 'EDIT',
  Title varchar(255) NOT NULL DEFAULT '',
  SubTitle text DEFAULT NULL,
  EMail varchar(64) DEFAULT NULL,
  Info text DEFAULT NULL,
  ThanksPage varchar(255) DEFAULT NULL,
  ThankHead varchar(255) DEFAULT NULL,
  ThankBody text DEFAULT NULL,
  `Changed` int(11) DEFAULT NULL,
  SectionID int(11) DEFAULT NULL);

--
-- Dumping data for table `eZSurvey_Survey`
--

INSERT INTO `eZSurvey_Survey` (`ID`, `UserID`, `Public`, `Status`, `Title`, `SubTitle`, `EMail`, `Info`, `ThanksPage`, `ThankHead`, `ThankBody`, `Changed`, `SectionID`) VALUES (1,1,'','ACTIVE','Test Survey','This is a test survey.','admin@example.com','Additional info on the test survey.','','Thanks!','Thanks for your entry!',1050149461,3),
(2,1,'','EDIT','Survey Title 2','','','','','','',1060605345,3);


