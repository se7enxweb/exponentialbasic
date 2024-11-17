@ECHO OFF
echo Clearing eZ publish cache directories!
SET DIRS=ezad ezaddress ezarticle ezbug ezcalendar ezcontact eztrade ezquiz ezpoll eztodo eznewsfeed ezmediacatalogue ezimagecatalogue ezsitemanager ezlink ezforum ezform ezfilemanager ezcontact ezcalendar ezbulkmail classes
echo "Clearing cache directories...."
FOR %%i IN (%DIRS%) DO deltree /Y %%i\cache
FOR %%i IN (%DIRS%) DO deltree /Y %%i\admin\cache
FOR %%i IN (%DIRS%) DO mkdir %%i\cache
FOR %%i IN (%DIRS%) DO mkdir %%i\admin\cache

SET DIRS=ezforum ezlink eznewsfeed ezpoll ezstats eztodo
FOR %%i IN (%DIRS%) DO deltree /Y %%i\cache
FOR %%i IN (%DIRS%) DO deltree /Y %%i\admin\cache
FOR %%i IN (%DIRS%) DO mkdir %%i\cache
FOR %%i IN (%DIRS%) DO mkdir %%i\admin\cache

SET DIRS=eztrade ezuser ezfilemanager ezimagecatalogue ezsitemanager ezquiz 
FOR %%i IN (%DIRS%) DO deltree /Y %%i\cache
FOR %%i IN (%DIRS%) DO deltree /Y %%i\admin\cache
FOR %%i IN (%DIRS%) DO mkdir %%i\cache
FOR %%i IN (%DIRS%) DO mkdir %%i\admin\cache

SET DIRS=classes ezurltranslator ezbulkmail ezform ezmediacatalogue
FOR %%i IN (%DIRS%) DO deltree /Y %%i\cache
FOR %%i IN (%DIRS%) DO deltree /Y %%i\admin\cache
FOR %%i IN (%DIRS%) DO mkdir %%i\cache
FOR %%i IN (%DIRS%) DO mkdir %%i\admin\cache

echo Finish!
exit
