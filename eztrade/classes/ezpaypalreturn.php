<?php
//!! eZPaypalReturn
//! Base class 

// Include the "abstract" ezPublish classes */
// include_once( "classes/ezdb.php" );
// include_once( "classes/ezdatetime.php" );

class eZPaypalReturn {
    // CREATOR  Bob Sims <bob.sims@gmail.com>
    /*!
      Constructs a new eZPaypalReturn object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id=-1 ) {
        if ( $id != -1 ) {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    // MANIPULATORS
    /*!
     Gets a single Paypal return
    */
    function get( $id=-1 ) {
        $db =& eZDB::globalDatabase();
        $ret = false;
        if ( $id != -1  ) {
            $db->array_query($paypal_array,
                             "SELECT * FROM eZTrade_PaypalReturn ".
                             "WHERE ID='$id'" );
            if ( count( $paypal_array ) > 1 ) {
                die( "Error: Duplicate PaypalReturn ID found in database.".
                     "This shouldn't happen." );
            } else if ( count( $paypal_array ) == 1 ) {
                $this->ID =& $paypal_array[0][$db->fieldName("ID")];
                $this->Invoice =
                    & $paypal_array[0][$db->fieldName("Invoice")];
                $this->Receiver_email =
                    & $paypal_array[0][$db->fieldName("Receiver_email")];
                $this->Payment_status =
                    & $paypal_array[0][$db->fieldName("Payment_status")];
                $this->Pending_reason =
                    & $paypal_array[0][$db->fieldName("Pending_reason")];
                $this->Reason_code =
                    & $paypal_array[0][$db->fieldName("Reason_code")];
                $this->Payment_date =
                    & $paypal_array[0][$db->fieldName("Payment_date")];
                $this->Payment_gross =
                    & $paypal_array[0][$db->fieldName("Payment_gross")];
                $this->Payment_fee =
                    & $paypal_array[0][$db->fieldName("Payment_fee")];					
                $this->Txn_id =
                    & $paypal_array[0][$db->fieldName("Txn_id")];
                $this->Txn_type =
                    & $paypal_array[0][$db->fieldName("Txn_type")];
                $this->Parent_txn_id =
                    & $paypal_array[0][$db->fieldName("Parent_txn_id")];
                $this->First_name =
                    & $paypal_array[0][$db->fieldName("First_name")];																				
                $this->Last_name =
                    & $paypal_array[0][$db->fieldName("Last_name")];
                $this->Payer_email =
                    & $paypal_array[0][$db->fieldName("Payer_email")];
                $this->Payer_status =
                    & $paypal_array[0][$db->fieldName("Payer_status")];																
                $this->Payment_type =
                    & $paypal_array[0][$db->fieldName("Payment_type")];	
                $this->Notify_version =
                    & $paypal_array[0][$db->fieldName("Notify_version")];	
                $this->Verify_sign =
                    & $paypal_array[0][$db->fieldName("Verify_sign")];	
                $this->Mc_currency =
                    & $paypal_array[0][$db->fieldName("Mc_currency")];	
                $this->Payer_business_name =
                    & $paypal_array[0][$db->fieldName("Payer_business_name")];	
                $this->Payer_id =
                    & $paypal_array[0][$db->fieldName("Payer_id")];	
                $this->Mc_gross =
                    & $paypal_array[0][$db->fieldName("Mc_gross")];	
                $this->Mc_fee =
                    & $paypal_array[0][$db->fieldName("Mc_fee")];	
                $this->Settle_amount =
                    & $paypal_array[0][$db->fieldName("Settle_amount")];		
                $this->Settle_currency =
                    & $paypal_array[0][$db->fieldName("Settle_currency")];	
                $this->Exchange_rate =
                    & $paypal_array[0][$db->fieldName("Exchange_rate")];	
                $this->Date_added =
                    & $paypal_array[0][$db->fieldName("Date_added")];						
                $ret = true;
            }
        }
        return $ret;
    }

	
    /*!
     Gets a list of Paypal results
    */
    function &getAll() {
        $db =& eZDB::globalDatabase();
        
        $return_array = array();
        $result_array = array();
        
        $db->array_query($result_array,
                 "SELECT ID FROM eZTrade_PaypalReturn ".
                 "ORDER BY Date_added" );
        
        for ( $i=0; $i<count($result_array); $i++ ) {
            $return_array[$i]=
                new eZPaypalReturn($result_array[$i][$db->fieldName("ID")] );
        }
        return $return_array;
    }

