<?php
//
// extension/helloworld/classes/helloworlditem.php
//
// Minimal example of a database-backed storage class for the Hello World
// extension. It uses eZDB::globalDatabase() directly (the eZ Publish 2 style)
// so it works with the current Exponential Basic SQLite/MariaDB layer without
// depending on the eZ4 eZPersistentObject machinery.
//
// This is intended as a reference abstraction: a real extension would copy the
// pattern, add columns to the table, and add more finder methods.

class eZHelloWorldItem
{
    public $ID;
    public $Name;
    public $Message;
    public $Created;

    public function __construct( $row = array() )
    {
        $this->ID = isset( $row['id'] ) ? (int)$row['id'] : 0;
        $this->Name = isset( $row['name'] ) ? $row['name'] : '';
        $this->Message = isset( $row['message'] ) ? $row['message'] : '';
        $this->Created = isset( $row['created'] ) ? (int)$row['created'] : 0;
    }

    /**
     * Definition used by eZDB-based helpers and the example createTable().
     * Mirrors the eZPersistentObject definition layout where possible.
     */
    static function definition()
    {
        return array(
            'table' => 'helloworld_item',
            'fields' => array(
                'id' => array( 'datatype' => 'integer', 'required' => true ),
                'name' => array( 'datatype' => 'string', 'required' => true, 'max_length' => 255 ),
                'message' => array( 'datatype' => 'string', 'required' => true, 'max_length' => 2000 ),
                'created' => array( 'datatype' => 'integer', 'required' => true )
            ),
            'keys' => array( 'id' ),
            'increment_key' => 'id'
        );
    }

    /**
     * Create the backing table if it does not exist. Run this from the
     * module datasupplier.php or from a one-off setup script.
     */
    static function createTable()
    {
        $db = eZDB::globalDatabase();
        $table = self::definition()['table'];
        $sql = "CREATE TABLE IF NOT EXISTS $table (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(255) NOT NULL,
            message TEXT NOT NULL,
            created INTEGER NOT NULL
        )";
        return $db->query( $sql );
    }

    /**
     * Drop and recreate the table. Useful for tests.
     */
    static function resetTable()
    {
        $db = eZDB::globalDatabase();
        $table = self::definition()['table'];
        $db->query( "DROP TABLE IF EXISTS $table" );
        self::createTable();
    }

    /**
     * Create a new, unsaved item.
     */
    static function create( $name, $message )
    {
        return new eZHelloWorldItem( array(
            'id' => 0,
            'name' => $name,
            'message' => $message,
            'created' => time()
        ) );
    }

    /**
     * Fetch a single item by ID.
     */
    static function fetch( $id )
    {
        $id = (int)$id;
        $db = eZDB::globalDatabase();
        $table = self::definition()['table'];
        $rows = array();
        $db->array_query( $rows, "SELECT id, name, message, created FROM $table WHERE id = $id" );
        if ( is_array( $rows ) && count( $rows ) > 0 )
            return new eZHelloWorldItem( $rows[0] );
        return false;
    }

    /**
     * Fetch the most recent $limit items.
     */
    static function fetchList( $limit = 10 )
    {
        $limit = (int)$limit;
        $db = eZDB::globalDatabase();
        $table = self::definition()['table'];
        $rows = array();
        $db->array_query( $rows, "SELECT id, name, message, created FROM $table ORDER BY id DESC LIMIT $limit" );
        $items = array();
        if ( is_array( $rows ) )
        {
            foreach ( $rows as $row )
                $items[] = new eZHelloWorldItem( $row );
        }
        return $items;
    }

    /**
     * Persist this item. Inserts when ID is 0, updates otherwise.
     */
    function store()
    {
        $db = eZDB::globalDatabase();
        $table = self::definition()['table'];
        $name = $db->escapeString( $this->Name );
        $message = $db->escapeString( $this->Message );
        $created = (int)$this->Created;

        if ( $this->ID > 0 )
        {
            $sql = "UPDATE $table SET name = '$name', message = '$message', created = $created WHERE id = " . (int)$this->ID;
        }
        else
        {
            $sql = "INSERT INTO $table (name, message, created) VALUES ('$name', '$message', $created)";
        }

        $result = $db->query( $sql );

        if ( $this->ID == 0 && $result !== false )
        {
            $newId = self::lastInsertId( $db );
            if ( $newId !== false )
                $this->ID = (int)$newId;
        }

        return $result;
    }

    /**
     * Return the last auto-increment ID from the database layer.
     * SQLite3 and MySQL are handled.
     */
    static function lastInsertId( $db )
    {
        if ( $db->Type === 'sqlite' )
        {
            $rows = array();
            $db->array_query( $rows, 'SELECT last_insert_rowid() AS id' );
            if ( is_array( $rows ) && count( $rows ) > 0 )
                return (int)$rows[0]['id'];
            return false;
        }

        if ( $db->Type === 'mysql' )
        {
            $rows = array();
            $db->array_query( $rows, 'SELECT LAST_INSERT_ID() AS id' );
            if ( is_array( $rows ) && count( $rows ) > 0 )
                return (int)$rows[0]['id'];
            return false;
        }

        return false;
    }

    /**
     * Search by name or message.
     */
    static function fetchBySearch( $term, $limit = 10 )
    {
        $limit = (int)$limit;
        $db = eZDB::globalDatabase();
        $table = self::definition()['table'];
        $term = $db->escapeString( $term );
        $rows = array();
        $db->array_query( $rows, "SELECT id, name, message, created FROM $table WHERE name LIKE '%$term%' OR message LIKE '%$term%' ORDER BY id DESC LIMIT $limit" );
        $items = array();
        if ( is_array( $rows ) )
        {
            foreach ( $rows as $row )
                $items[] = new eZHelloWorldItem( $row );
        }
        return $items;
    }

    /**
     * Remove an item by ID.
     */
    static function removeById( $id )
    {
        $id = (int)$id;
        $db = eZDB::globalDatabase();
        $table = self::definition()['table'];
        return $db->query( "DELETE FROM $table WHERE id = $id" );
    }

    /**
     * Return a human-readable created date.
     */
    function createdDate()
    {
        return date( 'Y-m-d H:i:s', (int)$this->Created );
    }
}
