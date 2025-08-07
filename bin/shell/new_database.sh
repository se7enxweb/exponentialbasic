#!/bin/sh
# This script will create the publish MySQL database with all the patches applied.
# by Chris Mason

#vars
mysqldb='update/generated/publish_mysql.sql'
postgresdb='update/generated/publish_postgresql.sql'
mysqldata='update/generated/data_mysql.sql'
# default_dbname='publish'
default_dbname='ez_ezp24';

# delete old files
modules=`ls -d ez*`
rm -f $postgresdb
rm -f $mysqldb

# build sql files for mysql and postgres
for module in $modules
do
    if [ -f $module/sql/postgresql/$module.sql ]
   then cat $module/sql/postgresql/$module.sql >> $postgresdb
    fi
    if [ -f $module/sql/mysql/$module.sql ]
   then cat $module/sql/mysql/$module.sql >> $mysqldb
    fi
done


# Get database name
echo -n "Name of Database to create [$default_dbname]:"
read DBNAME
if [ -n "$DBNAME" ]; then
   echo -n 'Database is' $DBNAME
else
   DBNAME="$default_dbname"
fi
echo "Database "$DBNAME" will be created"

# get database type
echo -n "mysql or postgres [mysql]:"
read DBTYPE
if [ -n "$DBTYPE" ]; then
   echo -n 'Database is' $DBTYPE
else
   DBTYPE="mysql"
fi

# test for postgres, which is not supported yet.
if [ "$DBTYPE" = "postgres" ]; then
echo "Sorry, not available yet"
break
fi

# Does the user want to add data
echo -n "Do you want to add data [y/N]"
read ADDDATA
 
echo -n 'Mysql root password: '
read PASS
if [ -n "$PASS" ]
then
   echo "Password is $PASS"
   
   echo "Dropping database"

      if  mysqladmin -u root -p'$PASS' drop $DBNAME
      then
         echo "Dropping database"
      else
         echo "No database to drop"
      fi

   echo "Creating database"
   mysqladmin -u root -p'$PASS' create $DBNAME
   echo "Adding Tables"
   mysql -u root -p'$PASS' $DBNAME < $mysqldb

   if [ "$ADDDATA" = "Y" ] || [ "$ADDDATA" = "y" ] || [ "$ADDDATA" = "yes" ] || [ "$ADDDATA" = "YES" ]; then
      echo "Adding Data"
      mysql -u root -p'$PASS' $DBNAME < $mysqldata 
   fi

   mysql -u root -p'$PASS' -e"grant all on $DBNAME.* to $DBNAME@localhost identified by '$DBNAME' " 
else
   echo "Blank Password"
   if  mysqladmin -u root drop $DBNAME
   then
      echo "Dropping database"
   else
      echo "No database to drop"
   fi

   echo "Creating database"
   mysqladmin -u root create $DBNAME
   echo "Adding Tables"
   mysql -u root $DBNAME < $mysqldb

   if [ "$ADDDATA" = "Y" ] || [ "$ADDDATA" = "y" ] || [ "$ADDDATA" = "yes" ] || [ "$ADDDATA" = "YES" ]; then
      echo "Adding Data"
      mysql -u root $DBNAME < $mysqldata 
   fi

   mysql -u root -e"grant all on $DBNAME.* to $DBNAME@localhost identified by '$DBNAME' "   
fi


