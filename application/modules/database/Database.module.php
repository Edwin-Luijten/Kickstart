<?php
class Database
{
    protected $host;
    protected $name;
    protected $user;
    protected $pass;
    protected $type;
    
    public function connect()
    {
		global $config;
		
		$this->host = $config['db']['host'];
		$this->name = $config['db']['dbname'];
		$this->user = $config['db']['username'];
		$this->pass = $config['db']['password'];
		$this->file = $config['db']['file'];
		$this->type = $config['db']['type'];
	
		if($this->type == 'mysql' || $this->type == 'mssql' || $this->type == 'sysbase')
		{
		    try
		    {
				return  new PDO("{$this->type}:host={$this->host};dbname={$this->name}", $this->user, $this->pass);
		    }
		    catch(PDOException $e)
		    {
				print "Error!: " . $e->getMessage() . "<br/>";
				die();
		    } 
		}
		elseif($this->type == 'sqlite')
		{
		    try
		    {
				return new PDO("sqlite:{$this->file}");
		    }
		    catch(PDOException $e)
		    {
				print "Error!: " . $e->getMessage() . "<br/>";
				die();
		    }
		}
    }
    
    public static function disconnect()
    {
		return null;
    }

}