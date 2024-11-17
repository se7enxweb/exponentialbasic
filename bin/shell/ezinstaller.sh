#!/bin/sh

#
# ezinstaller - version 1.10 - (c) 2001 Kai Dübbert <kai@duebbert.de>, eZ Systems
# ===============================================================================
#
# This shell script will install eZ publish 2 (http://ezcommunity.net/) on a Linux 
# (or Unix like) system.
#
#---------------------------------------------------------------------------
# Licence: GPL
#
# This program is free software; you can redistribute it and/or
# modify it under the terms of the GNU General Public License
# as published by the Free Software Foundation; either version 2
# of the License, or (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, US
#---------------------------------------------------------------------------
#
# Changelog:
#
# 1.10: - vl : Fixed bug when www-dir and inst-dir is the same dir
# 1.9: - jhe : changed publish.ez.no to developer.ez.no 
# 1.8: - changed to tell about new possible install (with .htaccess)
# 1.7: - more cache directories
# 1.6: - changed to allow installation of php files in same directory for
#        people who can't install it properly
# 1.5: - partial rewrite
#      - fixed for 2.2 alpha
# 1.4: - fixed to have it work with older Bash
# 1.4.5 - Updated script to reflect change from ez.no to ezcommunity.net & new design of eZ publish 2.3.0

############################################################################
# Default values
# 
DEF_INSTDIR=/usr/local/ezpublish
DEF_WWWDIR=/var/www
DEF_OWNER=www-data
DEF_GROUP=www-data
DEF_DBNAME=publish
DEF_DBUSER=publish
DEF_TITLE="eZ publish 2"
DEF_DBSERVER=localhost
DEF_URLDIR=/publish
DEF_INSTALL=1



############################################################################
############################################################################
#Let's go
#
DATE="09-25-2004"
VERSION="1.4.5 [$DATE]"

#echo "ezinstaller.sh - version $VERSION - (c) 2001 Kai Dübbert, eZ Systems"
echo "ezinstaller.sh - version $VERSION "
echo ""
echo "This tool will help you install eZ publish 2 on your server."
echo ""
echo "Licence: GPL"
echo ""
echo "##################################################################"


############################################################################
# Classic install or new install?
#
function install_q {
	echo ""
	echo "#################################################################"
	echo "                I N S T A L L   M E C H A N I S M"
	echo ""
	echo "       [1] with virtual_hosts and mod_rewrite (needs root priviledges)"
	echo "       [2] without virtual_hosts and mod_rewrite"
	echo "       [h] Help"
	echo ""
	echo -n "Choose:  "

	read Q
	if [ "$Q" = "2" ]; then
		C_INSTALL=new
	elif [ "$Q" = "1" ]; then
		C_INSTALL=old
		if [ `whoami` !=  "root" ]; then
			echo "To install eZ publish with virtual_hosts/mod_rewrite you"
			echo "must be root."
			echo "Choose [2] if you don't have or want to install as root."
			exit 0
		fi
	elif [ "$Q" = "h" ] || [ "$Q" = "H" ]; then
		echo ""
		echo "[1]: lets you install eZ publish like it is explained in"
		echo "     section 4.1 in the installation manual."
		echo "     You need mod_rewrite for Apache and have to setup"
		echo "     2 virtual hosts in your Apache configuration."
		echo "     It also has to chown/chmod the files appropriately which"
		echo "     is the reason, why you need root priviledges (you also"
		echo "     need them for changing your Apache configuration afterwards.)"
		echo "     Advantages compared to [2]:"
		echo "        - better tested"
		echo "        - better for upgrading existing eZ publish sites"
		echo "     Disadvantages (see advantages of [2])"
		echo ""
		echo "[2]: lets you install eZ publish much like other PHP programs."
		echo "     It doesn't need any special configurations in the Apache"
		echo "     configuration, which makes it first choice, if you don't have"
		echo "     root access."
		echo "     This installation is explained in section 4.2 in the"
		echo "     installation manual."
		echo "     Advantages compared to [1]:"
		echo "        - you can keep your old HTML documents on the server"
		echo "        - can be installed in subdirectories of your webserver"
		echo "        - doesn't need root priviledges"
		echo "        - doesn't need mod_rewrite/virtual_hosts"
		echo "     Disadvantages:"
		echo "        - not tested as well as [1] (yet)"
		echo ""
		install_q
	else
		echo "Please choose \"1\", \"2\" or \"h\"."
		install_q
	fi
}
install_q


