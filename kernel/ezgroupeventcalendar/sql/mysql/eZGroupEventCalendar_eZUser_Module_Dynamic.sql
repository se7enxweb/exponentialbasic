-- eZUser Module Action Permission : Calendar Permissions Dependancies
-- ######################################################################################

-- eZUser Module : Calendar Permissions Dependancies
-- Informational output of eZUser_Module Records and NextID (Calculated)
-- select ID, Name, max(ID) +1 as NextID from eZUser_Module where ID group by ID;

-- Select (Only) the NextID of eZUser_Module Record and NextID (Calculated)
-- select max(ID) +1 as NextID from eZUser_Module where ID group by ID desc limit 1 ;

-- Insert Module SQL (Dynamic)
INSERT INTO eZUser_Module (Name, ID) (SELECT "eZGroupEventCalendar", max(ID) +1 as NextID from eZUser_Module where ID group by ID desc limit 1);

-- #1 : Select the nextid from eZUser_Module,
-- ensure that you use the next unused id that is greater than the the largest id in use.

-- sql select to help you find this informaiton.
-- select ID, Name from eZUser_Module;
-- select MAX(ID) from eZUser_Module;
-- INSERT INTO eZUser_Module VALUES ('42', 'eZGroupEventCalendar');

-- ######################################################################################
-- eZUser Module Action Permission : Calendar Permissions Dependancies

-- Informational output of all eZUser_Permission Records
 select * from eZUser_Permission where ID group by ID desc;

-- Informational output of the last eZUser_Permission Record
 select * from eZUser_Permission where ID group by ID desc limit 1;

-- Informational output of last eZUser_Permission Record and NextID (Calculated)
select ID, ModuleID, Name, max(ID) +1 as NextID from eZUser_Permission where ID group by ID desc limit 1;   

-- Informational output of last eZUser_Permission Record and NextID (Calculated)
select ID, ModuleID, Name, max(ID) +1 as NextID from eZUser_Permission where ID group by ID desc limit 1;

-- Informational output of eZUser_Permission Record NextID (Calculated)
select max(ID) +1 as NextID from eZUser_Permission where ID group by ID desc limit 1;


-- Insert Module Permissions SQL (Dynamic)
INSERT INTO eZUser_Permission (ID, Name, ModuleID) (SELECT max(eZUser_Permission.ID) + 1, "Read", eZUser_Module.ID from eZUser_Module, eZUser_Permission where eZUser_Module.Name = "eZGroupEventCalendar" group by eZUser_Module.ID desc limit 1);
INSERT INTO eZUser_Permission (ID, Name, ModuleID) (SELECT max(eZUser_Permission.ID) + 1, "WriteToRoot", eZUser_Module.ID from eZUser_Module, eZUser_Permission where eZUser_Module.Name = "eZGroupEventCalendar" group by eZUser_Module.ID desc limit 1);

-- #2 : Select the nextid from eZUser_Module,
-- ensure that you use the next unused id that is greater than the the largest id in use.
-- also make sure that the eZUser_Permission module ID matches the eZUser_Module ID

-- sql select to help you find this informaiton.
-- select ID, ModuleID, Name from eZUser_Permission;
-- select MAX(ID) from eZUser_Permission;

-- Defaults eZ publish Installations can use these values.
-- INSERT INTO eZUser_Permission VALUES ('55', '24', 'ModuleEdit');
-- INSERT INTO eZUser_Permission VALUES ('56', '24', 'ModuleAnswer');

-- Modified or Customized Exponential installations may need larger values instead:
-- INSERT INTO eZUser_Permission VALUES ('250', '42', 'Read');
-- INSERT INTO eZUser_Permission VALUES ('251', '42', 'WriteToRoot');

-- # Wont execute properly (Depricated | Reference)
-- #
-- #INSERT INTO eZUser_Permission (ModuleID, Name) SELECT ID AS ModuleID, 'Read' AS Name FROM eZUser_Module WHERE Name='eZGroupEventCalendar';
-- #INSERT INTO eZUser_Permission (ModuleID, Name) SELECT ID AS ModuleID, 'WriteToRoot' AS Name FROM eZUser_Module WHERE Name='eZGroupEventCalendar';
-- #
-- #

