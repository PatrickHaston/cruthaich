<?php

require_once "base.php";

/**********************************************************
 * Column class
 * 
 * This class is used to define a database colunn.  It holds
 * a reference to the database table that the column is within.
 *
 * First version - 13 Sep 2013 - Patrick Haston
 */
class Column extends Base
{

  public $name;
  public $datatype;
  public $table_id;
  public $size;
  public $default;
  public $primary;

  function set_from_array( $data )
  {
    parent::set_from_array( $data );
    $name = $data['name'];
    $table_id = $data['table_id'];
    $datatype = $data['datatype'];
    $size = $data['size'];
    $default = $data['default'];
    $primary = $data['primary'];
  }
  
  function create_array_from_list( $list, $table_id )
  {
    if( $list == null )
    {
      return null;
    }
    if( $table_id == null )
    {
      return null;
    }
    
    $columns = array();
    
    foreach ($list as $column_name => $column_array) 
    {
      $column = new Column();
      $column->name = $column_name;
      $column->table_id = $column_array['table_id'];
      $column->datatype = $column_array['datatype'];
      $column->size = $column_array['size'];
      $column->default = $column_array['default'];
      $column->primary = $column_array['primary'];
      
      $columns[] = $column;
    }
    
    return $columns;
  }
  
  function load_array_from_database( $connection, $table_id )
  {
    if( $table_id == null )
    {
      return null;
    }

    $sql = "select * from _column where table_id = ".$table_id;
    $result = mysqli_query( $connection, $sql );
    $tables = array();
    while($row = mysqli_fetch_array($result))
    {
      $column = new Column();
      $column->set_from_array( $row );
      
      $columns[] = $column;
    }

    return $columns;
  }
  
}
