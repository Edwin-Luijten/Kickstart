<?php
class IndexController extends Controller{
    private $_db;
    
    function beforeAction(){
	if(!session_id()){
	    session_start();
	}
    }

    function index(){
	$this->_db = Database::connect();
	
	$query = $this->_db->query('SELECT name FROM piercings');
	$query->setFetchMode(PDO::FETCH_ASSOC);
	
	$test = $query->fetchAll();
	
	$this->_db = Database::disconnect();

	$this->set('title', INDEX_TITLE_TXT);	
	$this->set('test', $test);
	
    }
    
    function afterAction(){
    }
}