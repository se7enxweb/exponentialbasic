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

# Ensure var/cache root and classes subdir exist and are writable
mkdir -p var/cache/classes
chmod -R 777 var/cache 2>/dev/null

# Recursively clear the new cache home
if [ -d var/cache ]; then
    find var/cache -type f -delete 2>/dev/null
fi

# Clear any legacy cache files still living under kernel/*
for dir in $dirs
do
    if [ -d $dir ]; then
	    echo "Clearing $dir"
        find $dir -path '*/cache/*' -type f ! -name '.keep' -delete 2>/dev/null
        find $dir -path '*/admin/cache/*' -type f ! -name '.keep' -delete 2>/dev/null
        find $dir -path '*/user/cache/*' -type f ! -name '.keep' -delete 2>/dev/null
    else
        echo "Creating $dir"
	    mkdir -p $dir
    fi
    chmod 777 $dir 2>/dev/null
done
