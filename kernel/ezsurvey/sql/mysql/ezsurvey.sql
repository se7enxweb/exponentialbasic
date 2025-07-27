--
-- Table structure for table `eZSurvey_Question`
--

DROP TABLE IF EXISTS `eZSurvey_Question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `eZSurvey_Question` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `SurveyID` int(11) NOT NULL DEFAULT 0,
  `QuestionTypeID` int(11) NOT NULL DEFAULT 0,
  `ResultID` int(11) DEFAULT NULL,
  `Length` int(11) NOT NULL DEFAULT 0,
  `Position` int(11) NOT NULL DEFAULT 0,
  `Content` text NOT NULL DEFAULT '',
  `Initial` text DEFAULT NULL,
  `Required` enum('Y','N') NOT NULL DEFAULT 'N',
  `Public` varchar(3) NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`ID`)
) AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `eZSurvey_Question`
--

LOCK TABLES `eZSurvey_Question` WRITE;
/*!40000 ALTER TABLE `eZSurvey_Question` DISABLE KEYS */;
INSERT INTO `eZSurvey_Question` (`ID`, `SurveyID`, `QuestionTypeID`, `ResultID`, `Length`, `Position`, `Content`, `Initial`, `Required`, `Public`) VALUES (1,1,8,0,0,1,'Rating?','','Y',''),
(2,1,3,0,0,2,'Review (essay box)','','Y',''),
(3,2,8,0,0,3,'New Question 1','','N',''),
(4,2,8,0,0,4,'New Question 2','','N',''),
(5,2,8,0,0,5,'New Question 3','','N','');
/*!40000 ALTER TABLE `eZSurvey_Question` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `eZSurvey_QuestionChoice`
--

DROP TABLE IF EXISTS `eZSurvey_QuestionChoice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `eZSurvey_QuestionChoice` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `QuestionID` int(11) NOT NULL DEFAULT 0,
  `Content` text NOT NULL DEFAULT '',
  `Value` text DEFAULT NULL,
  PRIMARY KEY (`ID`)
) AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `eZSurvey_QuestionChoice`
--

LOCK TABLES `eZSurvey_QuestionChoice` WRITE;
/*!40000 ALTER TABLE `eZSurvey_QuestionChoice` DISABLE KEYS */;
INSERT INTO `eZSurvey_QuestionChoice` (`ID`, `QuestionID`, `Content`, `Value`) VALUES (1,1,'Fit and finish',''),
(2,1,'Usability',''),
(3,1,'Value',''),
(4,3,'Choice 1',''),
(5,3,'Choice 2',''),
(6,4,'value test1?',''),
(7,5,'Choice 1',''),
(8,5,'Choice 2','');
/*!40000 ALTER TABLE `eZSurvey_QuestionChoice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `eZSurvey_QuestionType`
--

DROP TABLE IF EXISTS `eZSurvey_QuestionType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `eZSurvey_QuestionType` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Type` varchar(32)/*old*/ NOT NULL DEFAULT '',
  `HasChoices` enum('Y','N') NOT NULL DEFAULT 'Y',
  `ResponseTable` varchar(32)/*old*/ NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`)
) AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `eZSurvey_QuestionType`
--

LOCK TABLES `eZSurvey_QuestionType` WRITE;
/*!40000 ALTER TABLE `eZSurvey_QuestionType` DISABLE KEYS */;
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
/*!40000 ALTER TABLE `eZSurvey_QuestionType` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `eZSurvey_Response`
--

DROP TABLE IF EXISTS `eZSurvey_Response`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `eZSurvey_Response` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `SurveyID` int(11) NOT NULL DEFAULT 0,
  `Submitted` int(11) DEFAULT NULL,
  `Complete` enum('Y','N') NOT NULL DEFAULT 'N',
  `UserID` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `eZSurvey_Response`
--

LOCK TABLES `eZSurvey_Response` WRITE;
/*!40000 ALTER TABLE `eZSurvey_Response` DISABLE KEYS */;
INSERT INTO `eZSurvey_Response` (`ID`, `SurveyID`, `Submitted`, `Complete`, `UserID`) VALUES (1,1,1050149950,'Y',1);
/*!40000 ALTER TABLE `eZSurvey_Response` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `eZSurvey_ResponseBool`
--

DROP TABLE IF EXISTS `eZSurvey_ResponseBool`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `eZSurvey_ResponseBool` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ResponseID` int(11) NOT NULL DEFAULT 0,
  `QuestionID` int(11) NOT NULL DEFAULT 0,
  `ChoiceID` enum('Y','N') NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`ID`)
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `eZSurvey_ResponseBool`
--

LOCK TABLES `eZSurvey_ResponseBool` WRITE;
/*!40000 ALTER TABLE `eZSurvey_ResponseBool` DISABLE KEYS */;
/*!40000 ALTER TABLE `eZSurvey_ResponseBool` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `eZSurvey_ResponseDate`
--

DROP TABLE IF EXISTS `eZSurvey_ResponseDate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `eZSurvey_ResponseDate` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ResponseID` int(11) NOT NULL DEFAULT 0,
  `QuestionID` int(11) NOT NULL DEFAULT 0,
  `Response` varchar(10)/*old*/ DEFAULT NULL,
  PRIMARY KEY (`ID`)
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `eZSurvey_ResponseDate`
--

