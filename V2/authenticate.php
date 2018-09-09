<?php
if(!isset($_COOKIE['ANESTHETIC_COSTS_AUTH']) || ($_COOKIE['ANESTHETIC_COSTS_AUTH'] != md5($_SESSION['username'] . $_SERVER['REMOTE_ADDR'] . $_SESSION['authsalt']))) {
	header('HTTP/1.1 401 Unauthorized');
	exit();
}
