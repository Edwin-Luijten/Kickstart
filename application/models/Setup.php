<?php
class Setup extends Model{
    protected $_config;
    
    function getModules(){
		$modules = array();
		$dir = opendir('../application/modules/');
		if($dir){
		    while (false !== ($entry = readdir($dir))) {
				if ($entry != "." && $entry != "..") {
				    array_push($modules, $entry);
				}
		    }
		}
		
		$key = array_search('template', $modules, true);
		unset($modules[$key]);
		
		return $modules;
    }
	    
	function writeConfig($data){
		$this->_config['framework']['dev'] = '';
		$this->_config['framework']['localize'] = '';
		
		$this->_config['app']['name'] = $data['app_name'];
		$this->_config['app']['url'] = $data['app_url'];
		$this->_config['app']['lang'] = 'en';
		$this->_config['app']['modules'] = $data['app_modules'];
		
		$this->_config['db']['host'] = $data['db_host'];
		$this->_config['db']['dbname'] = $data['db_name'];
		$this->_config['db']['username'] = $data['db_username'];
		$this->_config['db']['password'] = $data['db_password'];
		$this->_config['db']['type'] = $data['db_type'];
		$this->_config['db']['file'] = $data['db_file'];
		
		$cache = $data['cache'];
		$modules = $data['app_modules'];
		
		if($cache != null){
		$cache = '/* remove this line to enable cache
$config[\'cache\'][\'path\'] = "../tmp/cache/";
$config[\'cache\'][\'priority\'] = 50;
remove this line to enable cache */
		';
		}else{
		$cache = '
$config[\'cache\'][\'path\'] = "../tmp/cache/"; 
$config[\'cache\'][\'priority\'] = 50;
			';
		}

		$moduleList = '';
		foreach($modules as $module){
			$moduleList .= "'{$module}',\r\n";
		}
		
		$modules = substr($moduleList, 0, -3);
		
		$file = '../config/config.php';
		
		$fh = fopen($file, 'w') or die("Can't open: {$file}");
		
		$configContent = '<?php
$config[\'framework\'][\'development_environment\'] = "'.$this->_config['framework']['dev'].'";
$config[\'framework\'][\'localize\'] = "'.$this->_config['framework']['localize'].'";
	
$config[\'app\'][\'name\'] = "'.$this->_config['app']['name'].'";
$config[\'app\'][\'url\'] = "'.$this->_config['app']['url'].'";
$config[\'app\'][\'path\'] = "";
$config[\'app\'][\'lang\'] = "'.$this->_config['app']['lang'].'";
	
$config[\'db\'][\'host\'] = "'.$this->_config['db']['host'].'";
$config[\'db\'][\'dbname\'] = "'.$this->_config['db']['dbname'].'";
$config[\'db\'][\'username\'] = "'.$this->_config['db']['username'].'";
$config[\'db\'][\'password\'] = "'.$this->_config['db']['password'].'";
$config[\'db\'][\'type\'] = "'.$this->_config['db']['type'].'";
$config[\'db\'][\'file\'] = "'.$this->_config['db']['file'].'";

'.$cache.'
	
$config[\'framework\'][\'modules\'] = array(
'.$modules.'
);
?>
	';
		
		if(fwrite($fh, $configContent)){
			fclose($fh);
			header('location: /bodyworks');
		}
		
    }
}