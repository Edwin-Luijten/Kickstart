<?php
class IndexController extends Controller
{
	protected $config;
	
    function beforeAction()
    {
    	global $config;
    	$this->_config = $config;
    }

    function index()
    {	
		$this->set('title', 'Welcome to Kickstart');
    }
    
    function documentation()
    {
    	$this->set('base_url', '');
    	$this->set('title', 'Kickstart docs');
    	
    }
    
    function afterAction()
    {
    	
    }
}