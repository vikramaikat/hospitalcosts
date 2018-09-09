<?php
	session_start();
	
	require_once('authenticate.php');
	
	$db = new mysqli('classroom.cs.unc.edu', 'rarora9', 'tjGHkxWAG0AIv492', 'rarora9db');
			
	if($db->connect_errno > 0) {
    	die('Unable to connect to database [' . $db->connect_error . ']');
	}
	
	$drugs = $db->query("SELECT * FROM drug");
	$currentRow = $drugs->fetch_row();
	$response = array();
	
	while($currentRow) {
		$id = $currentRow[0];
		$name = $currentRow[1];
		$isInhalable = $currentRow[2];
		$category = $currentRow[3];
		$extraInfo;
		
		if($isInhalable == "0") {
			$nonInhalable = $db->query("SELECT * FROM non_inhalable WHERE drug_id =" . $id)->fetch_row();
			$extraInfo = array('unitCost' => $nonInhalable[1]);
		} else {
			$inhalable = $db->query("SELECT * FROM inhalable WHERE drug_id =" . $id)->fetch_row();
			$extraInfo = array('costPerMl' => $inhalable[1], 'constant' => $inhalable[2]);			
		}
		
		$tempRow = array('id' => $currentRow[0], 'name' => $currentRow[1], 'isInhalable' => $currentRow[2], 'category' => $currentRow[3]);
		$tempRow += $extraInfo;
		
		array_push($response, $tempRow);
		$currentRow = $drugs->fetch_row();
	}
	
	header("Content-type:application/json");
	print(json_encode($response));
	exit();
?>
