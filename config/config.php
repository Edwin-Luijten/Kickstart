<?php
$config['app']['name'] = "Kickstart";
$config['app']['url'] = "http://edwin-luijten.nl/sandbox/kickstart";
$config['app']['path'] = "";
$config['app']['lang'] = "en";
	
$config['db']['host'] = "";
$config['db']['dbname'] = "";
$config['db']['username'] = "";
$config['db']['password'] = "";
$config['db']['type'] = "";
$config['db']['file'] = "";

$config['cache']['path'] = "../tmp/cache/";
$config['cache']['priority'] = 50;

define("BASE_URL", $config['app']['url']);
?>