<?php
// include_once "cruthaich/tables.php"; 
//require_once "cruthaich/viewer.php";
include_once "cruthaich/classes/table.php";
include_once "cruthaich/classes/version.php";
include_once "cruthaich/generate_database.php";
/*
if( isset($_GET['p']) )
{
  $p = $_GET['p'];
} 
elsif ( isset($_POST['p']) )
{
  $p = $_GET['p'];
}
else
{
  $p = NULL;
}
*/

//page_viewer( $p );
?>

<html>
  <head>
  </head>
  <body>
    <h1>Tables</h1>
    
    <?php

      echo "<p>System Tables</p>\n";
      $system_tables = get_list_of_system_tables();
      echo '<pre>';
      print_r($system_tables);
      echo '</pre>';

      echo "<p>_application file definition</p>\n";
      $application_def = load_from_definition("_application.def", "system");
      echo '<pre>';
      print_r($application_def);
      echo '</pre>';

      echo "<p>_table file definition</p>\n";
      $table_def = load_from_definition("_table.def", "standard");
      echo '<pre>';
      print_r($table_def);
      echo '</pre>';
      
      $table_object = new Table();
      $table_object->set_from_array( $table_def );
      echo "<p>Table object class</p>\n";
      echo '<pre>';
      print_r($table_object);
      echo '</pre>';
      
      $table_ddl = construct_table_ddl( "_table", $table_def );
      echo "<p>table ddl</p>\n";
      echo '<pre>';
      print_r($table_ddl);
      echo '</pre>';

    ?>
  </body>
</html>