############################################################################
# configuration functions
#

echo ""
echo "#################################################################"
echo "                   C O N F I G U R A T I O N"
echo ""

# Let's first check for directories and permissions, so the user
# doesn't have to do all again

#
# Web directory
#
echo -n "Apache web directory [$DEF_WWWDIR]: "
read C_WWWDIR
if [ "$C_WWWDIR" = "" ]; then
	C_WWWDIR=$DEF_WWWDIR
fi

if [ "$C_INSTALL" = "new" ]; then
	echo -n "Web directory for eZ publish as subdirectory of $C_WWWDIR [$DEF_URLDIR]: "
	read C_URLDIR
	if [ "$C_URLDIR" = "" ]; then
		C_URLDIR=$DEF_URLDIR
	fi
	
	# add a slash if needed!
	if ! echo $C_URLDIR | grep /$ > /dev/null; then
		C_URLDIR=$C_URLDIR/
		#echo "slash added"
	fi
	
	if ! echo $C_URLDIR | grep ^/ > /dev/null; then
		C_URLDIR=/$C_URLDIR
		#echo "slash added"
	fi
	C_WWWDIR="$C_WWWDIR$C_URLDIR"
	if [ ! -d "$C_WWWDIR" ]; then
		echo -n "$C_WWWDIR does not exist. Creating it... "
		mkdir -p $C_WWWDIR
		if [ ! $? = 0 ]; then
			echo "FAILED. Check error message. Aborting."
			exit 1
		else
			echo "done."
		fi
	else
		touch $C_WWWDIR/test_delete_me
		if [ ! $? = 0 ]; then
			echo "Don't seem to have the right permissions for $C_WWWDIR!"
			echo "Aborting."
			exit 1
		else
			rm $C_WWWDIR/test_delete_me
		fi
	fi
fi

#
# New install: Installdir
#
function conf_inst_dir {
	if [ "$C_INSTALL" = "new" ]; then
		echo "-----------------------------------------------------------------"
		echo "Now I need a directory where you want me to put the eZ publish"
		echo "files. If possible, this should *NOT* be the same as your public"
		echo "WWW directory! This is important for your security."
		echo "If you have a web hoster, who doesn't allow this, use the same"
		echo "directory, but you *MUST* read the documentation about this"
		echo "install to know how to make it secure!"
		echo "in the directories with php-files to prevent direct web access."
		echo -n "Directory for program files [$DEF_INSTDIR]: "
		read C_INSTDIR
		
		# Check if the user read the warning.
		if [ "$C_WWWDIR" = "$C_INSTDIR" ] || [ "$C_WWWDIR" = "$C_INSTDIR/" ]; then
			echo ""
			echo "Using the same directory."
			echo "IMPORTANT: Read the documentation to see how to make this setup secure!!"
			echo ""
		fi
		
		if [ "$C_INSTDIR" = "" ]; then
			C_INSTDIR=$DEF_INSTDIR
		fi
		if [ ! -d "$C_INSTDIR" ]; then
			echo -n "$C_INSTDIR does not exist. Creating it... "
			mkdir -p $C_INSTDIR
			if [ ! $? = 0 ]; then
				echo "FAILED. Check error message. Aborting."
				exit 1
			else
				echo "done."
			fi
		fi
		# add a slash if needed!
		if ! echo $C_INSTDIR | grep /$ > /dev/null; then
			C_INSTDIR=$C_INSTDIR/
			#echo "slash added"
		fi
	else
		C_INSTDIR=.
	fi
}
conf_inst_dir

