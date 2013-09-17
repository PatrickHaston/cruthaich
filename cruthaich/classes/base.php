<?php

include __DIR__."/../config.php";

class Base
{

  public $_id;
  public $_created_on;
  public $_created_by;
  public $_updated_on;
  public $_updated_by;
  public $_status;
  public $_application_id;
  public $_from_version_id;
  public $_to_version_id;
  
  public $definition;
  
  public function set_from_array( $data )
  {
    if( $data == null )
    {
      // add error logging
      return;
    }
    
    $this->definition = $data;
    
    $this->_id = $data['_id'];
    $this->_created_on = $data['_created_on'];
    $this->_created_by = $data['_created_by'];
    $this->_updated_on = $data['_updated_on'];
    $this->_updated_by = $data['_updated_by'];
    $this->_status = $data['_status'];
    $this->_application_id = $data['_application_id'];
    $this->_from_version_id = $data['_from_version_id'];
    $this->_to_version_id = $data['_to_version_id'];
  }
  
  
}