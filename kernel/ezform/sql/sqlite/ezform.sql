CREATE TABLE eZForm_FormElement (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(255) DEFAULT NULL,
  Required int(1) DEFAULT 0,
  ElementTypeID int(11) DEFAULT NULL,
  Size int(11) DEFAULT 0,
  Break int(11) DEFAULT 0);

CREATE TABLE eZForm_FormElementDict (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(255) DEFAULT NULL,
  FormID int(11) DEFAULT NULL,
  ElementID int(11) DEFAULT NULL,
  Placement int(11) DEFAULT NULL);

CREATE TABLE eZForm_FormElementFixedValueLink (
  `ID` int(11) NOT NULL DEFAULT 0,
  ElementID int(11) DEFAULT 0,
  FixedValueID int(11) DEFAULT 0);

CREATE TABLE eZForm_FormElementFixedValues (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Value` varchar(80) DEFAULT NULL);

CREATE TABLE eZForm_FormElementType (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(255) DEFAULT NULL,
  Description text DEFAULT NULL);

INSERT INTO eZForm_FormElementType VALUES (1,'text_field_item','HTML text field (input type="text")');
INSERT INTO eZForm_FormElementType VALUES (2,'text_area_item','HTML text area (textarea)');
INSERT INTO eZForm_FormElementType VALUES (3,'dropdown_item','HTML Select');
INSERT INTO eZForm_FormElementType VALUES (4,'multiple_select_item','HTML Multiple Select');
INSERT INTO eZForm_FormElementType VALUES (5,'checkbox_item','HTML CheckBox');
INSERT INTO eZForm_FormElementType VALUES (6,'radiobox_item','HTML RadioBox');