# Hostname
DEF_HOSTNAME="$(hostname -f)"
echo -n "Hostname of your server [$DEF_HOSTNAME]: "
read C_HOSTNAME
if [ "$C_HOSTNAME" = "" ]; then
	C_HOSTNAME=$DEF_HOSTNAME
fi

# IP-Address
if [ "$C_INSTALL" = "old" ]; then
	echo -n "IP-Address of your server: "
	read C_IPADDRESS
fi

# Title
echo -n "Title for your site [$DEF_TITLE]: "
read C_TITLE
if [ "$C_TITLE" = "" ]; then
	C_TITLE=$DEF_TITLE
fi

# TODO
echo ""
echo "WARNING: ezinstaller can only install a mySQL database at the moment!"
echo ""

# DB server
echo -n "Database server [$DEF_DBSERVER]: "
read C_DBSERVER
if [ "$C_DBSERVER" = "" ]; then
	C_DBSERVER=$DEF_DBSERVER
fi

# DB name
function db_name {
	echo -n "Database name [$DEF_DBNAME]: "
	read C_DBNAME
	if [ "$C_DBNAME" = "" ]; then
		C_DBNAME=$DEF_DBNAME
	fi
}
db_name

# DB user
echo -n "Database user [$DEF_DBUSER]: "
read C_DBUSER
if [ "$C_DBUSER" = "" ]; then
	C_DBUSER=$DEF_DBUSER
fi

# DB pass
function conf_db_pass {
	echo -n "Password for user \"$C_DBUSER\": "
	read -s C_DBPASS
	echo -en "\nRetype password: "
	read -s C_DBPASS2
	if [ ! "$C_DBPASS" = "$C_DBPASS2" ]; then
		echo -en "\n"
		conf_db_pass
	fi
	echo ""
}
conf_db_pass

# For old install
if [ "$C_INSTALL" = "old" ]; then
	# Owner of Webserver
	echo -n "Webserver Owner (UID) [$DEF_OWNER]: "
	read C_OWNER
	if [ "$C_OWNER" = "" ]; then
		C_OWNER=$DEF_OWNER
	fi
fi

# Group of Webserver
echo -n "Webserver Group (GID) [$DEF_GROUP]: "
read C_GROUP
if [ "$C_GROUP" = "" ]; then
	C_GROUP=$DEF_GROUP
fi

# Configuration finished


############################################################################
# Creating database
#
echo ""
echo "#################################################################"
echo "                   D A T A B A S E   S E T U P"
echo ""
echo "If \"$C_DBNAME\" is already a working database for eZ publish which"
echo "you would like to use, choose no here."
echo "If you do a clean or first install, say yes to these options."
echo ""
echo -n "Shall I setup the database for you (Y/n): "
read Q
if [ "$Q" = "y" ] || [ "$Q" = "Y" ] || [ "$Q" = "" ]; then
	Q_DB_CREATE=1
else
	Q_DB_CREATE=0
fi

function db_root_name {
	if [ $Q_DB_CREATE = 1 ]; then
		echo ""
		echo "Please tell me a user that is allowed to create"
		echo -n "the database (e.g. root): "
		read C_DBUSER2
		if [ "$C_DBUSER2" = "" ]; then
			echo "ARGH.... you were supposed to give me a name! I will use \"root\""
			echo "for now."
			C_DBUSER2=root
		fi
	fi
}
db_root_name

function db_root_pass {
	if [ $Q_DB_CREATE = 1 ]; then
		# Password
		echo -n "Password for db user \"$C_DBUSER2\": "
		read -s C_DBPASS2
		echo -en "\nRetype password: "
		read -s C_DBPASS_CHECK
		if [ ! $C_DBPASS2 = $C_DBPASS_CHECK ]; then
			echo "Passwords were not the same."
			echo -en "\n"
			db_root_pass
		fi

		# Warning... feel like a teacher. ;)
		if [ "$C_DBPASS2" = "" ]; then
			echo "Uh-oh, having a powerful user like this and not having a password"
			echo "is *VERY* bad in respect to security. You should seriously think"
			echo "about setting a password for root. But I will continue for now..."
			DBOPTIONS="-u$C_DBUSER2"
		else
			DBOPTIONS="-u$C_DBUSER2 -p$C_DBPASS2"
		fi
	fi
}
db_root_pass

