<?php
class Model
{
    protected $_model;
    protected $_cache;
    
    function __construct()
    {
		global $config;
		
		if(isset($config['cache']))
		{
			$this->_cache = new Cache($config['cache']['path'], $config['cache']['priority']);
		}
		
		$this->_model = get_class($this);
    }

    function __destruct(){
    }
    
}
