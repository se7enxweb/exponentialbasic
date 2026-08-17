#!/bin/sh
echo "Creating symbolic links and setting permissions as needed."

# Set permissions for site.ini files
chmod 666 settings/site.ini
if [ -f "settings/override/site.ini" ]; then
    chmod 666 settings/override/site.ini
fi
if [ -f "settings/override/site.ini.php" ]; then
    chmod 666 settings/override/site.ini.php
fi

if [ -f "settings/override/site.ini.append" ]; then
    chmod 666 settings/override/site.ini.append
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
var/cache
var/cache/classes
var/site/storage/ezfilemanager
var/site/storage/ezimagecatalogue
var/site/storage/ezimagecatalogue/variations
var/site/storage/ezmediacatalogue
kernel/ezsitemanager/staticfiles
kernel/ezsitemanager/staticfiles/images
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
