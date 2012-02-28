<?php
$config['app']['name']	= 'Framework';
$config['app']['url']	= 'http://localhost/development/framework';
$config['app']['path']	= '';
$config['app']['lang']	= 'en';

$config['db']['host']		= 'localhost';
$config['db']['dbname']		= 'pedia';
$config['db']['username']	= 'root';
$config['db']['password']	= 'moeder13';
$config['db']['type']		= 'mysql'; //mysql/mssql/sysbase/sqlite, 
$config['db']['file']		= '../db/database.db'; //if type is sqlite specify where the databasefile is located

$config['cache']['path']	= '../tmp/cache/';
$config['cache']['priority']	= 50;
?>