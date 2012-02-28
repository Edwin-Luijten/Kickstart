<?php
class Index extends Model {
    function test(){
	
	$cache = $this->_cache->retrieve_cache('content',75);
	
	if(empty($cache['content'])){
	    $content = array(
		array(
		    'firstname' => 'edwin',
		    'lastname' => 'luijten'
		),
		array(
		    'firstname' => 'john',
		    'lastname' => 'doo'
		)
	    );
	
	    $this->_cache->cache_content('content',$content);
	}else{
	    $content = $cache['content'];
	}
	
	return $content;
    
    }
}