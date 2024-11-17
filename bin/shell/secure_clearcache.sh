#!/bin/sh

echo "Clearing the cache."

dirs="
ezlink
ezforum
ezpoll
eztrade
ezarticle
ezcontact
eztodo
ezstats
eznewsfeed
ezad
ezuser
ezaddress
ezcalendar/user
ezform
ezquiz
"

for dir in $dirs
do
    if [ -d $dir ]; then
	    echo "Clearing $dir"
        rm -f $dir/cache/*.cache
	if [ -d $dir/admin/cache/ ]; then
	    rm -f $dir/admin/cache/*.cache
	fi
#    else
#        echo "Creating $dir"
#	    mkdir -p $dir
    fi
#    chmod 770 $dir   
done
