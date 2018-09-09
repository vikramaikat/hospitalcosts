<?php
	session_start();
	
	require_once('authenticate.php');
	
	header("Content-type:application/json");
	$test = array('prop1' => 'ABC', 'prop2' => 42, 'prop3' => 2 + 3);
	print(json_encode($test));
	exit();
?>
