<?php

require_once "base.php";
require_once "column.php";

/**********************************************************
 * Table class
 * 
 * This class is used to define a database table.  It holds
 * a reference to the database that the table is within.
 * It also contains an array of columns.
 *
 * First version - 12 Sep 2013 - Patrick Haston
 */

class Table extends Base
{

  public $name;
  public $type;
  public $database_id;
  public $columns; 

  public function set_from_array( $data )
  {
    parent::set_from_array( $data );
    echo "debug";
    $this->name = $data['name'];
    $this->type = $data['type'];
    $this->database_id = $data['database_id'];
  }
  
  public function create_array_from_list( $list )
  {
    if( $list == null )
    {
      return null;
    }
    
    $tables = array();
    
    $arrlength = count($list);

    for( $i=0; $i<$arrlength; $i++ )
    {
      $table = new Table();
      $table->set_from_array( $list[$i] );
      
      $tables[] = $table;
    }

    return $tables;
  }
  
  public function load_array_from_database( $connection, $database_id )
  {
    if( $database_id == null )
    {
      return null;
    }

    $sql = "select * from _table where database_id = ".$database_id;
    $result = mysqli_query( $connection, $sql );
    $tables = array();
    while($row = mysqli_fetch_array($result))
    {
      $table = new Table();
      $table->set_from_array( $row );
      $tables[] = $table;
    }

    return $tables;
  }
  
  
}
