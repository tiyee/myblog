<?php
//error_reporting(0);

class Route {
	public static  function init() {
		if(empty($_GET['c'])) {
			$controller = '\controller\index';
		} else {
			$controller = trim($_GET['c']);
			$controller = str_replace('/', '\\', $controller);
			//echo $controller;
			$controller = '\controller\\'.$controller;
		}
		if(empty($_GET['a'])) {
			$action = 'index';
		} else {
			$action = $_GET['a'];
		}

		/*$obj = new $controller;
		$obj->$action();*/
		try{
			$controller = new $controller;


		call_user_func(array($controller,$action));
	    } catch (Exception $e) {
        	//print $e->getMessage();
        	throw new Exception('import not found file :' . $class);
        	exit();
        }




	}
	static public function import($class) {
		//echo $class,'<br>';

        $filePath = self::routes($class);

        require($filePath);





	}
	private static function routes($class) {

		$class = explode('\\', $class);
        $mod = array_shift($class);
        if(0 === strpos($mod,'_')) {
        	$filePath = CORE_PATH;
        } else {
        	$filePath = APP_PATH;
        }
		$mod = ltrim($mod,'_');

		switch ($mod) {
			case 'library':
        	case 'lib':
        		$prefix = 'Library';
        		break;
        	case 'con':
        	case 'controller':
        	    $prefix = 'Controller';
        	    break;
        	case 'mod':
        	case 'model':
        	    $prefix = 'Model';
        	    break;
        	case 'func':
        	    $prefix = 'Func';
        	    break;
        	case 'cor':
        	case 'core':
        	    $prefix = 'Core';
        	    break;
        	case 'config':
        	    $prefix = 'Config';
        	    break;
        	case 'drive':
        	case 'dri':
        	case 'dr':
        	    $prefix = 'Drive';
        	    break;

        	default:
        		trigger_error($mod.' is unknown',E_USER_ERROR);
        		break;
        }
        $filePath .= '/'.$prefix.'/'.implode('/', $class).'.class.php';
       // echo '%',$filePath,'-<br>';
        return $filePath;

	}

	public static function __callStatic($func, $arguments) {
		if(0 === strpos($func,'_')) {
			$mod = '_';
		} else {
			$mod = '';
		}
		$func = strtolower($func);
        $func = self::routes($func);
        $func = strtolower($func);
		$class = ucfirst($arguments[0]);
		$class = $mod.$func.$class;

		if(method_exists($class,'getInstance')) {
			$class = call_user_func(array($class,'getInstance'));
			//$class = $class::getInstance();
		} else {
			$class = new $class;
		}

		return $class;


	}
	public static function singleton($class) {
		if(class_exists($class,false)) {
			return $class;
		} else {
			return new $class;
		}
	}

	static public function exceptionHandler($e) {
        echo get_class($e) . ' :-> ' . $e->getMessage();

    }


    static public function errorHandler($errno, $errstr) {
        echo "<b>My WARNING</b> [$errno] $errstr<br />\n";
        //echo 'there are some error in this web!';
      // throw new Exception('import not found file :' . $class);

    }
}
spl_autoload_register(array('Route', 'import'));
set_exception_handler('Route::exceptionHandler');
set_error_handler('Route::errorHandler');
