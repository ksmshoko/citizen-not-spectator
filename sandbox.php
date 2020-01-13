<?php 

	//Session
	if (isset($_POST['submit'])) {

		//Cookie for gender
		setcookie('gender', $_POST['gender'], time() + 86400);
		
		session_start();

		$_SESSION['name'] = $_POST['name'];

		header('Location: index.php');
	}


?>


<!DOCTYPE html>
<html>
<head>
	<title>Sessions</title>
</head>
<body>
	<div>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
			<input type="text" name="name">
			<select name="gender">
				<option value="male">Male</option>
				<option value="female">Female</option>
			</select>
			<input type="submit" name="submit" value="Submit">
		</form>
			
	</div>

</body>
</html>