#!/bin/sh
######################################################
echo "Building a copy of the installation's datasuppliers . . . "

admin_dirs=`find . -name *datasupplier.php | grep admin`
user_dirs=`find . -name *datasupplier.php | grep user`

admin_views='bin/logs/module_views_admin.txt';
user_views='bin/logs/module_views_user.txt';

debug=false;

# rm -rf $admin_views $user_views
######################################################

for dir in $admin_dirs
do
    if [ -f $dir ]; then
        echo "Building from source file $dir"
        # ls -la $dir
        # pwd
        # ls -la $admin_views
        echo "$dir" >> $admin_views
        cat $dir >> $admin_views
        # rm -f $dir/cache/*.cache
        # rm -f $dir/cache/*.php
    fi
done
echo "Resulting source file: $admin_views"

######################################################

for dir in $user_dirs
do
    if [ -f $dir ]; then
        echo "Building from source file $dir"
        echo "$dir" >> $user_views
        cat $dir >> $user_views
    fi
done
echo "Resulting source file: $user_views"

######################################################
