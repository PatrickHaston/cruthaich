<?php

require_once "base.php";
require_once "region.php";

/**********************************************************
 * Template class
 * 
 * This class is used to define a template.  Templates are
 * used to create pages.  Templates contain regions.  Regions
 * are where content is added.
 *
 * First version - 14 Sep 2013 - Patrick Haston
 */
class Template extends Base
{

  public $name;
  public $html;
  
  public $regions;

  function set_from_array( $data )
  {
    parent::set_from_array( $data );
    $name = $data['name'];
    $html = $data['html'];
  }
  
  function create_array_from_list( $list, $application_id )
  {
    if( $list == null )
    {
      return null;
    }
    if( $application_id == null )
    {
      return null;
    }
    
    $templates = array();
    
    foreach ($list as $template_name => $template_html) 
    {
      $template = new Template();
      $template->name = $template_name;
      $template->html = $template_html;
      
      $templates[] = $template;
    }
    
    return $templates;
  }
  
  function load_array_from_database( $connection, $application_id )
  {
    if( $application_id == null )
    {
      return null;
    }

    $sql = "select * from _template where application_id = ".$application_id;
    $result = mysqli_query( $connection, $sql );
    $templates = array();
    while($row = mysqli_fetch_array($result))
    {
      $template = new Template();
      $template->set_from_array( $row );
      
      $templates[] = $template;
    }

    return $templates;
  }
  
  function load_from_database( $connection, $template_id )
  {
    if( $template_id == null )
    {
      return null;
    }

    $sql = "select * from _template where _id = ".$template_id;
    $result = mysqli_query( $connection, $sql );
    while($row = mysqli_fetch_array($result))
    {
      set_from_array( $row );
    }
    
    load_regions_from_database( $connection );
  }
  
  /* load_regions_from_database
   * This function loads all the regions that belong to this template
   * into the regions array.
   */
  function load_regions_from_database( $connection )
  {
    if( $_id == null )
    {
      return;
    }
    $region = new Region();
    $list = array();
    $list = $region->load_array_from_database( $_id );
    $regions = get_child_regions( $list, 0 );
  }
  
  /* create_default_template
   * This function creates a default empty template
   */
  function create_default_template()
  {
    $name = "Default Template";
    $html = "<html>\n" .
            "  <head>\n" .
            "    <title><!--[TITLE]--!></title>\n" .
            "  </head>\n" .
            "  <body>\n" .
            "<!--[REGIONS]--!>\n" .
            "  </body>\n" .
            "</html>\n";
  }
  
  /* get_html
   * This function reads all the regions and adds them to
   * the template.  This can then be passed to the page class
   * so that the page attributes and content can be added.
   */
  function get_html()
  {
    if( $html == null )
    {
      create_default_template();
    }
    if( $regions == null )
    {
      $connection = get_system_connection();
      load_regions_from_database( $connection )
      mysqli_close( $connection );
    }
    if( $regions != null )
    {
      // add the regions to the html
    }
  }
  
}
