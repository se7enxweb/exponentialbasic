CREATE TABLE eZTodo_Category (
  ID int(11) NOT NULL,
  Description text,
  Name varchar(30),
  PRIMARY KEY (ID)
);

CREATE TABLE eZTodo_Priority (
  ID int(11) NOT NULL,
  Name varchar(30),
  PRIMARY KEY (ID)
);

CREATE TABLE eZTodo_Todo (
  ID int(11) NOT NULL,
  Category int(11),
  Priority int(11),
  Permission int(11) default 0,
  UserID int(11),
  OwnerID int(11),
  Name varchar(30),
  Date int(11),
  Due int(11),
  Description text,
  Status int(11) DEFAULT '0',
  IsPublic int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTodo_Status (
  ID int(11) NOT NULL,
  Name varchar(30),
  Description text,
  PRIMARY KEY (ID)
);

CREATE TABLE eZTodo_Log (
  ID int(11) NOT NULL,
  Log text,
  Created int(11) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZTodo_TodoLogLink (
  ID int(11) NOT NULL auto_increment,
  TodoID int(11) default NULL,
  LogID int(11) default NULL,
  PRIMARY KEY (ID)
);

INSERT INTO eZTodo_Category VALUES (1,'','Work');    

INSERT INTO eZTodo_Status (Description, ID, Name) VALUES (NULL,1,'Not done');
INSERT INTO eZTodo_Status (Description, ID, Name) VALUES (NULL,2,'Done');

INSERT INTO eZTodo_Priority (ID, Name) VALUES (1,'Low');
INSERT INTO eZTodo_Priority (ID, Name) VALUES (2,'Medium');
INSERT INTO eZTodo_Priority (ID, Name) VALUES (3,'High');