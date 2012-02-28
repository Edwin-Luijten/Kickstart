<?php
    require_once (ROOT . DS . 'config' . DS . 'framework.php');

    if(file_exists(ROOT . DS . 'config' . DS . 'config.default.php')){
	require_once (ROOT . DS . 'config' . DS . 'config.default.php');
    }else if(file_exists(ROOT . DS . 'config' . DS . 'config.php')){
	require_once (ROOT . DS . 'config' . DS . 'config.php');
    }
    
    require_once (ROOT . DS . 'config' . DS . 'routing.php');
    require_once (ROOT . DS . 'library' . DS . 'shared.php');