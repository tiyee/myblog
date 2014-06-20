<?php
/*

 @authors tiyee <tiyee@live.com>
          you
*/
namespace controller\a;
class a extends \controller\index {

public function index() {
	//echo $this->test();
	$cache = new \library\cache();
	$cache->test();
}

}







