<?php 
	session_start();

	//if the session variables are not set, set them 
	if (!isset($_SESSION['user_id'])) {
		if (isset($_COOKIE['username']) && isset($_COOKIE['user_id'])) {
			$_SESSION['user_id'] = $_COOKIE['user_id'];
			$_SESSION['username'] = $_COOKIE['username'];
		}
	}
 ?>