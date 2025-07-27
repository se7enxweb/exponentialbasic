#!/bin/sh
echo "Creating symbolic links and setting permissions as needed."

# Set permissions for site.ini files
chmod 666 bin/ini/site.ini
if [ -f "bin/ini/override/site.ini" ]; then
    chmod 666 bin/ini/override/site.ini
fi
if [ -f "bin/ini/override/site.ini.php" ]; then
    chmod 666 bin/ini/override/site.ini.php
fi

if [ -f "bin/ini/override/site.ini.append" ]; then
    chmod 666 bin/ini/override/site.ini.append
fi

# Set permissions for log dir & files
if [ -d "bin/logs/" ]; then
    chmod -R 777 bin/logs/
fi


# [cache section]
# This part will create the cache dirs which are needed and make sure
# that they are writeable by php.

dirs="
design/admin/tmp
kernel/ezad/admin/cache
kernel/ezaddress/admin/cache
kernel/ezarticle/admin/cache
kernel/ezarticle/cache
kernel/ezbug/user/cache
kernel/ezbug/admin/cache
kernel/ezcalendar/admin/cache
kernel/ezcalendar/user/cache
kernel/ezcontact/admin/cache
kernel/ezexample/admin/cache
kernel/ezfilemanager/files
kernel/ezforum/admin/cache
kernel/ezforum/cache
kernel/ezimagecatalogue/catalogue
kernel/ezimagecatalogue/catalogue/variations
kernel/ezlink/admin/cache
kernel/ezlink/cache
kernel/ezmediacatalogue/catalogue
kernel/eznewsfeed/admin/cache
kernel/eznewsfeed/cache
kernel/ezpoll/admin/cache
kernel/ezpoll/cache
kernel/ezstats/admin/cache
kernel/eztodo/admin/cache
kernel/eztrade/admin/cache
kernel/eztrade/cache
kernel/ezuser/admin/cache
kernel/ezfilemanager/admin/cache
kernel/ezimagecatalogue/admin/cache
kernel/ezbulkmail/admin/cache
kernel/classes/cache
kernel/ezsysinfo/admin/cache
kernel/ezurltranslator/admin/cache
kernel/ezsitemanager/admin/cache
kernel/ezquiz/admin/cache
kernel/ezquiz/cache
kernel/ezmessage/admin/cache
kernel/ezform/admin/cache
kernel/ezsitemanager/staticfiles
kernel/ezsitemanager/staticfiles/images
kernel/ezmediacatalogue/admin/cache
kernel/ezmediacatalogue/cache
"


for dir in $dirs
do
    if [ -d $dir ]; then
	echo "$dir already exist"
    else
        echo "Creating $dir"
	    mkdir -p $dir
    fi
    chmod 777 $dir   
done

for dir in $dirs
do
    override_dir="override/"$dir
    if [ -d $override_dir ]; then
	chmod 777 $override_dir
    fi
done



# [admin section]
# This part will link the modules into the admin directory
#
# Obsolete as of version 2.0.1

#  files="
#  error.log
#  ezlink
#  site.ini
#  ezforum
#  ezarticle
#  ezad
#  classes
#  ezclassified
#  ezimagecatalogue
#  ezfilemanager
#  ezpoll
#  ezuser
#  ezsession
#  ezcontact
#  ezstats
#  eztodo
#  eznewsfeed
#  eztrade
#  ezaddress
#  ezbug
#  ezexample
#  ezcalendar
#  ezerror
#  checkout
#  "

#  for file in $files
#  do
#      if [ -e $file ]; then
#  	if [ -e admin/$file ]; then
#  	    echo "admin/$file already exist"
#  	else
#  	    echo "Linking ./$file to admin/$file"
#  	    ln -s ../$file admin/$file
#  	fi
#      fi
#  done

#  if [ -d "override" ]; then
#      if [ ! -d "admin/override" ]; then
#  	echo "Linking override to admin/override"
#  	ln -sf ../override admin/override
#      fi
#  fi
