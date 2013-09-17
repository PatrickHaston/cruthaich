<?php

require_once "base.php";

/**********************************************************
 * Region class
 * 
 * This class is used to define a region of a template.
 *
 * First version - 14 Sep 2013 - Patrick Haston
 */
class Region extends Base
{

  public $name;
  public $css_class;
  public $before_content;
  public $after_content;
  public $parent_id;
  public $weight;
  
  public $sub_regions;

  function set_from_array( $data )
  {
    parent::set_from_array( $data );
    $name = $data['name'];
    $css_class = $data['css_class'];
    $before_content = $data['before_content'];
    $after_content = $data['after_content'];
    $parent_id = $data['parent_id'];
    $weight = $data['weight'];
  }
  
  function create_array_from_list( $list )
  {
    if( $list == null )
    {
      return null;
    }
    $regions = array();
    
    $arrlength = count($list);

    for( $i=0; $i<$arrlength; $i++ )
    {
      $region = new Region();
      $region->set_from_array( $list[$i] );
      
      $regions[] = $region;
    }
    
    return $regions;
  }
  
  function load_array_from_database( $connection, $template_id )
  {
    if( $application_id == null )
    {
      return null;
    }

    $sql = "select * from _region where template_id = ".$template_id." order by parent_id, weight";
    $result = mysqli_query( $connection, $sql );
    $regions = array();
    while($row = mysqli_fetch_array($result))
    {
      $region = new Region();
      $region->set_from_array( $row );
      
      $regions[] = $region;
    }

    return $regions;
  }
  
  function load_from_database( $connection, $region_id )
  {
    if( $region_id == null )
    {
      return null;
    }

    $sql = "select * from _region where _id = ".$region_id;
    $result = mysqli_query( $connection, $sql );
    while($row = mysqli_fetch_array($result))
    {
      set_from_array( $row );
    }
  }
  
  function get_child_regions( $list, $parent_id )
  {
    if( $region_array == null )
    {
      return null;
    }
    if( $parent_id == null )
    {
      $parent_id = 0;
    }
    
    $regions = array();
    
    $arrlength = count($list);

    for( $i=0; $i<$arrlength; $i++ )
    {
      $data = $list[$i];
      if( $data['parent_id'] == $parent_id )
      {
        $region = new Region();
        $region->set_from_array( $data );
        
        // Recursive call to find any child regions
        $region->sub_regions = get_child_regions( $region->id );
        
        $regions[] = $region;
      }
    }
    
    return $regions;
  }
  
  function get_html( $indent )
  {
    $html =  $indent."<div ".$css_class.">\n";
    if( $before_content != null )
    {
      $html = $html.$indent."  ".$before_content."\n";
    }
    $html = $html.$indent."  <!--REGION ". $name . "--!>\n";
    
    if( $sub_regions != null )
    {
      $arrlength = count($sub_regions);

      for( $i=0; $i<$arrlength; $i++ )
      {
        $html = $html . $sub_regions[i]->get_html( $indent."  " );
      }
    }
    
    if( $after_content != null )
    {
      $html = $html.$indent."  ".$after_content."\n";
    }
    $html = $html.$indent."</div>\n";
  }
  
}
