#!/bin/sh
echo "Clearing the existing image variations in cache ... ";

dirs="
var/site/storage/ezimagecatalogue/variations
"

#####################################################

for dir in $dirs
do
    if [ -d $dir ]; then
	 echo "Clearing Image Variations Directory : $dir"

#####################################################
#        for file in ezcalendar/user/cache/* ;
#         do
#          ls -tr $file ;
#         done
#
#        for file in $dir/cache/* ; do ls -tr $file; done
#####################################################

         for file in $dir/* ;
           do
#           if [ -f $file ]; then
#              ls -tr $file 
#              ls -tr $file | rm -vf ;
               rm -vf $file ;
#           fi
         done

    else
        echo "Creating $dir"
            # mkdir -p $dir
    fi
      chmod 777 $dir
done


# Simple
################################################
#
# for dir in $dirs
# do
#     if [ -d $dir ]; then
#         echo "Clearing Image Variations Directory : $dir" 
# 	# echo "$dir"
# 	# echo "rm -f $dir/*"
#         rm -f $dir/*
# 	# ls -la $dir
# 	echo
#     else
#         echo "Creating $dir"
# 	    mkdir -p $dir
#     fi
#     chmod 777 $dir   
# done
#


################################################
# Clear eZ publish Class / Action Cache Files 

./bin/shell/clearcache.sh