# database creation
function db_create {
	if [ $Q_DB_CREATE = 1 ]; then
		echo -n "Creating the database \"$C_DBNAME\"... "
		mysqladmin $DBOPTIONS create $C_DBNAME 2> .ezinst.$$
		if [ ! $? = 0 ]; then
			if grep "Can't connect" .ezinst.$$ > /dev/null; then
				echo "ERROR: Can't connect to database!"
				echo "Please start database server and hit return to try creating the"
				echo "database again."
				echo "Hit return..."
				read LA
				db_create
			elif grep "Database exists" .ezinst.$$ > /dev/null; then
				echo "ERROR: database exists!"
				echo "You have 3 options: "
				echo ""
				echo "   [1] Drop old database and create new one"
				echo "   [2] Change database name and create that"
				echo "   [3] Continue without creating or changing the database"
				echo ""
				echo -n "Choose: "
				read Q
				if [ "$Q" = "1" ]; then
					echo -n "Dropping database... "
					echo "y" | mysqladmin $DBOPTIONS drop $C_DBNAME > /dev/null
					if [ $? = 0 ]; then
						echo "done."
					else
						echo "FAILED. Sorry, please try to fix this."
						exit 1
					fi
					db_create
				elif [ "$Q" = "2" ]; then
					db_name
					db_create
				elif [ "$Q" = "3" ]; then
					echo "OK, leave the existing database alone."
					Q_DB_CREATE=0
				fi
			elif grep "Access denied" .ezinst.$$ > /dev/null; then
				echo "ERROR: Access denied."
				echo "You have 2 options:"
				echo ""
				echo "  [1] Change name and password of user who is allowed to create the databases"
				echo "  [2] Continue without touching the database"
				echo ""
				echo -n "Choose: "
				read Q
				if [ "$Q" = "2" ]; then
					Q_DB_CREATE=0
				else
					db_root_name
					db_root_pass
					db_create
				fi
			else
				echo "ERROR: Creating the database failed and I can't recover from that error! Aborting."
				exit 1
			fi
			if [ -f ".ezinst.$$" ]; then
				rm -f .ezinst.$$
			fi
		else
			echo "done."
		fi
	fi
}
db_create

if [ $Q_DB_CREATE = 1 ]; then
	# Creating the user
	echo -n "Creating the user \"$C_DBUSER\" to use the database \"$C_DBNAME\"... "
	echo "grant all on ${C_DBNAME}.* to ${C_DBUSER}@localhost identified by \"$C_DBPASS\"" | mysql $DBOPTIONS
	if [ $? = 0 ];then
		echo "done."
	else
		echo "Sorry, but this failed... I have to stop. Please try to fix the error."
		exit 1
	fi
fi

if [ $Q_DB_CREATE = 1 ]; then
	# db structure creation
	echo -n "Creating data structures... "
	mysql $DBOPTIONS $C_DBNAME < bin/sql/publish_mysql.sql
	if [ ! $? = 0 ]; then
		echo "Creating the data structures failed! Abort."
		exit 1
	else
		echo "done."
	fi
fi

if [ $Q_DB_CREATE = 1 ]; then
	# example data
	echo -n "Shall I fill the database with an example site (y/N)? "
	read Q
	if [ "$Q" = "y" ] || [ "$Q" = "Y" ]; then
		echo -n "Filling the database... "
		mysql $DBOPTIONS $C_DBNAME < bin/sql/data_mysql.sql
		if [ ! $? = 0 ]; then
			echo "Filling the database with example data failed! Aborting."
			exit 1
		else
			echo "done."
		fi
	fi
