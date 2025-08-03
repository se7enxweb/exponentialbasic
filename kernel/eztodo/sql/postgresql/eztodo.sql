CREATE TABLE eZTodo_Category (
  ID int NOT NULL,
  Description text,
  Name varchar(30),
  PRIMARY KEY (ID)
);

CREATE TABLE eZTodo_Priority (
  ID int NOT NULL,
  Name varchar(30),
  PRIMARY KEY (ID)
);

CREATE TABLE eZTodo_Todo (
  ID int NOT NULL,
  Category int,
  Priority int,
  Permission int default 0,
  UserID int,
  OwnerID int,
  Name varchar(30),
  Date int,
  Due int,
  Description text,
  Status int DEFAULT '0',
  IsPublic int NOT NULL DEFAULT '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTodo_Status (
  ID int NOT NULL,
  Name varchar(30),
  Description text,
  PRIMARY KEY (ID)
);

CREATE TABLE eZTodo_Log (
  ID int NOT NULL,
  Log text,
  Created int default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZTodo_TodoLogLink (
  ID int NOT NULL,
  TodoID int default NULL,
  LogID int default NULL,
  PRIMARY KEY (ID)
);

INSERT INTO eZTodo_Status (Description, ID, Name) VALUES (NULL,1,'Not done');
INSERT INTO eZTodo_Status (Description, ID, Name) VALUES (NULL,2,'Done');

INSERT INTO eZTodo_Priority (ID, Name) VALUES (1,'Low');
INSERT INTO eZTodo_Priority (ID, Name) VALUES (2,'Medium');
INSERT INTO eZTodo_Priority (ID, Name) VALUES (3,'High');
