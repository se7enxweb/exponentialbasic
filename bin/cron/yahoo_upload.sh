#!/bin/sh
#####################################################
date;
echo;
echo "Yahoo FTP Upload : Connecting ... ";
#####################################################

#####################################################
# Testing Comments
# file="/home/full/public_html/importimages/attQ98AJz";


#####################################################
# the file
file="$1";


#####################################################
# connect
# send authentication
# send file
# quit

echo "Uploading CSV File ...";
echo;

# automated ftp requires login information passed 
# into ftp session in this way before (ftp limitation)

echo "
  user fullthrottle42 topcat1
  cd /10458753
  put $file data.txt
  bye
" | /usr/kerberos/bin/ftp -i -u -n ftp.productsubmit.adcentral.yahoo.com

echo;
date
echo;

#####################################################
# Fin ...
echo "Script Completed!";
echo;
echo;
