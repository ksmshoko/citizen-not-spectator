<?php 

	//MySQLi or PDO //PHP Data Objects (MySQLimproved)
	
	$conn = mysqli_connect('localhost', 'cns1', 'cns+T20IV', 'cns_db');

	// check the connection
	if (!$conn) {
		echo "Connection error: " . mysqli_connect_error();
	}

 ?>