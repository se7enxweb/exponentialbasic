#!/bin/sh
echo "Clearing the cache..."
# Module directories to ignore (Unused)
exempt_dirs="
ezrfp
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

# Module directories to clear cache files
clear_base_dirs="
ezad
ezaddress
ezarticle
ezbug
ezcalendar
ezcontact
ezforum
ezgroupeventcalendar
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
root="$PWD";

for dir in $clear_base_dirs
do
  if [ -d $dir ]; then
      echo;
      echo "Clearing $dir ..."

#####################################################
# Reference Implimentation for replacement clear cache script
# Which supports large number of files, 45,000+
#
# for file in ezcalendar/user/cache/* ;
#  do
#   ls -tr $file ;
# done
#
# for file in $dir/cache/* ; do ls -tr $file; done
#####################################################

  # Clear base cache dir's *.cache and *.php cache files
  if [ -d $dir/cache ]; then 
     # rm -f $dir/cache/*.cache
     # rm -f $dir/cache/*.php
     for file in $dir/cache/* ;
       do
       if [ -f $file ]; then
          # ls -tr $file ;
	  rm -vf $file ;
       fi
     done
  fi
  # Clear admin cache files
  if [ -d $dir/admin/cache/ ]; then
     # rm -f $dir/admin/cache/*.cache
     for file in $dir/admin/cache/* ;
       do
       if [ -f $file ]; then
          # ls -tr $file ;
	  rm -vf $file ;
       fi
     done
  fi
  # Clear user cache files
  if [ -d $dir/user/cache/ ]; then
     # rm -f $dir/user/cache/*.cache
     for file in $dir/user/cache/* ;
       do
       if [ -f $file ]; then
          # ls -tr $file ;
          rm -vf $file ;
       fi
     done
  fi
  else
      # If directory in list does not exist, create it.
      echo "Creating missing directory: $dir"
      mkdir -p $dir
  fi
  # Set permission for directories
  chmod 777 $dir   
done
