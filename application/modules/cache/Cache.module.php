<?php
/**
* Find this class for free here: http://www.codelikepandas.com
* Thanks are appreciated if you're actually using this class.
*
* @author: Pablo Albrecht
* @date: 29 January 2011, 11:45 PM
*/

class Cache{

	private $required_variables = array('caching_path','priority_required');
	
	private $caching_path = "";
	private $priority_required = 0;
	
	/**
	* 	Class constructor - automatically called when initiating an instance of the CacheHandler class.
	* 	Retrieve configuration variables to correctly setup the caching environment.
	* 	@param string|array $caching_path Absolute path to the configuration file where we can find environment related variable. OR array with required variables already pulled from the config file.
	*/
	function Cache($cache_path, $cache_priority) {
	    if(!isset($cache_path)){
		$cache_path = "../tmp/cache/";
	    }else{
		$this->caching_path = $cache_path;
	    }
	    
	    if(!isset($cache_priority)){
		$cache_priority = 0;
	    }else{
		$this->priority_required = $cache_priority;
	    }
	}

	/**
	*	Save a variable with its cache ending date in a text file (cache file).
	*	@param string $name Name of the caching file. Has to be unique since you'll pull your data from the cache engine with this variable.
	*	@param mixed $content Can be any type of variable that you want to cache.
	*	@param int $duration Time you want to keep the content cached (in SECONDS). If $duration = 0, we'll keep the file cached for ever.
	*	@return bool Simply returns true if the process has been successful.
	*/
	function cache_content($name,$content,$duration = 0) {
	
		$filename = $this->caching_path . $this->_encrypt_name($name);
		
		$content = array(
			'duration' => $duration,
			'creation' => time(),
			'content' => $content
		);
		$content = serialize($content);
		
		$rh = fopen($filename,'w+');
		fwrite($rh,$content);
		fclose($rh);
		
		return true;
	}
	
	/**
	*	Return the content previously cached with cache_content().
	*	@param string $name Has to be the same as used when calling cache_content()
	* 	@param int $priority Integer between 0 and 100 to know whether we cache the content or not.
	* 	@return bool False if the cache has expired or doesn't exist (or priority is too  low).
	*/
	function retrieve_cache($name,$priority = 0) {
	
		$filename = $this->caching_path . $this->_encrypt_name($name);
		
		if($priority < $this->priority_required)
			return false;
			
		if(!file_exists($filename))
			return false;
		
		$content = file_get_contents($filename);
		$content = unserialize($content);
		
		if($content['duration'] == 0){
			return $content;
		}elseif(time() > $content['creation']+$content['duration']){
			return false;
		}else{
		    return $content['content'];
		}
	}
	
	private $has_buffer_started = false;
	private $buffer_content = "";
	/**
	*	Start the caching buffer
	*/
	function start_buffer() {
		// we don't need to handle multiple buffers for simple content caching
		if(!$this->has_buffer_started){
			ob_start();
			$this->has_buffer_started = true;
		}
	}
	
	/**
	*	Stop the caching buffer
	*/
	function stop_buffer() {
		if($this->has_buffer_started){
			$content = ob_get_clean();
			$this->buffer_content = $content;
			$this->has_buffer_started = false;
		}
	}
		
	/**
	*	Cache the content (buffered with start_buffer() and stop_buffer())
	*	@param string $name Name of the caching file. Has to be unique since you'll pull your data from the cache engine with this variable.
	*	@param int $duration Time you want to keep the content cached. If $duration = 0, we'll keep the file cached for ever.
	*	@return bool True if the process has been successful
	*/
	function cache_buffer($name,$duration = 0) {
		// buffer has already been opened and closed
		if(!$this->has_buffer_started) {
			// simply use the method we already written earlier.
			$this->cache_content($name,$this->buffer_content,$duration);
		}
	}
	
	/**
	*	Delete a cache file. Usually not used unless you create a cache file with $duration = 0 and want to regenerate the cache.
	*	@param string $name Name of the caching file.
	*	@return bool False if file doesn't exist, true if the process has been successful.
	*/
	function delete_cache($name) {
		$filename = $this->caching_path . $this->_encrypt_name($name);
		if(!file_exists($filename))
			return false;
		else
			unlink($filename);
		return true;
	}
		
	/**
	* 	Encrypt the name of a given variable to avoid malformed files.
	*	@param string $name Cache variable name
	*	@access private
	*/
	function _encrypt_name($name) {
		return md5($name);
	}

}