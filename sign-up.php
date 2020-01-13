<?php 
	session_start();

	//Insert the page header
	$page_title = 'Sign Up';

	include('config/db_connect.php');

	$errors = array('username_err' => '', 'password1_err' => '', 'password2_err' => '', 'username_password_err' => '', 'verify_err' => '');
	$user_name = $user_password = $user_password2 = $verify = '';


	if (isset($_POST['submit'])) {

		//Data validation

		//check username
		if (empty($_POST['username'])) {
			$errors['username_err'] = "A username is required. <br />";
		} else {
			$user_name = $_POST['username'];
			if (!preg_match('/^[a-zA-Z0-9]+$/', $user_name)) {
				$errors['username_err'] = "Username must be letters and/or digits only.";
			}

		}

		//check first password
		if (empty($_POST['password1'])) {
			$errors['password1_err'] = "Please enter a password. <br />";
		}

		//check second password
		if (empty($_POST['password2'])) {
			$errors['password2_err'] = "Please re-enter password. <br />";
		}

		if ($_POST['password1'] !== $_POST['password2']) {
			$errors['username_password_err'] = "Passwords do not match.";
		}

		// get data from POST 
		$user_name = mysqli_real_escape_string($conn, trim($_POST['username']));
		$user_password = mysqli_real_escape_string($conn, trim($_POST['password1']));
		$user_password2 = mysqli_real_escape_string($conn, trim($_POST['password2']));
		$verify = mysqli_real_escape_string($conn, trim($_POST['verify']));

		if (!empty($user_name) && !empty($user_password) && !empty($user_password2) && ($user_password == $user_password2)) {
			//check if noone else has the same username
			$sql = "SELECT user_id, username, firstname, lastname, pseudoname, active_status 
					FROM cns_subscriber
					WHERE username = '$user_name'";

			//make query and get result
			$result = mysqli_query($conn, $sql);

			if (mysqli_num_rows($result) == 0) {
				//Username is unique - insert the data into the database

				$sql = "INSERT INTO cns_subscriber(username, password) 
				VALUES( '$user_name', SHA('$user_password'))";

				////////////////////////////////////////////////////// 2020-01-07

				mysqli_query($conn, $sql);

				//check if record is in database & retrieve
				if (mysqli_query($conn, $sql)) {
					//success- get the username and user_id
					$sql = "SELECT user_id, username 
					FROM cns_subscriber
					WHERE username = '$user_name'";

					//make query and get result
					$result = mysqli_query($conn, $sql);

					if (mysqli_num_rows($result) == 1) {

						//fetch the resulting row(s) as an array
						$user_record = mysqli_fetch_assoc($result);

						//set session variables
						$_SESSION["user_id"] = $user_record['user_id'];
						$_SESSION["username"] = $user_record['username'];

						//free result from memory
						mysqli_free_result($result);

					} else {
						// could not retrieve record
						echo "Query error: " . mysqli_error($conn);
					}

					////////
				} else {
					// error
					echo "Query error: " . mysqli_error($conn);
				}

				////////////////////////////////////////////////////////////////////// 2020-01-07

				mysqli_close($conn);

				$_SESSION["message"] = 'You have successfully signed up.';

				$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/redirect.php';
				header('Location: '. $home_url);

			} else { //if (mysqli_num_rows($result) == 0)
				//The username/password are incorrect so set an error message
				$errors['username_password_err'] = "An account with the username " . $user_name . " already exists. <br/> Please use a different username.";


			} //else (mysqli_num_rows($result) == 1)

		} else { // if (!empty($user_name) && !empty($user_password))
			$errors['username_password_err'] = "Please enter username and password, twice, to sign up.";

		} // else (!empty($user_name) && !empty($user_password))

	} // isset($_POST['submit'])

	mysqli_close($conn);

 ?>

 <!DOCTYPE html>
<html>

	<?php include('templates/header.php'); ?>

	<section class="container grey-text">
		<h4 class="center">Sign Up</h4>

		<form class="white" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
			
			<div class="red-text center"><?php echo $errors['username_password_err']; ?></div>

			<label>Username</label>
			<div class="red-text right"><?php echo $errors['username_err']; ?></div>
			<input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($user_name); ?>">
			
			<label>Password</label>
			<div class="red-text right"><?php echo $errors['password1_err']; ?></div>
			<input type="password" name="password1" class="form-control" value="<?php echo htmlspecialchars($user_password); ?>">
			
			<label>Re-type password</label>
			<div class="red-text right"><?php echo $errors['password2_err']; ?></div>
			<input type="password" name="password2" class="form-control" value="<?php echo htmlspecialchars($user_password2); ?>">

			<label for="verify">Verification</label>
			<div class="red-text right"><?php echo $errors['verify_err']; ?></div>
			<input type="text" name="verify" class="form-control" />
			<img src="templates/captcha1.php" alt="Verification pass-phrase" />
			
						
			<div class="center">
				<input type="submit" name="submit" value="Sign Up" class="btn brand z-depth=0">
			</div>

			<div class="nav-wrapper center">
				<nav class="sign-in-up-nav center">
			      	<ul class="right hide-on-med-and-down grey-text">
			        	<li><a href="sign-in.php">Sign in</a></li>
			      	</ul>
				  </nav>
			</div>

		</form>
	</section>

	<?php
		include ('templates/footer.php');
	?>

</html>