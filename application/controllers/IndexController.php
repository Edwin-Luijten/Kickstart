<?php
class IndexController extends Controller
{
    function beforeAction()
    {
		if(!session_id())
		{
		    session_start();
		}
    }

    function index()
    {	
		$this->set('title', 'Welcome to Kickstart');	
    }
    
    function afterAction()
    {
    	
    }
}