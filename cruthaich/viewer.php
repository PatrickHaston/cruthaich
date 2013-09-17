<?php

require_once "config.php";
require_once "classes/page.php";

/**********************************************************
 * page_viewer()
 * 
 * This function is used to display an application page.
 *
 * First version - 13 Sep 2013 - Patrick Haston
 */

function page_viewer( $page_id )
{
  if( $page_id == null )
  {
    show_error_page( "No page requested." );
    return;
  }
  $connection = get_system_connection();
  $page = new Page();
  $page->create_from_database( $page_id );
}


function show_error_page( $error_message )
{
  echo "<html>\n";
  echo "  <head>\n";
  echo "    <title>Error</title>\n";
  echo "  </head>\n";
  echo "  <body>\n";
  echo "    <h1>Error Page</h1>\n";
  echo "    <p>Oops!  Something went wrong.  Here's the message:</p>\n";
  echo "    <p>" . $error_message . "</p>\n";
  echo "  </body>\n";
  echo "</html>\n";
}

