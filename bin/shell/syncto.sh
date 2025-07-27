#!/bin/sh

# Name: ./bin/shell/syncto.sh
# Description: Backport production files to another location, wrapper call
#

################################################################
# switch : alpha, beta, dev, stage 

# from='production'
# dest='dev'
from='/home/web/production/public_html'
dest='/home/web/dev/doc'

# USAGE
# echo "./bin/shell/backportfiles.sh /home/web/production/public_html /home/web/dev/doc";
# echo;

# from="$1"
# dest="$2"

################################################################

./bin/shell/backportfiles.sh $from $dest

################################################################
#

