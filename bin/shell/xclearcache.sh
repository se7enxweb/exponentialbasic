#!/bin/sh
echo "Clearing the cache."

#ezrfp
rfp_clear_dirs="
ezcontact
ezaddress
ezarticle
ezstats
ezuser
ezfilemanager
ezsitemanager
classes
ezurltranslator
ezform
"


clear_base_dirs="
ezgroupeventcalendar
ezad
ezaddress
ezarticle
ezbug
ezcalendar
ezcontact
ezforum
ezlink
eznewsfeed
ezpoll
ezstats
eztodo
eztrade
ezuser
ezfilemanager
ezimagecatalogue
ezsitemanager
ezquiz
classes
ezurltranslator
ezbulkmail
ezform
ezmediacatalogue
ezsysinfo
ezvote
"

for dir in $clear_base_dirs
do
    if [ -d $dir ]; then
        echo "Clearing $dir"
        #rm -f $dir/cache/*.cache
        find . -name '$dir/cache/*.cache' -print0 | xargs -0 rm -vf
	#rm -f $dir/cache/*.php
        find . -name '$dir/cache/*.php' -print0 | xargs -0 rm -vf
	if [ -d $dir/admin/cache/ ]; then
	    #rm -f $dir/admin/cache/*.cache
            find . -name '$dir/admin/cache/*.cache' -print0 | xargs -0 rm -vf
	fi
	if [ -d $dir/user/cache/ ]; then
	    #rm -f $dir/user/cache/*.cache
            find . -name '$dir/user/cache/*.cache' -print0 | xargs -0 rm -vf
	fi
    else
        echo "Creating $dir"
	    # mkdir -p $dir
    fi
    chmod 777 $dir   
done
