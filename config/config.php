<?php
$config['framework']['development_environment'] = "";
$config['framework']['localize'] = "";
	
$config['app']['name'] = "Bodyworks";
$config['app']['url'] = "localhost/bodyworks";
$config['app']['path'] = "";
$config['app']['lang'] = "en";
	
$config['db']['host'] = "localhost";
$config['db']['dbname'] = "bodyworks";
$config['db']['username'] = "root";
$config['db']['password'] = "moeder13";
$config['db']['type'] = "Mysql";
$config['db']['file'] = "";

/* remove this line to enable cache
$config['cache']['path'] = "../tmp/cache/";
$config['cache']['priority'] = 50;
remove this line to enable cache */
		
	
$config['framework']['modules'] = array(
'Cache',
'Database',
'Mailer'
);
?>
	