-- Drop eZGroupEventCalendar Tables
drop table eZGroupEventCalendar_Event;
drop table eZGroupEventCalendar_EventCategory;
drop table eZGroupEventCalendar_EventType;
drop table eZGroupEventCalendar_GroupEditor;
drop table eZGroupEventCalendar_GroupNoShow;
drop table eZGroupEventCalendar_EventForumLink;
drop table eZGroupEventCalendar_EventFileLink;


-- Delete eZGroupEventCalendar Module and Permission Records

delete from eZUser_Module where name = 'eZGroupEventCalendar';
-- Example of the coresponding insert (In case you need to know which ID's you used)
-- INSERT INTO eZUser_Module VALUES ('24', 'eZGroupEventCalendar');


-- Incomplete

-- delete from eZUser_Permission WHERE EXISTS

-- delete from eZUser_Permission WHERE EXISTS (SELECT max(eZUser_Permission.ID) + 1, "Read", eZUser_Module.ID from eZUser_Module, eZUser_Permission where eZUser_Module.Name = "eZGroupEventCalendar" and eZUser_Module.ID = eZUser_Permission.ModuleID group by eZUser_Module.ID desc limit 1);

-- delete from eZUser_Permission WHERE EXISTS (SELECT eZUser_Module.*, eZUser_Permission.* from eZUser_Module, eZUser_Permission where eZUser_Module.Name = "eZGroupEventCalendar" and eZUser_Module.ID = eZUser_Permission.ModuleID group by eZUser_Module.ID desc limit 1);


-- (SELECT max(eZUser_Permission.ID) + 1, "Read", eZUser_Module.ID from eZUser_Module, eZUser_Permission where eZUser_Module.Name = "eZGroupEventCalendar" group by eZUser_Module.ID desc limit 1);

-- where ModuleID = '24';

-- Example of the coresponding insert (In case you need to know which ID's you used)
-- INSERT INTO eZUser_Permission VALUES ('98', '24', 'ModuleEdit');
-- INSERT INTO eZUser_Permission VALUES ('99', '24', 'ModuleAnswer');
