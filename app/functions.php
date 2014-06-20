<?php
function num2letter($num,$t = 0) {

	$num = explode('|', $num);
	$newArr  = array();
	foreach($num as $value) {
		$newArr[] = chr($value+64);
	}
	return implode(',', $newArr);
}
