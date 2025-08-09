#!/bin/sh
echo "Clearing the eZ publish cache . . ."

dirs="
kernel/ezad
kernel/ezaddress
kernel/ezarticle
kernel/ezbug
kernel/ezcalendar
kernel/ezgroupeventcalendar
kernel/ezcontact
kernel/ezforum
kernel/ezlink
kernel/eznewsfeed
kernel/ezpoll
kernel/ezstats
kernel/ezsurvey
kernel/eztip
kernel/eztodo
kernel/eztrade
kernel/ezuser
kernel/ezfilemanager
kernel/ezimagecatalogue
kernel/ezsitemanager
kernel/ezquiz
kernel/classes
kernel/ezurltranslator
kernel/ezbulkmail
kernel/ezform
kernel/ezmediacatalogue
kernel/ezsysinfo
var
"

for dir in $dirs
do
    if [ -d $dir ]; then
	    echo "Clearing $dir"
        rm -f $dir/cache/*.cache
	rm -f $dir/cache/*.php
	if [ -d $dir/admin/cache/ ]; then
	    rm -f $dir/admin/cache/*.cache
	fi
	if [ -d $dir/user/cache/ ]; then
	    rm -f $dir/user/cache/*.cache
	fi
    else
        echo "Creating $dir"
	    mkdir -p $dir
    fi
    chmod 777 $dir   
done
