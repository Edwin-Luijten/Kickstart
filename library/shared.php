<?php
/**
 * @author: Edwin Luijten
 * @package: Framework
 * @filesource: framework/library/
 * Description: Contains functions to fire up te framework and functions for use elsewhere in the framework
 * Included in: Kickstart/library/shared.php
**/
global $config;
$magic_quotes = FALSE;

/** Check if environment is development and display errors **/
function setReporting()
{
    global $config;
    
    if ($config['framework']['development_environment'] == true)
    {
		error_reporting(E_ALL);
		ini_set('display_errors','On');
    }
    else
    {
		error_reporting(E_ALL);
		ini_set('display_errors','Off');
		ini_set('log_errors', 'On');
		ini_set('error_log', ROOT.DS.'tmp'.DS.'logs'.DS.'error.log');
    }
}

/**
 * Check for Magic Quotes and remove them
 * @param: array
 * @return: array
**/
function stripSlashesDeep($value)
{
	
    $value = is_array($value) ? array_map('stripSlashesDeep', $value) : stripslashes($value);
    
    return $value;
}

/**
 * Check register globals and remove them
 * @return: void
**/
function unregisterGlobals()
{
	if (isset($_REQUEST['GLOBALS']) OR isset($_FILES['GLOBALS']))
	{
		// Prevent malicious GLOBALS overload attack
		echo "Global variable overload attack detected! Request aborted.\n";

		// Exit with an error status
		exit(1);
	}

	// Get the variable names of all globals
	$global_variables = array_keys($GLOBALS);

	// Remove the standard global variables from the list
	$global_variables = array_diff($global_variables, array(
		'_COOKIE',
		'_ENV',
		'_GET',
		'_FILES',
		'_POST',
		'_REQUEST',
		'_SERVER',
		'_SESSION',
		'GLOBALS',
	));

	foreach ($global_variables as $name)
	{
		// Unset the global variable, effectively disabling register_globals
		unset($GLOBALS[$name]);
	}
}

/**
 * Recursively sanitizes an input variable:
 *
 * - Strips slashes if magic quotes are enabled
 * - Normalizes all newlines to LF
 * 
 * @param   mixed  any variable
 * @return  mixed  sanitized variable
 */
function sanitize($value)
{
	if (is_array($value) OR is_object($value))
	{
		foreach ($value as $key => $val)
		{
			// Recursively clean each value
			$value[$key] = sanitize($val);
		}
	}
	elseif (is_string($value))
	{
		if (Kohana::$magic_quotes === TRUE)
		{
			// Remove slashes added by magic quotes
			$value = stripslashes($value);
		}

		if (strpos($value, "\r") !== FALSE)
		{
			// Standardize newlines
			$value = str_replace(array("\r\n", "\r"), "\n", $value);
		}
	}

	return $value;
}
/**
 *Secondary Call Function
 *@param: controller [string], action, [string], queryString [array], render [int]
 *@return: value of callback or false
**/
function performAction($controller,$action,$queryString = null,$render = 0)
{
    $controllerName = ucfirst($controller).'Controller';
    $dispatch = new $controllerName($controller,$action);
    $dispatch->render = $render;
    
    return call_user_func_array(array($dispatch,$action),$queryString);
}

