<?php
include_once "log.php";

/*******************************************************
 * config.php
 *
 * Contains the settings for this installation
 */
 
/*******************************************************
 * Database connection details
 *
 * array(
 *   'driver' => 'mysql',
 *   'database' => 'databasename',
 *   'username' => 'username',
 *   'password' => 'password',
 *   'host' => 'localhost',
 *   'port' => 3306,
 *   'prefix' => 'myprefix_',
 *   'collation' => 'utf8_general_ci',
 * );
 */
define('DIR_BASE', dirname( dirname( __FILE__ ) ) . '/');
//$root=pathinfo($_SERVER['SCRIPT_FILENAME']);
//define ('BASE_FOLDER', basename($root['dirname']));
//define ('SITE_ROOT',    realpath(dirname(__FILE__)));
//define ('SITE_URL',    'http://'.$_SERVER['HTTP_HOST'].'/'.BASE_FOLDER);
define ('SYSTEM_ROOT', dirname( dirname( __FILE__ ) ) . '/cruthaich' );

$_database = array(
    'driver' => 'mysql',
    'database' => 'cruthaich',
    'username' => 'cruthaich',
    'password' => 'snh2000',
    'host' => 'localhost',
    'port' => 3306 
  );
  
function get_system_connection()
{
  $connection = mysqli_connect( $_database['database'], $_database['username'], $_database['password'] );
  
  return $connection;
}


