<?php
namespace library
final class request {
	private static $_instance;
    private $get = array();
	private $post = array();
	private $cookie = array();
	private $request = array();
	private $files = array();
	private $server = array();
	private function __construct(){}
	private function __clone(){}
	public static function getInstance() {
		if(! (self::$_instance instanceof self) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __get($key) {
		$key = strtolower($key);
		$value = array();
		switch ($key) {
			case 'get':
				$value = $_GET;
				break;
			case 'post':
				$value = $_POST;
				break;
			case 'cookie':
				$value = $_COOKIE;
				break;
			case 'request':
				$value = $_REQUEST;
				break;
			case 'files':
				$value = $_FILES;
				break;
			case 'server':
				$value = $_SERVER;
				break;


			default:
				trigger_error(' Unavailable Request');
				break;
		}
		return self::filter($value);

	}



  	private static function filter($data) {
    	if (is_array($data)) {
	  		foreach ($data as $key => $value) {
				unset($data[$key]);

	    		$data[self::filter($key)] = self::filter($value);
	  		}
		} else {
	  		$data = htmlspecialchars($data, ENT_COMPAT);
		}

		return $data;
	}
	public function test() {
		echo 'request test';
	}
}
