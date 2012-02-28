<?php
class Locale {
    protected $_default_lang = 'en_EN';
    protected $_locale = '../application/locale';
    protected $_dir;
    
    function __construct($localize = true, $lang = null){
	if($localize == true){
	    
	    if($lang === null || $lang == ''){
		$lang_code = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
	    }else{
		$lang_code = $lang;
	    }
	    
	    $this->_lang = $lang_code.'_'.strtoupper($lang_code);
	    $this->_dir = $this->_locale.'/'.$this->_lang.'/';
	    
	    if(!is_dir($this->_dir)){
	       $this->_dir = $this->_locale.'/'.$this->_default_lang.'/';
	    }
	    
	    if(empty($this->lang_code)){
		$this->_lang = $this->_lang;
	    }
    
	    foreach(glob($this->_dir.'*.php') as $lang_file){
		require_once($lang_file);
	    }
	}
    }
}