fi

############################################################################
# Extracting the examples
#
echo -n "Extracting the data for the example site... "
tar xzfp bin/data/data.tar.gz
if [ ! $? = 0 ]; then
	echo "Failed to extract the example data (bin/data/data.tar.gz)! Aborting."
	exit 1
else
	echo "done."
fi

############################################################################
# Old install: using modfix_secure
#
if [ "$C_INSTALL" = "old" ]; then
	echo -n "Executing secure_modfix.sh... "
	./bin/shell/secure_modfix.sh $C_OWNER $C_GROUP
	if [ -d ezimagecatalogue/catalogue ]; then
		chown -R $C_OWNER ezimagecatalogue/catalogue
		chgrp -R $C_GROUP ezimagecatalogue/catalogue
	fi
	if [ $? = 0 ]; then
		echo "done."
	else
		echo "FAILED! You might have problems with permissions."
		echo "You have to do it yourself."
	fi
else
	# TODO: let's try to find a better solution
	echo "Creating the needed cache directories and files... "
	touch bin/logs/error.log
	chmod 660 bin/logs/error.log

	dirs="
	admin/tmp
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

	for dir in $dirs; do
		if [ ! -d $dir ]; then
			mkdir -p $dir
		fi
		chmod 770 $dir
		chgrp $C_GROUP $dir
	done
	
fi

############################################################################
# Fix the owners and permissions. We don't have to be too picky with the new install.
#
if [ "$C_INSTALL" = "new" ]; then
	chmod 640 bin/ini/site.ini
fi


############################################################################
# cleaning and securing the cache
#
echo -n "Executing secure_clearcache.sh... "
./bin/shell/secure_clearcache.sh
if [ $? = 0 ]; then
	echo "done."
else
	echo "FAILED! You might have problems with caching. Check it yourself."
fi

