<?php
/**
 *@author: Edwin Luijten
 *@package: Framework
 *@filesource: framework/library/
 *Description: Contains functions to fire up te framework and functions for use elsewhere in the framework
 *Included in: Framework/library/bootstrap.php
**/
global $config;

/** Check if environment is development and display errors **/
function setReporting(){
    global $config;
    
    if($config['framework']['development_environment'] == true){
		error_reporting(E_ALL);
		ini_set('display_errors','On');
    }else{
		error_reporting(E_ALL);
		ini_set('display_errors','Off');
		ini_set('log_errors', 'On');
		ini_set('error_log', ROOT.DS.'tmp'.DS.'logs'.DS.'error.log');
    }
}
/**
 *Check if framework is configured
**/
function is_configured(){
    global $url;
    
    if($url == 'setup' || $url == 'setup/save'){
	
    }elseif(file_exists('../config/config.default.php')){
		header('location: setup');
    }
}
/**
 *Check for Magic Quotes and remove them
 *@param: array
 *@return: array
**/
function stripSlashesDeep($value){
    $value = is_array($value) ? array_map('stripSlashesDeep', $value) : stripslashes($value);
    
    return $value;
}

/**
 *Removes magic quotes
 *@return: void
**/
function removeMagicQuotes(){
    if(get_magic_quotes_gpc()){
		$_GET = stripSlashesDeep($_GET);
		$_POST = stripSlashesDeep($_POST);
		$_COOKIE = stripSlashesDeep($_COOKIE);
    }
}

/**
 *Check register globals and remove them
 *@return: void
**/
function unregisterGlobals(){
    if (ini_get('register_globals')){
        $array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
        foreach($array as $value){
            foreach($GLOBALS[$value] as $key => $var){
                if($var === $GLOBALS[$key]){
                    unset($GLOBALS[$key]);
                }
            }
        }
    }
}

/**
 *Secondary Call Function
 *@param: controller [string], action, [string], queryString [array], render [int]
 *@return: value of callback or false
**/
function performAction($controller,$action,$queryString = null,$render = 0){
    $controllerName = ucfirst($controller).'Controller';
    $dispatch = new $controllerName($controller,$action);
    $dispatch->render = $render;
    
    return call_user_func_array(array($dispatch,$action),$queryString);
}

/** Routing **/
function routeURL($url){
    global $routing;

    foreach($routing as $pattern => $result){
		if(preg_match($pattern, $url)){
		    return preg_replace($pattern, $result, $url);
		}
    }

    return ($url);
}

/**
 *Get an url segment
 *@param int
 *@return string [name of requested url segment]
**/
function getSegment($segmentPart){
    //Full path
    $path = trim($_SERVER['REQUEST_URI'], '/');
    
    //Create array of segments from full path
    $segment = explode('/', $path);
    
    //Check if $segment[$segmentPart] is set else it gives undefined offset with no segments in the url
    if(isset($segment[$segmentPart])){
       return $segment[$segmentPart]; 
    }      
}
    
/**
 *Get current page url
 *@param none
 *@return string [current page url]
**/ 
function curPageURL(){
    $pageURL = 'http';
    if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
    	$pageURL .= "://";
    if($_SERVER["SERVER_PORT"] != "80"){
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    }else{
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
    
    return $pageURL;
}

/**
 *Generate a random alpha numeric number of a given length
 *@param int
 *@return alpha numeric string of given length
 **/
function randomAlphaNum($length){
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
    // Length of character list
    $chars_length = (strlen($chars) - 1);

    // Start our string
    $string = $chars{rand(0, $chars_length)};
   
    // Generate random string
    for ($i = 1; $i < $length; $i = strlen($string)){
        // Grab a random character from our list
        $r = $chars{rand(0, $chars_length)};
       
        // Make sure the same two characters don't appear next to each other
        if ($r != $string{$i - 1}) $string .=  $r;
    }
   
    return $string;
}

/** Main Call Function **/
function callHook(){
    global $url;
    global $default;
    global $config;

    $queryString = array();

    if($url === FALSE){
		$controller = $default['controller'];
		$action = $default['action'];
    }else{
		$url = routeURL($url);
		$urlArray = array();
		$urlArray = explode("/",$url);
		$controller = $urlArray[0];
		array_shift($urlArray);
		if (isset($urlArray[0])){
		    $action = $urlArray[0];
		    array_shift($urlArray);
		} else {
		    $action = 'index'; // Default Action
		}
		$queryString = $urlArray;
    }
    
    $controllerName = ucfirst($controller).'Controller';

    $dispatch = new $controllerName($controller,$action);
    
    if((int)method_exists($controllerName, $action)){
		call_user_func_array(array($dispatch,"beforeAction"),$queryString);
		call_user_func_array(array($dispatch,$action),$queryString);
		call_user_func_array(array($dispatch,"afterAction"),$queryString);
    }else{
	/* Error Generation Code Here */
    }
}


/** Autoload any classes/modules that are required **/
function __autoload($className){
    global $config;
    
    if(file_exists(ROOT . DS . 'library' . DS . $className . '.class.php')){
		require_once(ROOT . DS . 'library' . DS . $className . '.class.php');
    }elseif(file_exists(ROOT . DS . 'application' . DS . 'controllers' . DS . $className . '.php')){
		require_once(ROOT . DS . 'application' . DS . 'controllers' . DS . $className . '.php');
    }elseif(file_exists(ROOT . DS . 'application' . DS . 'models' . DS . $className . '.php')){
		require_once(ROOT . DS . 'application' . DS . 'models' . DS . $className . '.php');
    }else{
	/* Error Generation Code Here */
    }
    
    foreach($config['framework']['modules']  as $module){
		if(file_exists(ROOT . DS . 'application/modules' . DS . $module . DS . $module . '.module.php')){
		    require_once(ROOT . DS . 'application/modules' . DS . $module . DS . $module . '.module.php');	
		}
    }
}

/** GZip Output **/
function gzipOutput(){
    $ua = $_SERVER['HTTP_USER_AGENT'];

    if (0 !== strpos($ua, 'Mozilla/4.0 (compatible; MSIE ') || false !== strpos($ua, 'Opera')){
        return false;
    }

    $version = (float)substr($ua, 30); 
    return($version < 6 || ($version == 6  && false === strpos($ua, 'SV1')));
}

/** Get Required Files **/
gzipOutput() || ob_start("ob_gzhandler");

setReporting();

if(isset($url)){

}else{
    $url = FALSE;
}

new Locale($config['framework']['localize'], $config['app']['lang']);
removeMagicQuotes();
unregisterGlobals();
is_configured();
callHook();
?>