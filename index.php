<?php
//error_reporting(0);
ini_set("display_errors","On");
define('ROOT_PATH', __DIR__);
define('APP_PATH',ROOT_PATH.'/app' );
define('LOG_PATH',ROOT_PATH.'/logs' );
define('CORE_PATH',ROOT_PATH.'/framework' );
define('DIR_CACHE',ROOT_PATH.'/Cache' );
define('UPLOAD_PATH',dirname(ROOT_PATH).'/Upload' );

require APP_PATH.'/functions.php';
require APP_PATH.'/route.php';
Route::init();
