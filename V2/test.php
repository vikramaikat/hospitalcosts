<!DOCTYPE html>
<html>
	<head>
		<title>authenticate.php</title>
	</head>
	<body>
		<?php
			$db = new mysqli('classroom.cs.unc.edu', 'rarora9', 'tjGHkxWAG0AIv492', 'rarora9db');
			
			if($db->connect_errno > 0) {
    			die('Unable to connect to database [' . $db->connect_error . ']');
			}
			
			function createHashSaltPair($password) {
				$salt = time() . $_SERVER['REMOTE_ADDR'];
				$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
				
				for($i = 0; $i < 32; $i++) {
					$salt = $salt . $chars[mt_rand(0, 61)];
				}
				
				$hash = hash('sha256', $salt . $password);
				return array($hash, $salt);
			}
			
			function createAccount($username, $password, $fname, $lname, $department) {
				$num = $GLOBALS['db']->query("SELECT * FROM doctor WHERE username = '" . $username . "'")->num_rows;
				
				if($num > 0) {
					print('Sorry, that username has already been taken.<br>');
					return;
				}
				
				if(strlen($password) < 8) {
					print('Sorry, your password must be at least 8 characters long.<br>');
					return;
				}
				
				if(strlen($fname) < 1 || strlen($lname) < 1) {
					print("Please enter both your first and last name.<br>");
					return;
				}
				
				$num = $GLOBALS['db']->query("SELECT * FROM department WHERE name = '" . $department . "'")->num_rows;
				
				if($num < 1) {
					print("Please enter a valid department.<br>");
					return;
				}
				
				$hashSaltPair = createHashSaltPair($password);
				
				if($GLOBALS['db']->query("INSERT INTO doctor (username, password_hash, password_salt, first_name, last_name, department_name) VALUES ('" . $username . "', '" . $hashSaltPair[0] . "', '" . $hashSaltPair[1] . "', '" . $fname . "', '" . $lname . "', '" . $department . "')")) {
					print("Account creation successful.<br>");
				} else {
					print("Account creation failed.<br>");
				}
			}
			
			function login($username, $password) {
				$num = $GLOBALS['db']->query("SELECT * FROM doctor WHERE username = '" . $username . "'")->num_rows;
				
				if($num < 1) {
					print('Invalid username or password.<br>');
					return;
				}
				
				$hash = $GLOBALS['db']->query("SELECT password_hash FROM doctor WHERE username = '" . $username . "'")->fetch_row()[0];
				$salt = $GLOBALS['db']->query("SELECT password_salt FROM doctor WHERE username = '" . $username . "'")->fetch_row()[0];
				
				if(hash('sha256', $salt . $password) == $hash) {
					print('Successfully logged in.<br>');
				} else {
					print('Invalid username or password.<br>');
				}
			}
			
			createAccount('rarora9', 'password', 'Rohan', 'Arora', 'Anesthesiology');
			login('rarora9', 'password');
		?>
	</body>
</html>
