<?php

require_once "base.php";

/**********************************************************
 * Page class
 * 
 * This class is used to define an application page.
 *
 * First version - 13 Sep 2013 - Patrick Haston
 */
class Page extends Base
{

  public $name;
  public $title;
  public $url;
  public $description;
  public $head;
  public $template_id;
  
  public $page_regions;

  function set_from_array( $data )
  {
    parent::set_from_array( $data );
    $name = $data['name'];
    $title = $data['title'];
    $url = $data['url'];
    $description = $data['description'];
    $head = $data['head'];
    $template_id = $data['template_id'];
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
    
    $pages = array();
    
    foreach ($list as $page_name => $page_array) 
    {
      $page = new Page();
      $page->name = $page_name;
      $page->title = $page_array['title'];
      $page->url = $page_array['url'];
      $page->description = $page_array['description'];
      $page->head = $page_array['head'];
      $page->template_id = $page_array['template_id'];
      
      $pages[] = $page;
    }
    
    return $pages;
  }
  
  function load_array_from_database( $connection, $application_id )
  {
    if( $application_id == null )
    {
      return null;
    }

    $sql = "select * from _page where application_id = ".$application_id;
    $result = mysqli_query( $connection, $sql );
    $pages = array();
    while($row = mysqli_fetch_array($result))
    {
      $page = new Page();
      $page->set_from_array( $row );
      
      $pages[] = $page;
    }

    return $pages;
  }
  
  function load_from_database( $connection, $page_id )
  {
    if( $page_id == null )
    {
      return null;
    }

    $sql = "select * from _page where _id = ".$page_id;
    $result = mysqli_query( $connection, $sql );
    while($row = mysqli_fetch_array($result))
    {
      set_from_array( $row );
    }
  }
  
}
