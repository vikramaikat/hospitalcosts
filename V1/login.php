<?php
	session_start();
	
	$db = new mysqli('classroom.cs.unc.edu', 'rarora9', 'tjGHkxWAG0AIv492', 'rarora9db');
			
	if($db->connect_errno > 0) {
    	die('Unable to connect to database [' . $db->connect_error . ']');
	}
	
	function login($username, $password) {
		$num = $GLOBALS['db']->query("SELECT * FROM doctor WHERE username = '" . $username . "'")->num_rows;
				
		if($num < 1) {
			return false;
		}
				
		$hash = $GLOBALS['db']->query("SELECT password_hash FROM doctor WHERE username = '" . $username . "'")->fetch_row()[0];
		$salt = $GLOBALS['db']->query("SELECT password_salt FROM doctor WHERE username = '" . $username . "'")->fetch_row()[0];
				
		if(hash('sha256', $salt . $password) === $hash) {
			return true;
		} else {
			return false;
		}
	}
	
	$username = $_GET['username'];
	$password = $_GET['password'];
	
	if(login($username, $password)) {
		header('Content-type:application/json');
		
		// Generate authorization cookie
		$_SESSION['username'] = $username;
		$_SESSION['authsalt'] = time();
		
		$auth_cookie_val = md5($_SESSION['username'] . $_SERVER['REMOTE_ADDR'] . $_SESSION['authsalt']);
		
		setcookie('ANESTHETIC_COSTS_AUTH', $auth_cookie_val, 0, '/', '.cs.unc.edu', true);
		
		print(json_encode(true));
		
	} else {
		unset($_SESSION['username']);
		unset($_SESSION['authsalt']);
		
		header('HTTP/1.1 401 Unauthorized');
		header('Content-type: application/json');
		print(json_encode(false));
	}
?>
