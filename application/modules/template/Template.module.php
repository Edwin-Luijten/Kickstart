<?php
/**
 *@author: Edwin Luijten
 *@package: Framework
 *@filesource: framework/library/
 *Description: Simple template class to pass content to the view
 *Called in: Framework/library/controller.class.php
**/
class Template
{	
    protected $variables = array();
    protected $_controller;
    protected $_action;
    
    function __construct($controller, $action)
    {
		$this->_controller = $controller;
		$this->_action = $action;
    }

    /** Set variables **/
    function set($name, $value)
    {
		$this->variables[$name] = $value;
    }

    /** Display template **/
    function render($doNotRenderHeader = 0)
    {
		extract($this->variables);
	
		if($doNotRenderHeader == 0)
		{	
	    	if(file_exists(ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'header.tpl'))
	    	{
				include (ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'header.tpl');
	    	}
	    	else
	    	{
				include (ROOT . DS . 'application' . DS . 'views' . DS . 'header.tpl');
	    	}
		}

		if(file_exists(ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . $this->_action . '.tpl'))
		{
	    	include (ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . $this->_action . '.tpl');		 
		}
		
		if($doNotRenderHeader == 0)
		{
	    	if(file_exists(ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'footer.tpl')) 
	    	{
				include (ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'footer.tpl');
	    	}
	    	else
	    	{
				include (ROOT . DS . 'application' . DS . 'views' . DS . 'footer.tpl');
	    	}
		}
    }

}