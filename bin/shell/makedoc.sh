#!/bin/sh
echo "Generating eZ publish 2 documentation"

./bin/shell/ezphpdoc.pl --disable_todo classes/ ezad/classes/ ezaddress/classes/ ezarticle/classes/ ezbug/classes/ ezbulkmail/classes/ ezcalendar/classes/ ezcontact/classes/ ezfilemanager/classes/ ezform/classes/ ezforum/classes/ ezimagecatalogue/classes/ ezlink/classes/
ezmail/classes/ ezmediacatalogue/classes/ ezmessage/classes/ ezmodule/classes/ eznewsfeed/classes/ ezpoll/classes/ ezquiz/classes/ ezsession/classes/ ezsitemanager/classes/ ezstats/classes/ ezsysinfo/classes/ eztodo/classes/ 
eztrade/classes/ ezurltranslator/classes/ ezuser/classes/ ezxml/classes/ ezxmlrpc/classes/
