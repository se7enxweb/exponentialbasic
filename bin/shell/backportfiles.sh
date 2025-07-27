#!/bin/sh

# Name: ./bin/shell/backportfiles.sh
# Original Name: ./bin/shell/rsync_down_production_images.sh
# Description: Backport production files to another codeset
#

################################################################
# switch : alpha, beta, dev, stage 

# from='production'
# dest='dev'
# from='/home/web/production/public_html'
# dest='/home/web/dev/doc'

# USAGE
# echo "./bin/shell/backportfiles.sh /home/web/production/public_html /home/web/dev/doc";
# echo;

from="$1"
dest="$2"

################################################################

echo "Rsync'ing ..."
echo "Files: ezfilemanager/files, ezimagecatalogue/catalogue/, ezmediacatalogue/catalogue/ "
echo;
echo "From: $from" 
echo "To: $dest"

dirs="
ezimagecatalogue/catalogue
ezfilemanager/files
ezmediacatalogue/catalogue
"

echo

################################################################

for dir in $dirs
do
    full_dir_from="$from/$dir";
    full_dir_dest="$dest/$dir";
    cpwd=`pwd`
    
    sfull_dir_from=( $full_dir_from );
    sfull_dir_dest=( $full_dir_dest );
   
    # count=`echo $sfull_dir_dest | wc -m`
    # echo "URL Char Count: $count";
    # echo;

    # rfull_dir_dest=`echo $sfull_dir_dest| rev`    
    # echo "$rfull_dir_dest";

    # Base Dir
    # echo $sfull_dir_dest | cut -c 1-18

    # /home/web/dev/doc/ezimagecatalogue/
    # echo $sfull_dir_dest | cut -c 1-35
    
    # full_dir_dest_trimmed=`echo $sfull_dir_dest | cut -c 1-35`
    full_dir_dest_trimmed=`echo $sfull_dir_dest | cut -c 1-35`
    # echo "$full_dir_dest_trimmed";

################################################################
    
if [ $full_dir_dest_trimmed == "/home/web/dev/doc/ezimagecatalogue/" ];
then
  # positive, do this move
  rsyncDir=1;
  full_dir_dest_trimmed=$full_dir_dest_trimmed
else
   cfull_dir_dest_trimmed=`echo $sfull_dir_dest | cut -c 1-32`
   # echo "! $cfull_dir_dest_trimmed";

   # other tests ...
   if [ $cfull_dir_dest_trimmed == "/home/web/dev/doc/ezfilemanager/" ]; 
   then
     # positive, do this move
     rsyncDir=2;
     full_dir_dest_trimmed=$cfull_dir_dest_trimmed
   else
     dfull_dir_dest_trimmed=`echo $sfull_dir_dest | cut -c 1-35`
     # echo "$dfull_dir_dest_trimmed";

     # still, other tests ...
     if [ $dfull_dir_dest_trimmed == "/home/web/dev/doc/ezmediacatalogue/" ];
     then
       # positive, do this move
       rsyncDir=3;
       full_dir_dest_trimmed=$dfull_dir_dest_trimmed
     else
       # other tests ...
       rsyncDir=4;
     fi
  fi
fi  

################################################################
#   exit -1;

    if [ -d $dir ]; then
        echo "Syncing Directory ... "
	# echo "$full_dir_from $full_dir_dest"
        # echo "$dir"
        # echo "rm -f $dir/*"
        # rm -f $dir/*

	# echo $full_dir_dest
	cd $full_dir_dest
        
	# echo "Command:  rsync -v -a -n $full_dir_from $full_dir_dest";
	# echo "Command:  rsync -v -a -n $full_dir_from $full_dir_dest_trimmed";
	# echo "rsync -v -a --exclude=\"/home/web/production/public_html/ezimagecatalogue/catalogue/variations/*\" $full_dir_from $full_dir_dest_trimmed"
	  echo "rsync -v -a --exclude=variations/ $full_dir_from $full_dir_dest_trimmed"
	echo

# exit -1;

	# rsync -v -a $full_dir_from $full_dir_dest
	# rsync -v -a -n $full_dir_from $full_dir_dest_trimmed
	# 
	if [ $rsyncDir == "1" ]; then
	  rsync -v -a --exclude=variations/ $full_dir_from $full_dir_dest_trimmed
        else
	  rsync -v -a $full_dir_from $full_dir_dest_trimmed
        fi

	cd $cpwd;
# 	pwd

        # ls -la $dir
        echo
    else
        echo "Creating $dir"
            # mkdir -p $dir
    fi

    # exit -1 ;    

    chmod 777 $dir
done

################################################################

# Clear Image Variations / Cache
 ./bin/shell/clearvariations2.sh
