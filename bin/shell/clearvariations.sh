#!/bin/sh
echo "Clearing the existing image variations in cache ... ";

dirs="
var/site/storage/ezimagecatalogue/variations
"

for dir in $dirs
do
    if [ -d $dir ]; then
        echo "Clearing Image Variations Directory : $dir" 
	# echo "$dir"
	# echo "rm -f $dir/*"
        rm -f $dir/*
	# ls -la $dir
	echo
    else
        echo "Creating $dir"
	    mkdir -p $dir
    fi
    chmod 777 $dir   
done

./bin/shell/clearcache.sh
