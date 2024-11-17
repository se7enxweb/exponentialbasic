#!/bin/sh

if [ "$1" = "" ] || [ "$2" = "" ] || [ `whoami` !=  "root" ] 
then
echo "Must be run as root"
echo "Usage: secure_modfix web_admin_user apache_user_group"
exit 0
fi

echo "Creating symbolic links and setting permissions as needed."
chown $1:$2 bin/ini/site.ini
chmod 640 bin/ini/site.ini
if [ -f "bin/ini/override/site.ini" ]; then
    chown $1:$2 bin/ini/override/site.ini
    chmod 640 bin/ini/override/site.ini
fi
if [ -f "bin/ini/override/site.ini.append" ]; then
    chown $1:$2 bin/ini/override/site.ini.append
    chmod 640 bin/ini/override/site.ini.append
fi

touch bin/logs/error.log
chmod 660 bin/logs/error.log
chown $1:$2 bin/logs/error.log

# [cache section]
# This part will create the cache dirs which are needed and make sure
# that they are writeable by php.

dirs="
design/admin/tmp
ezad/admin/cache
ezaddress/admin/cache
ezarticle/admin/cache
ezarticle/cache
ezbug/user/cache
ezbug/admin/cache
ezcalendar/admin/cache
ezcalendar/user/cache
ezcontact/admin/cache
ezexample/admin/cache
ezfilemanager/files
ezforum/admin/cache
ezforum/cache
ezimagecatalogue/catalogue
ezimagecatalogue/catalogue/variations
ezlink/admin/cache
ezlink/cache
eznewsfeed/admin/cache
eznewsfeed/cache
ezpoll/admin/cache
ezpoll/cache
ezstats/admin/cache
eztodo/admin/cache
eztrade/admin/cache
eztrade/cache
ezuser/admin/cache
ezfilemanager/admin/cache
ezimagecatalogue/admin/cache
ezbulkmail/admin/cache
classes/cache
ezsysinfo/admin/cache
ezurltranslator/admin/cache
ezsitemanager/admin/cache
ezform/admin/cache
ezquiz/admin/cache
ezquiz/cache"

for dir in $dirs
do
    if [ -d $dir ]; then
	    echo "$dir already exist"
    else
        echo "Creating $dir"
	    mkdir -p $dir
    fi
    chown $1:$2 $dir
    chmod 770 $dir   
done

for dir in $dirs
do
    override_dir="override/"$dir
    if [ -d $override_dir ]; then
	chmod 770 $override_dir
    fi
done