	    /*!
     Gets a list of Paypal results with matching Parent_txn_id numbers
    */
    function &getRelated( $txn_id=false) {
		
		if ( !$txn_id )
			return false;
			
        $db =& eZDB::globalDatabase();
        
        $return_array = array();
        $result_array = array();
        
        $db->array_query($result_array,
                 "SELECT ID FROM eZTrade_PaypalReturn ".
				 "WHERE Parent_txn_id = '$txn_id'".
                 "ORDER BY Date_added" );
		
        for ( $i=0; $i<count($result_array); $i++ ) {
            $return_array[$i]=
                new eZPaypalReturn($result_array[$i][$db->fieldName("ID")] );
        }
        return $return_array;
    }
	
    // ACCESSORS
    /*!
     Returns the current Paypal return id, for links etc.
    */
    function id() {
       return $this->ID;
    }

    /*!
     Returns the invoice (order) number
    */
    function invoice() {
        return $this->Invoice;
    }

    /*!
     Returns the receiver email
    */
    function receiver_email() {
        return $this->Receiver_email;
    }

    /*!
     Returns the payment_status
    */
    function payment_status() {
        return $this->Payment_status;
    }

    /*!
     Returns the pending_reason
    */
    function pending_reason() {
        return $this->Pending_reason;
    }

    /*!
     Returns the reason_code
    */
    function Reason_code() {
        return $this->Invoice;
    }

    /*!
     Returns the payment_date
    */
    function payment_date() {
        return $this->Payment_date;
    }

    /*!
     Returns the payment_gross
    */
    function payment_gross() {
        return $this->Payment_gross;
    }

    /*!
     Returns the payment_fee
    */
    function payment_fee() {
        return $this->Payment_fee;
    }

    /*!
     Returns the txn_id
    */
    function txn_id() {
        return $this->Txn_id;
    }

    /*!
     Returns the txn_type
    */
    function txn_type() {
        return $this->Txn_type;
    }

    /*!
     Returns the parent_txn_id
    */
    function parent_txn_id() {
        return $this->Parent_txn_id;
    }

    /*!
     Returns the first_name
    */
    function first_name() {
        return $this->First_name;
    }

    /*!
     Returns the last_name
    */
    function last_name() {
        return $this->Last_name;
    }

    /*!
     Returns the payer_email
    */
    function payer_email() {
        return $this->Payer_email;
    }

    /*!
     Returns the payer_status
    */
    function payer_status() {
        return $this->Payer_status;
    }

    /*!
     Returns the payment_type
    */
    function payment_type() {
        return $this->Payment_type;
    }

    /*!
     Returns the notify_version
    */
    function notify_version() {
        return $this->Notify_version;
    }

    /*!
     Returns the verify_sign
    */
    function verify_sign() {
        return $this->Verify_sign;
    }

    /*!
     Returns the mc_currency
    */
    function mc_currency() {
        return $this->Mc_currency;
    }

    /*!
     Returns the payer_business_name
    */
    function payer_business_name() {
        return $this->Payer_business_name;
    }

    /*!
     Returns the payer_id
    */
    function payer_id() {
        return $this->Payer_id;
    }

    /*!
     Returns the mc_gross
    */
    function mc_gross() {
        return $this->Mc_gross;
    }

    /*!
     Returns the mc_fee
    */
    function mc_fee() {
        return $this->Mc_fee;
    }

    /*!
     Returns the settle_amount
    */
    function settle_amount() {
        return $this->Settle_amount;
    }

    /*!
     Returns the settle_currency
    */
    function settle_currency() {
        return $this->Settle_currency;
    }

    /*!
     Returns the exchange_rate
    */
    function exchange_rate() {
        return $this->Exchange_rate;
    }

    /*!
     Returns the date_added
    */
    function date_added() {
        return $this->Date_added;
    }

	    /*!
      Returns true if the transaction has a duplicate txn_id.
    */
    function isDuplicate( $txn_id=-1 )
    {
       $return_value = false;
       $option_array = array();
       $db =& eZDB::globalDatabase();
       $db->array_query( $option_array, "SELECT ID FROM eZTrade_PaypalReturn WHERE Txn_id='$txn_id'" );
       if ( count( $option_array ) > 1 )
       {
           $return_value = true;
       }
       return $return_value;
    }
	
