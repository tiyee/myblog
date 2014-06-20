<?php
namespace library;
class cache {
	private $expire = 86400;
	public function test() {
		echo 'cache test';
	}

	public function get($key) {
		!file_exists(DIR_CACHE) && false === mkdir(DIR_CACHE,0777) && exit('create dir error!');
		$hash_dir=DIR_CACHE.'/'.substr(md5($key), 0, 1);
		$files = glob($hash_dir .'/'. 'cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.*');

		if ($files) {
			$cache = file_get_contents($files[0]);

			$data = unserialize($cache);

			foreach ($files as $file) {
				$time = substr(strrchr($file, '.'), 1);

      			if ($time < time()) {
					if (file_exists($file)) {
						unlink($file);
					}
      			}
    		}

			return $data;
		}
	}

	public function log($str){
		$file = DIR_CACHE.'/log';
		/*$handle = fopen($file, 'a+');

    	fwrite($handle, $str);

    	fclose($handle);*/
    	file_put_contents($file, $str);


	}
   /**
    * cache
    * @param [type] $key    cache name
    * @param [type] $value  cache value
    * @param string $expire time()
    */
  	public function set($key, $value,$expire = '') {
  		if($expire === '') {
  			$expire = $this->expire;
  		} elseif($expire === 0) {
  			$expire = $this->expire + 3600*24*365;
  		} else {
  			$expire = (int)$expire;
  		}
    	$this->delete($key);
		$hash_dir=DIR_CACHE.'/'.substr(md5($key), 0, 1);
		if (!is_dir($hash_dir)){
			mkdir($hash_dir,0777,true);
		}
		$file =$hash_dir.'/cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.' . (time() + $expire);
		$handle = fopen($file, 'w');

    	fwrite($handle, serialize($value));

    	fclose($handle);
  	}

  	public function delete($key) {
  		$hash_dir=DIR_CACHE.'/'.substr(md5($key), 0, 1);

		$files = glob($hash_dir.'/'. 'cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.*');

		if ($files) {
    		foreach ($files as $file) {
      			if (file_exists($file)) {
					unlink($file);
				}
    		}
		}
  	}

  	public function tokenPut($key,$sCode) {
      $hash_dir=DIR_CACHE.'/token/'.substr(md5($key), 0, 2);
      if (!is_dir($hash_dir)){
          mkdir($hash_dir,0777,true);
      }
      $path = $hash_dir.'/'.$key;
      file_put_contents($path, $sCode);


    }
    public function fileCheck($key) {
       $hash_dir=DIR_CACHE.'/token/'.substr(md5($key), 0, 2);
       $path = $hash_dir.'/'.$key;
       if(file_exists($path)) {
        return file_get_contents($path);
       } else {
        return false;
       }
    }
    public function fileDel($key) {
      $hash_dir=DIR_CACHE.'/token/'.substr(md5($key), 0, 2);
       $path = $hash_dir.'/'.$key;
       if(file_exists($path)) {
        unlink($path);
       }


    }

}
?>
