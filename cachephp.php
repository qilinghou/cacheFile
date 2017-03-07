<?php
class cache{
	private $cache_dir;   
	private $expireTime=180;
	function __construct($cache_dirname){   
	if(!@is_dir($cache_dirname)){  
		if(!@mkdir($cache_dirname,0777)){  
			$this->warn('缓存文件不存在而且不能创建,需要手动创建.');   
			return false;   
		} 
	}   
	$this->cache_dir = $cache_dirname;   
	}   
function get_url() {   
if (!isset($_SERVER['REQUEST_URI'])) {   
$url = $_SERVER['REQUEST_URI'];   
}else{   
 $url = $_SERVER['SCRIPT_NAME'];   
 $url .= (!emptyempty($_SERVER['QUERY_STRING'])) ? '?' . $_SERVER['QUERY_STRING'] : '';   
 }   
  
 return $url;   
}   
 
 function warn($errorstring){   
 echo "<b><font color='red'>发生错误:<pre>".$errorstring."</pre></font></b>";   
}   
    
 function cache_page($pageurl,$pagedata){   
 if(!$fso=fopen($pageurl,'w')){   
 $this->warns('无法打开缓存文件.');   
 return false;
 }  
 if(!flock($fso,LOCK_EX)){   
 $this->warns('无法锁定缓存文件.');   
 return false;   
 }   
if(!fwrite($fso,$pagedata)){  
 $this->warns('无法写入缓存文件.');  
 return false;   
 }   
 flock($fso,LOCK_UN);   
 fclose($fso);   
 return true;     
} 
function display_cache($cacheFile){   
 if(!file_exists($cacheFile)){   
$this->warn('无法读取缓存文件.');   
return false;   
 }   
  echo '读取缓存文件:'.$cacheFile;   
$fso = fopen($cacheFile, 'r');   
$data = fread($fso, filesize($cacheFile));   
 fclose($fso);   
return $data;   
}  
    
 function readData($cacheFile='default_cache.txt'){   
 $cacheFile = $this->cache_dir."/".$cacheFile;   
 if(file_exists($cacheFile)&filemtime($cacheFile)>(time()-$this->expireTime)){   
$data=$this->display_cache($cacheFile);   
 }else{   
 $data="from here wo can get it from mysql database,update time is <b>".date('l dS \of F Y h:i:s A')."</b>,过期时间是:".date('l dS \of F Y h:i:s A',time()+$this->expireTime)."----------";   
 $this->cache_page($cacheFile,$data);   
 }  
 return $data;   
 }

?>