    // MANIPULATORS
    /*!
     Inserts or updates a Paypal return
    */
    function store() {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $db->lock( "eZTrade_PaypalReturn" );
        
        $nextID = $db->nextID( "eZTrade_PaypalReturn", "ID" );

        if ( empty( $this->ID ) ) {
			$timeStamp = eZDateTime::timeStamp( true );
				
            $ret = $db->query( "INSERT INTO eZTrade_PaypalReturn SET ".
				"ID = '$nextID',".
				"Invoice = '$this->Invoice',".
				"Receiver_email = '$this->Receiver_email',".
				"Payment_status = '$this->Payment_status',".
				"Pending_reason = '$this->Pending_reason',".
				"Reason_code = '$this->Reason_code',".
				"Payment_date = '$this->Payment_date',".
				"Payment_gross = '$this->Payment_gross',".
				"Payment_fee = '$this->Payment_fee',".
				"Txn_id = '$this->Txn_id',".
				"Txn_type = '$this->Txn_type',".
				"Parent_txn_id = '$this->Parent_txn_id',".
				"First_name = '$this->First_name',".
				"Last_name = '$this->Last_name',".
				"Payer_email = '$this->Payer_email',".
				"Payer_status = '$this->Payer_status',".
				"Payment_type = '$this->Payment_type',".
				"Notify_version = '$this->Notify_version',".
				"Verify_sign = '$this->Verify_sign',".
				"Mc_currency = '$this->Mc_currency',".
				"Payer_business_name = '$this->Payer_business_name',".
				"Payer_id = '$this->Payer_id',".
				"Mc_gross = '$this->Mc_gross',".
				"Mc_fee = '$this->Mc_fee',".
				"Settle_amount = '$this->Settle_amount',".
				"Settle_currency = '$this->Settle_currency',".
				"Exchange_rate = '$this->Exchange_rate',".
				"Date_added = '$timeStamp'"  );
			$this->ID = $nextID;
        } else {
            $ret = $db->query( "UPDATE eZTrade_PaypalReturn SET ".
				"ID = '$nextID',".
				"Invoice = '$this->Invoice',".
				"Receiver_email = '$this->Receiver_email',".
				"Payment_status = '$this->Payment_status',".
				"Pending_reason = '$this->Pending_reason',".
				"Reason_code = '$this->Reason_code',".
				"Payment_date = '$this->Payment_date',".
				"Payment_gross = '$this->Payment_gross',".
				"Payment_fee = '$this->Payment_fee',".
				"Txn_id = '$this->Txn_id',".
				"Txn_type = '$this->Txn_type',".
				"Parent_txn_id = '$this->Parent_txn_id',".
				"First_name = '$this->First_name',".
				"Last_name = '$this->Last_name',".
				"Payer_email = '$this->Payer_email',".
				"Payer_status = '$this->Payer_status',".
				"Payment_type = '$this->Payment_type',".
				"Notify_version = '$this->Notify_version',".
				"Verify_sign = '$this->Verify_sign',".
				"Mc_currency = '$this->Mc_currency',".
				"Payer_business_name = '$this->Payer_business_name',".
				"Payer_id = '$this->Payer_id',".
				"Mc_gross = '$this->Mc_gross',".
				"Mc_fee = '$this->Mc_fee',".
				"Settle_amount = '$this->Settle_amount',".
				"Settle_currency = '$this->Settle_currency',".
				"Exchange_rate = '$this->Exchange_rate',".
                "WHERE ID='$this->ID'" );
        }
        $db->unlock();

        if ( $ret == false )
            $db->rollback();
        else
            $db->commit();

        return $ret;
    }

    /*!
     Deletes a Paypal return
    */
    function delete( $id ) {
        $db =& eZDB::globalDatabase();
        
        $db->begin();
        $ret = $db->query(
                "DELETE FROM eZTrade_PaypalReturn WHERE ID='$id'" );

        if ( $ret == false )
            $db->rollback( );
        else
            $db->commit( );

        return $ret;
    }

    // ACCESSORS
	
    /*!
     Assigns the invoice (order) number
    */
    function setInvoice( $Invoice ) {
        $this->Invoice = $Invoice;
    }

    /*!
     Assigns the receiver email
    */
    function setReceiver_email($Receiver_email) {
        $this->Receiver_email=$Receiver_email;
    }

    /*!
     Assigns the payment_status
    */
    function setPayment_status($Payment_status) {
        $this->Payment_status=$Payment_status;
    }

    /*!
     Assigns the pending_reason
    */
    function setPending_reason($Pending_reason) {
        $this->Pending_reason=$Pending_reason;
    }

    /*!
     Assigns the reason_code
    */
    function setReason_code($Reason_code) {
        $this->Reason_code=$Reason_code;
    }

    /*!
     Assigns the payment_date
    */
    function setPayment_date($Payment_date) {
        $this->Payment_date=$Payment_date;
    }

    /*!
     Assigns the payment_gross
    */
    function setPayment_gross($Payment_gross) {
        $this->Payment_gross=$Payment_gross;
    }

    /*!
     Assigns the payment_fee
    */
    function setPayment_fee($Payment_fee) {
        $this->Payment_fee=$Payment_fee;
    }

    /*!
     Assigns the txn_id
    */
    function setTxn_id($Txn_id) {
        $this->Txn_id=$Txn_id;
    }

    /*!
     Assigns the txn_type
    */
    function setTxn_type($Txn_type) {
        $this->Txn_type=$Txn_type;
    }

