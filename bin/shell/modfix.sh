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
ezmediacatalogue/catalogue
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
ezquiz/admin/cache
ezquiz/cache
ezmessage/admin/cache
ezform/admin/cache
ezsitemanager/staticfiles
ezsitemanager/staticfiles/images
ezmediacatalogue/admin/cache
ezmediacatalogue/cache
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
