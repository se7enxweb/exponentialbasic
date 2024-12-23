<?php
//
// $Id: ezconsultation.php 9529 2002-05-14 11:17:05Z jhe $
//
// Definition of eZConsultation class
//
// Created on: <19-Mar-2001 16:51:20 amos>
//
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, US
//

//!! eZContact
//! eZConsultation handles consultations with contact persons and companies.
/*!

  Example code:
  \code
  $consult = new eZConsultation();
  $consult->setShortDescription( "Signed multi-million dollar deal with ..." );
  $consult->store();

  $stored_consult = new eZConsultation( $consultID );
  $notificators = $stored_consult->notificators();
  foreach ( $notificators as $notification )
  {
      print( $notification->email() );
  }

  \endcode

  \sa eZPerson eZCompany eZUser
*/

// include_once( "classes/ezdb.php" );
// include_once( "classes/ezdatetime.php" );
// include_once( "ezcontact/classes/ezperson.php" );
// include_once( "ezcontact/classes/ezcompany.php" );
// include_once( "ezuser/classes/ezusergroup.php" );

// include_once( "classes/ezlog.php" );

class eZConsultation
{
    /*!
      Constructs a new eZConsultation object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id = -1 )
    {
        $this->ID = 0;

        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores the consultation to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $date = $this->Date->timeStamp();
        $shortdesc = $db->escapeString( $this->ShortDesc );
        $description = $db->escapeString( $this->Description );
        $emailnotice = $db->escapeString( $this->EmailNotice );
        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZContact_Consultation" );
            $this->ID = $db->nextID( "eZContact_Consultation", "ID" );
            $res[] = $db->query( "INSERT INTO eZContact_Consultation
                                  (ID, ShortDesc, Description, StateID, EmailNotifications, Date)
                                  VALUES
                                  ('$this->ID',
                                   '$shortdesc',
                                   '$description',
                                   '$this->State',
                                   '$emailnotice',
	                               '$date')" );
            $db->unlock();
        }
        else
        {
            $res[] = $db->query( "UPDATE eZContact_Consultation SET
                                        ShortDesc='$shortdesc',
                                        Description='$description',
                                        StateID='$this->State',
                                        EmailNotifications='$this->EmailNotice',
                                        Date='$date'
                                        WHERE ID='$this->ID'" );
        }
        eZDB::finish( $res, $db );
        return true;
    }

    /*!
      Deletes an eZConsultation object from the database.
    */
    function delete( $id = false )
    {
        if ( !$id )
            $id = $this->ID;
        if ( isset( $id ) && is_numeric( $id ) )
        {
            $db =& eZDB::globalDatabase();
            $db->begin();
            $res[] = $db->query( "DELETE FROM eZContact_Consultation WHERE ID='$id'" );
            $db->array_query( $qry_array, "SELECT ConsultationID FROM eZContact_ConsultationPersonUserDict WHERE ConsultationID='$id'" );
            if ( count( $qry_array ) > 0 )
            {
                $res[] = $db->query( "DELETE FROM eZContact_ConsultationPersonUserDict WHERE ConsultationID='$id'" );
            }
            $db->array_query( $qry_array, "SELECT ConsultationID FROM eZContact_ConsultationCompanyUserDict WHERE ConsultationID='$id'" );
            if ( count( $qry_array ) > 0 )
            {
                $res[] = $db->query( "DELETE FROM eZContact_ConsultationCompanyUserDict WHERE ConsultationID='$id'" );
            }
            eZDB::finish( $res, $db );
        }
        return true;
    }

    /*!
      Fetches the object information from the database.
    */
    function get( $id = -1 )
    {
        $ret = false;
        $consult_array = array();

        if ( $id != -1 )
        {
            $db =& eZDB::globalDatabase();
            $db->query_single( $consult_array, "SELECT * FROM eZContact_Consultation WHERE ID='$id'" );

            if( is_array( $consult_array ) && count( $consult_array ) > 0 )
            {
                $this->ID = $consult_array[$db->fieldName( "ID" )];
                $this->ShortDesc = $consult_array[$db->fieldName( "ShortDesc" )];
                $this->Description = $consult_array[$db->fieldName( "Description" )];
                $this->State = $consult_array[$db->fieldName( "StateID" )];
                $this->EmailNotice = $consult_array[$db->fieldName( "EmailNotifications" )];
                $this->Date = new eZDate();
                $this->Date->setTimeStamp( $consult_array[$db->fieldName( "Date" )] );
                $ret = true;
            }

        }
        return $ret;
    }