LOCK TABLES `eZSurvey_ResponseDate` WRITE;
/*!40000 ALTER TABLE `eZSurvey_ResponseDate` DISABLE KEYS */;
/*!40000 ALTER TABLE `eZSurvey_ResponseDate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `eZSurvey_ResponseMultiple`
--

DROP TABLE IF EXISTS `eZSurvey_ResponseMultiple`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `eZSurvey_ResponseMultiple` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ResponseID` int(11) NOT NULL DEFAULT 0,
  `QuestionID` int(11) NOT NULL DEFAULT 0,
  `ChoiceID` int(11) NOT NULL DEFAULT 0,
  `Other` text DEFAULT NULL,
  PRIMARY KEY (`ID`)
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `eZSurvey_ResponseMultiple`
--

LOCK TABLES `eZSurvey_ResponseMultiple` WRITE;
/*!40000 ALTER TABLE `eZSurvey_ResponseMultiple` DISABLE KEYS */;
/*!40000 ALTER TABLE `eZSurvey_ResponseMultiple` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `eZSurvey_ResponseRank`
--

DROP TABLE IF EXISTS `eZSurvey_ResponseRank`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `eZSurvey_ResponseRank` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ResponseID` int(11) NOT NULL DEFAULT 0,
  `QuestionID` int(11) NOT NULL DEFAULT 0,
  `ChoiceID` int(11) NOT NULL DEFAULT 0,
  `Rank` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`)
) AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `eZSurvey_ResponseRank`
--

LOCK TABLES `eZSurvey_ResponseRank` WRITE;
/*!40000 ALTER TABLE `eZSurvey_ResponseRank` DISABLE KEYS */;
INSERT INTO `eZSurvey_ResponseRank` (`ID`, `ResponseID`, `QuestionID`, `ChoiceID`, `Rank`) VALUES (1,1,1,1,2),
(2,1,1,2,3),
(3,1,1,3,2);
/*!40000 ALTER TABLE `eZSurvey_ResponseRank` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `eZSurvey_ResponseSingle`
--

DROP TABLE IF EXISTS `eZSurvey_ResponseSingle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `eZSurvey_ResponseSingle` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ResponseID` int(11) NOT NULL DEFAULT 0,
  `QuestionID` int(11) NOT NULL DEFAULT 0,
  `ChoiceID` int(11) NOT NULL DEFAULT 0,
  `Other` text DEFAULT NULL,
  PRIMARY KEY (`ID`)
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `eZSurvey_ResponseSingle`
--

LOCK TABLES `eZSurvey_ResponseSingle` WRITE;
/*!40000 ALTER TABLE `eZSurvey_ResponseSingle` DISABLE KEYS */;
/*!40000 ALTER TABLE `eZSurvey_ResponseSingle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `eZSurvey_ResponseText`
--

DROP TABLE IF EXISTS `eZSurvey_ResponseText`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `eZSurvey_ResponseText` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ResponseID` int(11) NOT NULL DEFAULT 0,
  `QuestionID` int(11) NOT NULL DEFAULT 0,
  `Response` text DEFAULT NULL,
  PRIMARY KEY (`ID`)
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `eZSurvey_ResponseText`
--

LOCK TABLES `eZSurvey_ResponseText` WRITE;
/*!40000 ALTER TABLE `eZSurvey_ResponseText` DISABLE KEYS */;
/*!40000 ALTER TABLE `eZSurvey_ResponseText` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `eZSurvey_Survey`
--

DROP TABLE IF EXISTS `eZSurvey_Survey`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `eZSurvey_Survey` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL DEFAULT 0,
  `Public` varchar(3) NOT NULL DEFAULT 'Y',
  `Status` varchar(64)/*old*/ NOT NULL DEFAULT 'EDIT',
  `Title` varchar(255)/*old*/ NOT NULL DEFAULT '',
  `SubTitle` text DEFAULT NULL,
  `EMail` varchar(64)/*old*/ DEFAULT NULL,
  `Info` text DEFAULT NULL,
  `ThanksPage` varchar(255)/*old*/ DEFAULT NULL,
  `ThankHead` varchar(255)/*old*/ DEFAULT NULL,
  `ThankBody` text DEFAULT NULL,
  `Changed` int(11) DEFAULT NULL,
  `SectionID` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `eZSurvey_Survey`
--

LOCK TABLES `eZSurvey_Survey` WRITE;
/*!40000 ALTER TABLE `eZSurvey_Survey` DISABLE KEYS */;
INSERT INTO `eZSurvey_Survey` (`ID`, `UserID`, `Public`, `Status`, `Title`, `SubTitle`, `EMail`, `Info`, `ThanksPage`, `ThankHead`, `ThankBody`, `Changed`, `SectionID`) VALUES (1,1,'','ACTIVE','Test Survey','This is a test survey.','admin@example.com','Additional info on the test survey.','','Thanks!','Thanks for your entry!',1050149461,3),
(2,1,'','EDIT','Survey Title 2','','','','','','',1060605345,3);
/*!40000 ALTER TABLE `eZSurvey_Survey` ENABLE KEYS */;
UNLOCK TABLES;