    /*!
     Assigns the parent_txn_id
    */
    function setParent_txn_id($Parent_txn_id) {
        $this->Parent_txn_id=$Parent_txn_id;
    }

    /*!
     Assigns the first_name
    */
    function setFirst_name($First_name) {
        $this->First_name=$First_name;
    }

    /*!
     Assigns the last_name
    */
    function setLast_name($Last_name) {
        $this->Last_name=$Last_name;
    }

    /*!
     Assigns the payer_email
    */
    function setPayer_email($Payer_email) {
        $this->Payer_email=$Payer_email;
    }

    /*!
     Assigns the payer_status
    */
    function setPayer_status($Payer_status) {
        $this->Payer_status=$Payer_status;
    }

    /*!
     Assigns the payment_type
    */
    function setPayment_type($Payment_type) {
        $this->Payment_type=$Payment_type;
    }

    /*!
     Assigns the notify_version
    */
    function setNotify_version($Notify_version) {
        $this->Notify_version=$Notify_version;
    }

    /*!
     Assigns the verify_sign
    */
    function setVerify_sign($Verify_sign) {
        $this->Verify_sign=$Verify_sign;
    }

    /*!
     Assigns the mc_currency
    */
    function setMc_currency($Mc_currency) {
        $this->Mc_currency=$Mc_currency;
    }

    /*!
     Assigns the payer_business_name
    */
    function setPayer_business_name($Payer_business_name) {
        $this->Payer_business_name=$Payer_business_name;
    }

    /*!
     Assigns the payer_id
    */
    function setPayer_id($Payer_id) {
        $this->Payer_id=$Payer_id;
    }

    /*!
     Assigns the mc_gross
    */
    function setMc_gross($Mc_gross) {
        $this->Mc_gross=$Mc_gross;
    }

    /*!
     Assigns the mc_fee
    */
    function setMc_fee($Mc_fee) {
        $this->Mc_fee=$Mc_fee;
    }

    /*!
     Assigns the settle_amount
    */
    function setSettle_amount($Settle_amount) {
        $this->Settle_amount=$Settle_amount;
    }

    /*!
     Assigns the settle_currency
    */
    function setSettle_currency($Settle_currency) {
        $this->Settle_currency=$Settle_currency;
    }

    /*!
     Assigns the exchange_rate
    */
    function setExchange_rate($Exchange_rate) {
        $this->Exchange_rate=$Exchange_rate;
    }

    // Store data for a single Paypal return
	
	var $ID;
	var $Invoice;
	var $Receiver_email;
	var $Payment_status;
	var $Pending_reason;
	var $Reason_code;
	var $Payment_date;
	var $Payment_gross;
	var $Payment_fee;
	var $Txn_id;
	var $Txn_type;
	var $Parent_txn_id;
	var $First_name;
	var $Last_name;
	var $Payer_email;
	var $Payer_status;
	var $Payment_type;
	var $Notify_version;
	var $Verify_sign;
	var $Mc_currency;
	var $Payer_business_name;
	var $Payer_id;
	var $Mc_gross;
	var $Mc_fee;
	var $Settle_amount;
	var $Settle_currency;
	var $Exchange_rate;
	var $Date_added;
}

/*
* Supporting MySQL table

CREATE TABLE eZTrade_PaypalReturn(
  ID int(11) unsigned NOT NULL auto_increment,
  Invoice varchar(64) default NULL,
  Receiver_email varchar(96) NOT NULL default '',
  Payment_status varchar(17) NOT NULL default '',
  Pending_reason varchar(14) default NULL,
  Reason_code varchar(15) default NULL,
  Payment_date datetime default NULL,
  Payment_gross decimal(7,2) default NULL,
  Payment_fee decimal(7,2) default NULL,
  Txn_id varchar(17) NOT NULL default '',
  Txn_type varchar(10) NOT NULL default '',
  Parent_txn_id varchar(17) default NULL,
  First_name varchar(32) NOT NULL default '',
  Last_name varchar(32) NOT NULL default '',
  Payer_email varchar(96) NOT NULL default '',
  Payer_status varchar(10) NOT NULL default '',
  Payment_type varchar(7) NOT NULL default '',
  Notify_version decimal(2,1) NOT NULL default '0.0',
  Verify_sign varchar(128) NOT NULL default '',
  Mc_currency char(3) NOT NULL default '',
  Payer_business_name varchar(64) default NULL,
  Payer_id varchar(32) NOT NULL default '',
  Mc_gross decimal(7,2) NOT NULL default '0.00',
  Mc_fee decimal(7,2) NOT NULL default '0.00',
  Settle_amount decimal(7,2) default NULL,
  Settle_currency char(3) default NULL,
  Exchange_rate decimal(4,2) default NULL,
  Date_added datetime default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

* 
* 
*/
?>