    /*!
      Sets the short description for the consultation.
    */
    function setShortDescription( $desc )
    {
        $this->ShortDesc = $desc;
    }

    /*!
      Sets the full description for the consultation.
    */
    function setDescription( $desc )
    {
        $this->Description = $desc;
    }

    /*!
      Sets the state type for the consultation.
    */
    function setState( $state )
    {
        $this->State = $state;
    }

    /*!
      Adds a new group to the consultation,
      you can either use the group id or a eZUserGroup object.
    */
    function addGroup( $group )
    {
        if ( is_a( $group, "eZUserGroup" ) )
            $groupid = $group->id();
        else
            $groupid = $group;
        $db =& eZDB::globalDatabase();
        $db->begin();
        $res = $db->query( "INSERT INTO eZContact_ConsultationGroupsDict
                            (ConsultationID, GroupID)
                            VALUES
                            ('$this->ID', '$groupid')" );
        eZDB::finish( $res, $db );
    }

    /*!
      Removes an existing group from the consultation.
    */
    function removeGroup( $group )
    {
    }

    /*!
      Removes all groups from the consultation.
    */
    function removeGroups()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $res[] = $db->query( "DELETE FROM eZContact_ConsultationGroupsDict
                            WHERE ConsultationID='$this->ID'" );
        eZDB::finish( $res, $db );
    }

    /*!
      Adds a new email to the consultation.
    */
    function addEmail( $email )
    {
    }

    /*!
      Sets the email list for the consultation.
    */
    function setEmail( $emails )
    {
        $this->EmailNotice = $emails;
    }

    /*!
      Removes an existing email from the consultation.
    */
    function removeEmail( $email )
    {
    }

    /*!
      Sets the date for the consultation.
    */
    function setDate( $date )
    {
        $this->Date = $date;
    }

    /*!
      Returns the id for the consultation
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the short description for the consultation
    */
    function shortDescription()
    {
        return $this->ShortDesc;
    }

    /*!
      Returns the full description for the consultation.
    */
    function description()
    {
        return $this->Description;
    }

    /*!
      Returns the state of the consultation,
      this usually tells if the consultation is under negotiation, closing or similar.
    */
    function state()
    {
        return $this->State;
    }

    /*!
      Returns an array with eZUserGroup objects,
      these groups are used for sending an email reply.
    */
    function groupList()
    {
        $db =& eZDB::globalDatabase();
        $db->array_query( $qry_array, "SELECT GroupID FROM eZContact_ConsultationGroupsDict
                                       WHERE ConsultationID='$this->ID'" );
        $ret_array = array();
        foreach ( $qry_array as $qry )
        {
            $ret_array[] = new eZUserGroup( $qry[$db->fieldName( "GroupID" )] );
        }
        return $ret_array;
    }

    /*!
      Returns an array with eZUserGroup IDs.
      \sa groupList
    */
    function groupIDList()
    {
        $db =& eZDB::globalDatabase();
        $db->array_query( $qry_array, "SELECT GroupID FROM eZContact_ConsultationGroupsDict
                                       WHERE ConsultationID='$this->ID'" );
        $ret_array = array();
        foreach ( $qry_array as $qry )
        {
            $ret_array[] = $qry[$db->fieldName( "GroupID" )];
        }
        return $ret_array;
    }

    /*!
      Returns an array with strings, these strings each contain an email address.
    */
    function emailList()
    {
        $emails = preg_split( "/[,;]/", $this->EmailNotice );
        return $emails;
    }

    /*!
      Returns the string with email notifications,
      this is an unparsed form, use emailList() to get them parsed.
    */
    function emails()
    {
        return $this->EmailNotice;
    }

    /*!
      Returns an array with eZNotification objects,
      this array is built from both the groupList() and the emailList().
    */
    function notificators()
    {
    }

    /*!
      Returns the date of the consultation.
    */
    function date()
    {
        return $this->Date;
    }

    /*!
      \static
      Finds all consultations on a specific state type.
    */
    static public function findConsultationsByState( $state )
    {
    }

    /*!
      \static
      Returns true if the consultation given by $consultationid belongs to $user.
    */
    function belongsTo( $consultationid, $user )
    {
        if ( is_a( $user, "eZUser" ) )
            $user = $user->id();
        $db =& eZDB::globalDatabase();
        // Check company
        $db->array_query( $qry_array, "SELECT C.ID FROM eZContact_Consultation AS C,
                                                      eZContact_ConsultationCompanyUserDict AS CCUD
                                       WHERE CCUD.UserID='$user' AND C.ID=CCUD.ConsultationID
                                       AND C.ID='$consultationid'", 0, 1 );
        if ( count( $qry_array ) == 1 )
            return true;
        // Check person
        $db->array_query( $qry_array, "SELECT C.ID FROM eZContact_Consultation AS C,
                                                      eZContact_ConsultationPersonUserDict AS CPUD
                                       WHERE CPUD.UserID='$user' AND C.ID=CPUD.ConsultationID
                                       AND C.ID='$consultationid'", 0, 1 );
        if ( count( $qry_array ) == 1 )
            return true;
        return false;
    }

    /*!
      \static
      Finds all companies which the user has consultations with and returns an array with IDs.
    */
    static public function findConsultedCompanies( $user )
    {
        if ( is_a( $user, "eZUser" ) )
            $user = $user->id();
        if ( $user == -1 )
            $userString = "";
        else
            $userString = "WHERE UserID='$user'";

        $db =& eZDB::globalDatabase();
        $db->array_query( $qry_array, "SELECT CompanyID FROM eZContact_ConsultationCompanyUserDict
                                       $userString
                                       GROUP BY CompanyID" );
        $ret_array = array();
        foreach ( $qry_array as $qry )
        {
            $ret_array[] = new eZCompany( $qry[$db->fieldName( "CompanyID" )] );
        }
        return $ret_array;
    }

    /*!
      \static
      Finds all persons which the user has consultations with and returns an array with IDs.
    */
    static public function findConsultedPersons( $user )
    {
        if ( is_a( $user, "eZUser" ) )
            $user = $user->id();
        if ( $user == -1 )
            $userString = "WHERE UserID='$user'";
        else
            $userString = "";

        $qry_array = array();
        $db =& eZDB::globalDatabase();
        $db->array_query( $qry_array, "SELECT PersonID FROM eZContact_ConsultationPersonUserDict
                                       $userString
                                       GROUP BY PersonID" );
        $ret_array = array();
        foreach ( $qry_array as $qry )
        {
            $ret_array[] = new eZPerson( $qry[$db->fieldName( "PersonID" )] );
        }
        return $ret_array;
    }

    /*!
      \static
      Finds all consultations on a specific contact person or company between two timestamps.
    */
    function findConsultationsByDate( $user, $startTime, $endTime )
    {
        if ( is_a( $user, "eZUser" ) )
            $user = $user->id();

        if ( $user == -1 )
        {
            $userString = "";
            $userString2 = "";
        }
        else
        {
            $userString = "CPUD.UserID='$user' AND ";
            $userString2 = "CPCD.UserID='$user' AND ";
        }

        $qry_array = array();
        $db =& eZDB::globalDatabase();
        $db->array_query( $qry_array, "SELECT CPUD.ConsultationID
                                       FROM
                                       eZContact_ConsultationPersonUserDict AS CPUD,
                                       eZContact_Consultation AS C
                                       WHERE
                                       $userString
                                       CPUD.ConsultationID = C.ID AND
                                       C.Date>='" . $startTime->timeStamp() . "' AND
                                       C.Date<'" . $endTime->timeStamp() . "'" );
        $ret_array = array();
        foreach ( $qry_array as $qry )
        {
            $ret_array[] = new eZConsultation( $qry[$db->fieldName( "ConsultationID" )] );
        }
        $db->array_query( $qry_array, "SELECT CPCD.ConsultationID
                                       FROM
                                       eZContact_ConsultationCompanyUserDict AS CPCD,
                                       eZContact_Consultation AS C
                                       WHERE
                                       $userString2
                                       CPCD.ConsultationID = C.ID AND
                                       C.Date>='" . $startTime->timeStamp() . "' AND
                                       C.Date<'" . $endTime->timeStamp() . "'" );
        foreach ( $qry_array as $qry )
        {
            $ret_array[] = new eZConsultation( $qry[$db->fieldName( "ConsultationID" )] );
        }
        return $ret_array;
    }

    /*!
      \static
      Finds all consultations on a specific contact person or company.
    */
    static public function findConsultationsByContact( $contact, $user, $OrderBy = "ID", $is_person = true, $index = 0, $max = -1, $relations = false )
    {
        if ( is_a( $user, "eZUser" ) )
            $user = $user->id();
        if ( $max > 0 )
        {
            $limit = array( "Offset" => $index, "Limit" => $max );
        }
        else
        {
            $limit = array();
        }

        if ( $user == -1 )
        {
            $userString = "";
            $userString2 = "";
        }
        else
        {
            $userString = "CPUD.UserID='$user' AND ";
            $userString2 = "CPCD.UserID='$user' AND ";
        }

        switch ( strtolower( $OrderBy ) )
        {
            case "description":
            case "desc":
                $OrderBy = "ORDER BY C.ShortDesc";
                break;
            case "date":
                $OrderBy = "ORDER BY C.Date DESC";
                break;
            case "status":
                $OrderBy = "ORDER BY CT.Name";
                break;
            case "id":
            case "typeid":
                $OrderBy = "ORDER BY C.ID";
                break;
            default:
                $OrderBy = "ORDER BY C.Date DESC";
                break;
        }

        $relationString = "";
        $qry_array = array();
        $db =& eZDB::globalDatabase();
        if ( $is_person )
        {
            $db->array_query( $qry_array, "SELECT CPUD.ConsultationID
                                           FROM
                                           eZContact_ConsultationPersonUserDict AS CPUD,
                                           eZContact_Consultation AS C,
                                           eZContact_ConsultationType AS CT
                                           WHERE
                                           CPUD.PersonID='$contact' AND
                                           (C.StateID = CT.ID OR C.StateID = 0) AND
                                           $userString
                                           CPUD.ConsultationID = C.ID
                                           $OrderBy", $limit );
            if ( $relations )
            {
                $companyArray = eZCompany::persons( $contact, false );
                foreach ( $companyArray as $companyID )
                {
                    $relationString .= "CPCD.CompanyID='$companyID' AND ";
                }
                $db->array_query( $qry_array2, "SELECT CPCD.ConsultationID
                                                FROM
                                                eZContact_ConsultationCompanyUserDict AS CPCD,
                                                eZContact_Consultation AS C,
                                                eZContact_ConsultationType AS CT
                                                WHERE
                                                $relationString
                                                (C.StateID = CT.ID OR C.StateID = 0) AND
                                                $userString2
                                                CPCD.ConsultationID = C.ID
                                                $OrderBy", $limit );
                $qry_array = array_merge( $qry_array, $qry_array2 );
            }
        }
        else
        {
            $db->array_query( $qry_array, "SELECT CPCD.ConsultationID
                                           FROM
                                           eZContact_ConsultationCompanyUserDict AS CPCD,
                                           eZContact_Consultation AS C,
                                           eZContact_ConsultationType AS CT
                                           WHERE
                                           CPCD.CompanyID='$contact' AND
                                           (C.StateID = CT.ID OR C.StateID = 0) AND
                                           $userString2
                                           CPCD.ConsultationID = C.ID
                                           $OrderBy", $limit );
            if ( $relations )
            {
                $personArray = eZCompany::persons( $contact, false );
                foreach ( $personArray as $personID )
                {
                    $relationString .= "CPUD.PersonID='$personID' AND ";
                }
                $db->array_query( $qry_array2, "SELECT CPUD.ConsultationID
                                                FROM
                                                eZContact_ConsultationPersonUserDict AS CPUD,
                                                eZContact_Consultation AS C,
                                                eZContact_ConsultationType AS CT
                                                WHERE
                                                $relationString
                                                (C.StateID = CT.ID OR C.StateID = 0) AND
                                                $userString
                                                CPUD.ConsultationID = C.ID
                                                $OrderBy", $limit );
                $qry_array = array_merge( $qry_array, $qry_array2 );
            }
        }
        $ret_array = array();
        foreach ( $qry_array as $qry )
        {
            $ret_array[] = new eZConsultation( $qry[$db->fieldName( "ConsultationID" )] );
        }
        return $ret_array;
    }

    /*!
      \static
      Finds the n latest consultations.
    */
    static public function findLatestConsultations( $user, $max )
    {
        if ( is_a( $user, "eZUser" ) )
        {
            $user = $user->id();
            $userString = "CPUD.UserID='$user' AND ";
            $userString2 = "CPCD.UserID='$user' AND ";
        }
        else if ( $user == -1 )
        {
            $userString = "";
            $userString2 = "";
        }
        else
        {
            $userString = "CPUD.UserID='$user' AND ";
            $userString2 = "CPCD.UserID='$user' AND ";
        }
        $qry_array = array();
        $db =& eZDB::globalDatabase();
        $db->array_query( $qry_array, "SELECT C.Date, C.ID
                                       FROM
                                       eZContact_ConsultationPersonUserDict AS CPUD,
                                       eZContact_Consultation AS C
                                       WHERE
                                       $userString
                                       CPUD.ConsultationID = C.ID
                                       ORDER BY C.Date DESC, C.ID DESC",
                                       array( "Limit" => $max ) );
        $db->array_query_append( $qry_array, "SELECT C.Date, C.ID
                                              FROM
                                              eZContact_ConsultationCompanyUserDict AS CPCD,
                                              eZContact_Consultation AS C
                                              WHERE
                                              $userString2
                                              CPCD.ConsultationID = C.ID
                                              ORDER BY C.Date DESC, C.ID DESC",
                                              array( "Limit" => $max ) );
        arsort( $qry_array );
        $ret_array = array();
        $qry_array = array_slice( $qry_array, 0, $max );
        foreach ( $qry_array as $qry )
        {
            $ret_array[] = new eZConsultation( $qry[$db->fieldName( "ID" )] );
        }
        return $ret_array;
    }

    /*!
     */
    static public function companyConsultationCount( $company, $user )
    {
        if ( is_a( $user, "eZUser" ) )
            $user = $user->id();
        if ( $user == -1 )
            $userString = "";
        else
            $userString = "AND UserID='$user'";
        $db =& eZDB::globalDatabase();
        $db->query_single( $qry, "SELECT count( ConsultationID ) as Count FROM eZContact_ConsultationCompanyUserDict
                                  WHERE CompanyID='$company' $userString" );
        return $qry[$db->fieldName( "Count" )];
    }

    /*!
     */
    function personConsultationCount( $person, $user )
    {
        if ( is_a( $user, "eZUser" ) )
            $user = $user->id();
        if ( $user == -1 )
            $userString = "";
        else
            $userString = "AND UserID='$user'";
        $db =& eZDB::globalDatabase();
        $db->query_single( $qry, "SELECT count( ConsultationID ) as Count FROM eZContact_ConsultationPersonUserDict
                                  WHERE PersonID='$person' $userString" );
        return $qry[$db->fieldName( "Count" )];
    }

    /*!
     */
    function company( $user = false )
    {
        if ( !$user )
        {
            $userString = "";
        }
        else if ( is_a( $user, "eZUser" ) )
        {
            $user = $user->id();
            $userString = " AND UserID='$user'";
        }
        else
        {
            $userString = "";
        }

        $db =& eZDB::globalDatabase();
        $db->array_query( $qry_array, "SELECT CompanyID FROM eZContact_ConsultationCompanyUserDict
                                       WHERE ConsultationID='$this->ID' $userString" );
        if ( count( $qry_array ) == 1 )
        {
            return $qry_array[0][$db->fieldName( "CompanyID" )];
        }
        else
        {
            return false;
        }
    }

    /*!
     */
    function person( $user = false )
    {
        if ( !$user )
        {
            $userString = "";
        }
        else if ( is_a( $user, "eZUser" ) )
        {
            $user = $user->id();
            $userString = " AND UserID='$user'";
        }
        else
        {
            $userString = "";
        }

        $db =& eZDB::globalDatabase();
        $db->array_query( $qry_array, "SELECT PersonID FROM eZContact_ConsultationPersonUserDict
                                       WHERE ConsultationID='$this->ID' $userString" );
        if ( count( $qry_array ) == 1 )
        {
            return $qry_array[0][$db->fieldName( "PersonID" )];
        }
        else
        {
            return false;
        }
    }

    /*!
     */
    function addConsultationToPerson( $person, $user )
    {
        if ( is_a( $user, "eZUser" ) )
            $user = $user->id();
        $db =& eZDB::globalDatabase();
        $db->begin();
        $db->lock( "eZContact_ConsultationPersonUserDict" );
        $db->query( "INSERT INTO eZContact_ConsultationPersonUserDict
                     (ConsultationID, PersonID, UserID)
                     VALUES
                     ('$this->ID', '$person', '$user')" );
        $db->unlock();
        eZDB::finish( $res, $db );
    }

    /*!
     */
    function addConsultationToCompany( $company, $user )
    {
        if ( is_a( $user, "eZUser" ) )
            $user = $user->id();
        $db =& eZDB::globalDatabase();
        $db->begin();
        $res[] = $db->query( "INSERT INTO eZContact_ConsultationCompanyUserDict
                              (ConsultationID, CompanyID, UserID)
                              VALUES
                              ('$this->ID', '$company', '$user')" );
        eZDB::finish( $res, $db );
    }

    /*!
     */
    function removeConsultationFromPerson( $person, $user )
    {
        if ( is_a( $user, "eZUser" ) )
            $user = $user->id();
        $db =& eZDB::globalDatabase();
        $res[] = $db->query( "DELETE FROM eZContact_ConsultationPersonUserDict
                              WHERE ConsultationID='$this->ID' AND PersonID='$person' AND UserID='$user'" );
        eZDB::finish( $res, $db );
    }

    /*!
     */
    function removeConsultationFromCompany( $company, $user )
    {
        if ( is_a( $user, "eZUser" ) )
            $user = $user->id();
        $db =& eZDB::globalDatabase();
        $res[] = $db->query( "DELETE FROM eZContact_ConsultationCompanyUserDict
                              WHERE ConsultationID='$this->ID' AND CompanyID='$company' AND UserID='$user'" );
        eZDB::finish( $res, $db );
    }

    /*!
      Returns the owner of the consultation
    */
    function owner( $id = -1, $is_person = true )
    {
        if ( $id == -1 )
            $id = $this->ID;
        if ( $is_person )
            $table = "eZContact_ConsultationPersonUserDict";
        else
            $table = "eZContact_ConsultationCompanyUserDict";

        $db =& eZDB::globalDatabase();
        $db->array_query( $res, "SELECT UserID FROM $table WHERE ConsultationID='$id'" );
        return $res[0][$db->fieldName( "UserID" )];
    }

    /*!
      \static
      Returns the name of the state id.
    */
    static public function stateName( $state )
    {
        $db =& eZDB::globalDatabase();
        $db->query_single( $state_row, "SELECT Name FROM eZContact_ConsultationType WHERE ID='$state'" );
        return $state_row[$db->fieldName( "Name" )];
    }

    var $ID;
    var $ShortDesc;
    var $Description;
    var $State;
    var $Date;
    var $EmailNotice;
}

?>