/** Routing **/
function routeURL($url)
{
    global $routing;

    foreach ($routing as $pattern => $result)
    {
		if (preg_match($pattern, $url))
		{
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
function getSegment($segmentPart)
{
    //Full path
    $path = trim($_SERVER['REQUEST_URI'], '/');
    
    //Create array of segments from full path
    $segment = explode('/', $path);
    
    //Check if $segment[$segmentPart] is set else it gives undefined offset with no segments in the url
    if (isset($segment[$segmentPart]))
    {
       return $segment[$segmentPart]; 
    }      
}
    
/**
 *Get current page url
 *@param none
 *@return string [current page url]
**/ 
function curPageURL()
{
    $pageURL = 'http';
    
    if ($_SERVER["HTTPS"] == "on") {
    	$pageURL .= "s";
    }
    
    $pageURL .= "://";
    
    if ($_SERVER["SERVER_PORT"] != "80")
    {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    }
    else
    {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
    
    return $pageURL;
}

/**
 *Generate a random alpha numeric number of a given length
 *@param int
 *@return alpha numeric string of given length
 **/
function randomAlphaNum($length)
{
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
    // Length of character list
    $chars_length = (strlen($chars) - 1);

    // Start our string
    $string = $chars{rand(0, $chars_length)};
   
    // Generate random string
    for ($i = 1; $i < $length; $i = strlen($string))
    {
        // Grab a random character from our list
        $r = $chars{rand(0, $chars_length)};
       
        // Make sure the same two characters don't appear next to each other
        if ($r != $string{$i - 1}) $string .=  $r;
    }
   
    return $string;
}

function errorPage($err)
{
	if ((int)$err === 404)
	{
		$error_header = "HTTP/1.1 404 Not Found";
	}
	elseif ((int)$err === 403)
	{
		$error_header = "HTTP/1.1 403 Forbidden";
	}
	elseif ((int)$err === 301)
	{
		$error_header = "HTTP/1.1 301 Moved Permanently";
	}
	elseif ((int)$err === 500)
	{
		$error_header = "HTTP/1.1 500 Internal Server Error";
	}
	
	header($error_header);
	include('../public/'.$err.'.html');
	exit;
}

/** Main Call Function **/
function callHook()
{
    global $url;
    global $default;
    global $config;

    $queryString = array();

    if ($url === FALSE)
    {
		$controller = $default['controller'];
		$action = $default['action'];
    }
    else
    {
		$url = routeURL($url);
		$urlArray = array();
		$urlArray = explode("/",$url);
		$controller = $urlArray[0];
		
		array_shift($urlArray);
		
		if (isset($urlArray[0]))
		{
		    $action = $urlArray[0];
		    array_shift($urlArray);
		} 
		else 
		{
		    $action = 'index'; // Default Action
		}
		
		$queryString = $urlArray;
    }
    
    $controllerName = ucfirst($controller).'Controller';

	if (class_exists($controllerName))
	{
	    if ((int)method_exists($controllerName, $action))
	    {
	    	$dispatch = new $controllerName($controller, $action);
	    	
			call_user_func_array(array($dispatch,"beforeAction"),$queryString);
			call_user_func_array(array($dispatch,$action),$queryString);
			call_user_func_array(array($dispatch,"afterAction"),$queryString);
	    }
	    else
	    {
			/* Error Generation Code Here */
			errorPage(404);
	    }
	}
	else {
		{
			/* Error Generation Code Here */
			echo 'Class does not exists';
			exit;		
		}
	}
}


/** Autoload any classes/modules that are required **/
function __autoload($className)
{
    global $config;
    
    if (file_exists(ROOT . DS . 'library' . DS . $className . '.class.php'))
    {
		require_once(ROOT . DS . 'library' . DS . $className . '.class.php');
    }
    elseif (file_exists(ROOT . DS . 'application' . DS . 'controllers' . DS . $className . '.php'))
    {
		require_once(ROOT . DS . 'application' . DS . 'controllers' . DS . $className . '.php');
    }
    elseif (file_exists(ROOT . DS . 'application' . DS . 'models' . DS . $className . '.php'))
    {
		require_once(ROOT . DS . 'application' . DS . 'models' . DS . $className . '.php');
    }
    else
    {
		/* Error Generation Code Here */
		errorPage(404);	
    }
    
    foreach($config['framework']['modules']  as $module)
    {
		if (file_exists(ROOT . DS . 'application/modules' . DS . strtolower($module) . DS . $module . '.module.php'))
		{
		    require_once(ROOT . DS . 'application/modules' . DS . strtolower($module) . DS . $module . '.module.php');	
		}
		else
		{
			echo 'Module '. $module .' does not exist.';	
		}
    }
}

/** GZip Output **/
function gzipOutput()
{
    $ua = $_SERVER['HTTP_USER_AGENT'];

    if (0 !== strpos($ua, 'Mozilla/4.0 (compatible; MSIE ') || false !== strpos($ua, 'Opera'))
    {
        return false;
    }

    $version = (float)substr($ua, 30); 
    return($version < 6 || ($version == 6  && false === strpos($ua, 'SV1')));
}

/** Get Required Files **/
gzipOutput() || ob_start("ob_gzhandler");

setReporting();

if(isset($url))
{

}
else{
    $url = FALSE;
}

new Locale($config['framework']['localize'], $config['app']['lang']);

if (get_magic_quotes_gpc())
{
	// Sanitize all request variables
	$_GET    = sanitize($_GET);
	$_POST   = sanitize($_POST);
	$_COOKIE = sanitize($_COOKIE);
}

if (ini_get('register_globals'))
{
	// Reverse the effects of register_globals
	unregisterGlobals();
}	
	

callHook();
?>