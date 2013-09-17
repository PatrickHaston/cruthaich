<?php
include_once "config.php";

/* get_list_of_system_tables()
 * 
 * This function reads and parses the "__tables.def" file
 * that contains a list of the tables required for this application.
 * It returns this list in the form of an array.
 */
function get_list_of_system_tables()
{
  $table_list = array();
  
  $table_list_filename = __DIR__ . "/definitions/__tables.def";
  
  $string = file_get_contents( $table_list_filename );
  
  $string = utf8_encode( $string );

  $column_array = json_decode( $string, true );

  return $column_array;
}


/* get_standard_columns()
 * 
 * This function reads and parses the "__standard_columns.def" file
 * that contains a list of the columns to be added to every table.
 * It returns this list in the form of an array.
 */
function get_standard_columns()
{
  $column_array = array();
  
  $std_col_def_filename = __DIR__ . "/definitions/__standard_columns.def";
  
  $std_cols = file_get_contents( $std_col_def_filename );
  
  $std_cols = utf8_encode( $std_cols );

  $column_array = json_decode( $std_cols, true );

  return $column_array;
}


/* load_from_definition( $filename, $filetype = "standard" )
 *
 * This function reads the definition of the table for storing a class
 * from the supplied file.  There are currently two types of database
 * table: system and standard.  Standard database tables have the standard
 * columns automatically added.
 */
function load_from_definition( $filename, $filetype = "standard" )
{
  $definition = array();
  
  if( $filename == null )
  {
    return null;
  }
  $pathname = __DIR__."/definitions/".$filename;
  if( file_exists( $pathname ) )
  {
    $cols = file_get_contents( $pathname );
    $cols = utf8_encode( $cols );
    $definition = json_decode( $cols, true );
  }
  else
  {
    echo "file does not exist: ". $pathname;
    return null;
  }
 
  if( $filetype == "standard" )
  {
    $standard_columns = get_standard_columns();
    $definition = array_merge( $standard_columns, $definition );
  }
 
  return $definition;
}



/*
  if( json_last_error() != JSON_ERROR_NONE )
  {
    switch (json_last_error()) {
        case JSON_ERROR_DEPTH:
            echo ' - Maximum stack depth exceeded';
        break;
        case JSON_ERROR_STATE_MISMATCH:
            echo ' - Underflow or the modes mismatch';
        break;
        case JSON_ERROR_CTRL_CHAR:
            echo ' - Unexpected control character found';
        break;
        case JSON_ERROR_SYNTAX:
            echo ' - Syntax error, malformed JSON';
        break;
        case JSON_ERROR_UTF8:
            echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
        break;
        default:
            echo ' - Unknown error';
        break;
    }
    echo "<br/>";
  }
*/

/********************************************************************
 * construct_table_ddl( $table_name, $column_array )
 * 
 * This function takes the array of columns supplied as a parameter
 * and uses this to construct the sql script needed to create the
 * database table.
 * 
 * First version - Patrick Haston - 11 Sep 2013 
 */
function construct_table_ddl( $table_name, $column_array )
{
  $sql = "";
  
  if( $table_name == null ) 
  {
    return null;
  }
  if( $column_array == null ) 
  {
    return null;
  }
  
  foreach ($column_array as $column_name => $column_definition) {
    $column_sql = construct_column_ddl( $column_name, $column_definition );
    if( $column_sql != null ) {
      if( $sql == "" ) {
        $sql = "create table " . $table_name . " ( \n";
      } else {
        $sql = $sql . ", \n";
      }
      $sql = $sql . $column_sql;
    }
  }

  $sql = $sql . "\n); \n";
  
  return $sql;
}

/********************************************************************
 * construct_column_ddl( $column_name, $column_definition )
 * 
 * This function takes the name and definition of a single column in an array 
 * as parameters and uses them to construct the sql script needed to 
 * create this column within the database table.
 * 
 * First version - Patrick Haston - 11 Sep 2013 
 */
function construct_column_ddl( $column_name, $column_definition )
{
  $sql = "";
  
  if( $column_name == null ) return null;
  if( $column_definition == null ) return null;
  
  $datatype = $column_definition['datatype'];
  $size = $column_definition['size'];
  $default_value = $column_definition['default'];
  $primary = $column_definition['primary'];
  $nullable = $column_definition['nullable'];

  if( $datatype == null ){
    log_error( "construct_column_ddl", "datatype is not set for column " .  $column_name );
    return null;
  } else {
    switch( $datatype ) 
    {
      case "number":
        if( $size == null ) 
        {
          log_error( "construct_column_ddl", "size is not set for column " .  $column_name );
          return null;
        } 
        else 
        {
          switch( $size ) 
          {
            case "int":
              $sql = "  " . $column_name . " INT";
              break;
            case "float":
              $sql = "  " . $column_name . " DOUBLE";
              break;
            case "currency":
            case "money":
            case "decimal":
              $sql = "  " . $column_name . " DECIMAL(20,6)"; // extra precision reduces rounding errors
              break;
            default:
              break;
          }
        }
        if( $default_value != null )
        {
          switch( $default_value )
          {
            case "@now":
              $sql = $sql . " DEFAULT NOW()";
              break;
            case "@auto":
              $sql = $sql . " AUTO_INCREMENT";
              break;
            case "@user":
              break;
            default:
              $sql = $sql . " DEFAULT " . $default_value;
              break;
          }
        }
        break;
      case "text":
        if( $size > 250 )
        {
          $sql = "  " . $column_name . " TEXT(".$size.")";
        }
        else
        {
          $sql = "  " . $column_name . " VARCHAR(".$size.")";
        }
        break;
      case "date":
        switch( $size ) 
        {
          case "date":
            $sql = "  " . $column_name . " DATE";
            break;
          case "time":
            $sql = "  " . $column_name . " TIME";
            break;
          case "datetime":
          default:
            $sql = "  " . $column_name . " DATETIME";
            break;
        }
        break;
      case "image":
      case "photo":
      case "video":
      case "audio":
        $sql = "  " . $column_name . " BLOB";
        break;
      default:
        break;
    }

    if( $primary == "yes" )
    {
      $sql = $sql . " PRIMARY KEY";
    }
    
    if( $nullable == "no" )
    {
      $sql = $sql . " NOT NULL";
    }
    
  }
  
  
  return $sql;
}