############################################################################
# Moving files to wwwdir
#
if [ "$C_INSTALL" = "new" ] && [ $C_WWWDIR != $C_INSTDIR ]; then
	echo ""
	echo "#################################################################"
	echo "Moving the publicly needed files to $C_WWWDIR... "

	echo -n "Moving index*.php to $C_WWWDIR... "
	mv  index*.php $C_WWWDIR
	echo "done."

	echo -n "Moving the ez* image files to $C_WWWDIR... "
	for i in ez*; do
		MODULE=`basename $i`
		for j in admin user; do
			if [ -d "$i/$j/images" ]; then
				if [ ! -d "$C_WWWDIR/$MODULE/$j" ]; then
					mkdir -p "$C_WWWDIR/$MODULE/$j"
				fi
				mv "$i/$j/images" "$C_WWWDIR/$MODULE/$j"
			fi
		done
		if [ -d "$i/images" ]; then
			if [ ! -d "$C_WWWDIR/$MODULE" ]; then
				mkdir "$C_WWWDIR/$MODULE"
			fi
		fi
		if [ -d "$i/images" ]; then
			mv "$i/images" "$C_WWWDIR/$MODULE"
		fi
	done
	echo "done."

	if [ ! -d "$C_WWWDIR/design/admin" ]; then
		mkdir "$C_WWWDIR/design/admin"
	fi

	echo -n "Moving design/admin/images to $C_WWWDIR/design/admin... "
	mv "design/admin/images" "$C_WWWDIR/design/admin"
	echo "done."

	echo -n "Moving images to $C_WWWDIR... "
	mv "images" "$C_WWWDIR"
	echo "done."

	echo -n "Moving design/admin/templates/*/* to $C_WWWDIR... "
	if [ ! -d "design/admin/templates" ]; then
		mkdir -p design/admin/templates
	fi
	for i in design/admin/templates/*; do
		if [ ! "$(basename $i)" = "CVS" ]; then
			mkdir -p design/admin/templates/$(basename $i)
			if [ -e $i/*.css ]; then
				# TODO: Resolve this problem!
				mv $i/*.css design/admin/templates/$(basename $i) | grep -v "are the same file"
			fi
		fi
	done
	echo "done."

	echo -n "Moving design/*/*.css to design/*... "
	for i in design/*; do
		SDNAME=`basename $i`
		if [ ! "$SDNAME" = "CVS" ]; then
			mkdir -p "$C_WWWDIR/design/$SDNAME"
			if [ -e $i/images ]; then
				mv "$i/images" "$C_WWWDIR/design/$SDNAME"
			fi
			if [ -e $i/*.css ]; then
				mv $i/*.css "$C_WWWDIR/design/$SDNAME"
			fi
		fi
	done
	echo "done."

	echo -n "Moving sitedir.ini to $C_WWWDIR... "
	mv "bin/ini/sitedir.ini" "$C_WWWDIR/bin/ini/"
	echo "done."

	#
	# change sitedir.ini
	#
	echo -n "Adjusting $C_WWWDIR/bin/ini/sitedir.ini... "
	sed s:"siteDir = \"\"":"siteDir = \"$C_INSTDIR\"": $C_WWWDIR/bin/ini/sitedir.ini > $C_WWWDIR/bin/ini/sitedir.ini.tmp && mv $C_WWWDIR/bin/ini/sitedir.ini.tmp $C_WWWDIR/bin/ini/sitedir.ini
	echo "done."
fi

############################################################################
# Move the files
#
if [ "$C_INSTALL" = "new" ] && [ $C_WWWDIR != $C_INSTDIR ]; then
	echo ""
	echo "#################################################################"
	echo "Moving the files:"
	echo "Now I will move the files to the installation dir."
	echo -n "Moving files... "
	mv * $C_INSTDIR
	#find . -exec mv {} $C_INSTDIR \;
	if [ ! $? = 0 ]; then
		echo "Moving of files failed! Aborting."
		exit 1
	else
		echo "done."
	fi
	mv .cvsignore $C_INSTDIR

	# 
	# Links for ezimagecatalogue!
	#
	if [ -d "$C_INSTDIR/ezimagecatalogue/catalogue" ]; then
		echo -n "Linking $C_INSTDIR/ezimagecatalogue/catalogue to $C_WWWDIR/ezimagecatalogue... "
		if [ ! -d "$C_WWWDIR/ezimagecatalogue" ]; then
			mkdir "$C_WWWDIR/ezimagecatalogue"
		fi
		ln -s "$C_INSTDIR/ezimagecatalogue/catalogue" "$C_WWWDIR/ezimagecatalogue"
		echo "done."
	fi

fi

# www-dir and inst-dir is the same target. Let's move all files.....
if [ "$C_INSTALL" = "new" ] && [ $C_WWWDIR = $C_INSTDIR ]; then
	echo moving all files
	mv * $C_INSTDIR
fi

############################################################################
# Now we want to change site.ini
#
echo ""
echo "#################################################################"
echo "Changing site.ini with our values."
echo -n "Adjusting bin/ini/site.ini... "
echo -n "SiteURL"; sed s:SiteURL=ez.no:"SiteURL=$C_HOSTNAME": $C_INSTDIR/bin/ini/override/site.ini > $C_INSTDIR/bin/ini/override/site.ini.tmp && mv $C_INSTDIR/bin/ini/override/site.ini.tmp $C_INSTDIR/bin/ini/override/site.ini
echo -n " SiteTitle"; sed s:'SiteTitle=eZ Systems':"SiteTitle=$C_TITLE": $C_INSTDIR/bin/ini/override/site.ini > $C_INSTDIR/bin/ini/override/site.ini.tmp && mv $C_INSTDIR/bin/ini/override/site.ini.tmp $C_INSTDIR/bin/ini/override/site.ini
echo -n " Server"; sed s:Server=localhost:"Server=$C_DBSERVER": $C_INSTDIR/bin/ini/override/site.ini > $C_INSTDIR/bin/ini/override/site.ini.tmp && mv $C_INSTDIR/bin/ini/override/site.ini.tmp $C_INSTDIR/bin/ini/override/site.ini
echo -n " Database"; sed s:Database=publish:"Database=$C_DBNAME": $C_INSTDIR/bin/ini/override/site.ini > $C_INSTDIR/bin/ini/override/site.ini.tmp && mv $C_INSTDIR/bin/ini/override/site.ini.tmp $C_INSTDIR/bin/ini/override/site.ini
echo -n " User"; sed s:User=publish:"User=$C_DBUSER": $C_INSTDIR/bin/ini/override/site.ini > $C_INSTDIR/bin/ini/override/site.ini.tmp && mv $C_INSTDIR/bin/ini/override/site.ini.tmp $C_INSTDIR/bin/ini/override/site.ini
echo -n " Password"; sed s:Password=publish:"Password=$C_DBPASS": $C_INSTDIR/bin/ini/override/site.ini > $C_INSTDIR/bin/ini/override/site.ini.tmp && mv $C_INSTDIR/bin/ini/override/site.ini.tmp $C_INSTDIR/bin/ini/override/site.ini
echo " ...done."


############################################################################
# Try to do the rest for the old install
#
if [ "$C_INSTALL" = "old" ]; then
	echo "
NameVirtualHost $C_IPADDRESS
<VirtualHost $C_HOSTNAME>
  <Directory $C_WWWDIR>
		Options FollowSymLinks Indexes ExecCGI
		AllowOverride None 
   </Directory>
 
   RewriteEngine On 
   RewriteRule ^/stats/store/(.*).gif\$  $C_WWWDIR/ezstats/user/storestats.php [S=2]
   RewriteRule ^/filemanager/filedownload/([^/]+)/(.*)\$  $C_WWWDIR/ezfilemanager/files/\$1 [T="application/oct-stream",S=1]
   RewriteRule !\.(ico|png|css|jpg|jpeg|gif|js|jar|swf|rm|rmm|ram|rpm|mp3|mp4|wma|asx|asf|wmv|mov|mpg|mpeg|ogg|wav|a
u|aiff|midi|mid|pdf)\$ $C_WWWDIR/index.php
 
   ServerAdmin webmaster@$C_HOSTNAME
   DocumentRoot $C_WWWDIR
   ServerName $C_HOSTNAME
</VirtualHost>
 
<VirtualHost admin.$C_HOSTNAME>
  <Directory $C_WWWDIR>
		Options FollowSymLinks Indexes ExecCGI
		AllowOverride None 
		RewriteEngine On
		RewriteRule     !\.(ico|png|css|jpg|jpeg|gif|js|jar|swf|rm|rmm|ram|rpm|mp3|mp4|wma|asx|asf|wmv|mov|mpg|mpeg|ogg|wav|a
u|aiff|midi|mid|pdf) $C_WWWDIR/index_admin.php
  </Directory>
 
  ServerAdmin webmaster@$C_HOSTNAME
  DocumentRoot $C_WWWDIR
  ServerName admin.$C_HOSTNAME
</VirtualHost>
	" > $C_INSTDIR/apache.ini

	echo -n "Moving files to $C_WWWDIR... "
	mv * $C_WWWDIR
	if [ $? = 0 ]; then
		echo "done."
	else
		echo "FAILED!"
	fi

	echo ""
	echo "#################################################################"
	echo "In $C_WWWDIR/apache.ini you find the finished virtualhost setup."
	echo "Append that to your Apache config file and restart apache."
	echo "Then go to http://$C_HOSTNAME and if you're lucky it works. ;-)"
else
	echo ""
	echo "#################################################################"
	echo "Everything should be finished now. Go to http://$C_HOSTNAME$C_URLDIR"
	echo "and see if it works."
fi
	

echo ""
echo ""
echo "Happy Hacking!"
echo ""
echo "Graham Brookins <info at brookinsconsulting dot com> - $DATE"

