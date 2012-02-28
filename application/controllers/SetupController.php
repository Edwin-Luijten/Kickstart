<?php
class SetupController extends Controller{
    function beforeAction(){
	if(!session_id()){
	    session_start();
	}
    }
    
    function index(){
		$modules = $this->Setup->getModules();
		
		$this->set('title', 'Setup Framework');
		$this->set('modules', $modules);
    }
    
    function save(){
		$this->Setup->writeConfig($_POST);
		
    }
    function afterAction(){
	
    }

}