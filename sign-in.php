<?php 

	session_start();

	//Insert the page header
	$page_title = 'Sign In';
	$user_id = 1;

	include('config/db_connect.php');

	$errors = array('username' => '', 'password' => '', 'username_password' => '');
	$user_name = $user_password = '';

	if (!isset($_SESSION["user_id"])) {
		if (isset($_POST['submit'])) {

			//Data validation

			//check username
			if (empty($_POST['username'])) {
				$errors['username'] = "A username is required. <br />";
			} else {
				$user_name = $_POST['username'];
				if (!preg_match('/^[a-zA-Z0-9\-]+$/', $user_name)) {
					$errors['username'] = "Username must be letters and/or digits only.";
				}

			}

			//check password
			if (empty($_POST['password'])) {
				$errors['password'] = "Please enter a password. <br />";
			}

			$user_name = mysqli_real_escape_string($conn, trim($_POST['username']));
			$user_password = mysqli_real_escape_string($conn, trim($_POST['password']));

			if (!empty($user_name) && !empty($user_password)) {
				//look up the username and password in the database
				$sql = "SELECT user_id, username, firstname, lastname, pseudoname, active_status 
						FROM cns_subscriber
						WHERE username = '$user_name' AND password = SHA('$user_password')";

				//make query and get result
				$result = mysqli_query($conn, $sql);

				if (mysqli_num_rows($result) == 1) {
					//Login success - set the user ID and username session variables (and cookies) and re-direct to home page

					$row = mysqli_fetch_array($result);

					$user_id = $row['user_id'];
					$_SESSION["user_id"] = $row['user_id'];
					$_SESSION["username"] = $row['username'];
					$_SESSION["pseudonym"] = $row['pseudoname'];
					$_SESSION["lastname"] = $row['lastname'];
					$_SESSION["firstname"] = $row['firstname'];
					$_SESSION["message"] = 'Nothing';
					$_SESSION["login_id"] = 0;
					$_SESSION["active_status"] = $row['active_status'];

					//create sql
					$sql = "INSERT INTO cns_subscriber_sessions(subscriber_id) 
							VALUES( '$user_id' )";

					//save to database and check
					if (mysqli_query($conn, $sql)) {
						//success
						$sql = "SELECT login_id 
						FROM cns_subscriber_sessions
						WHERE subscriber_id = '$user_id' 
						ORDER BY when_logged_in DESC 
						LIMIT 1";

						//make query and get result
						$result = mysqli_query($conn, $sql);

						if (mysqli_num_rows($result) == 1) {
							//login record found - access row and get login_id
							$row = mysqli_fetch_array($result);
							$_SESSION["login_id"] = $row['login_id']; // get login id for when logging out
							echo "Login id => " . $_SESSION["login_id"];
						}

					} else {
						// error - could not insert login record
						echo "Query error: " . mysqli_error($conn);
					}

					setcookie('user_id', $row['username'], time() + (60 * 60)); // expires after 1 hour

					setcookie('username', $row['username'], time() + (60 * 60)); // expires after  1 hour

					if ($_SESSION["active_status"] == 0) {
						$_SESSION["message"] = 'Welcome ' .$_SESSION["username"]. '. You have successfully signed in. Please update your profile.';

						$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/update-profile.php';
						header('Location: '. $home_url);
					} else {
						$_SESSION["message"] = 'Welcome ' .$_SESSION["username"]. '. You have successfully signed in.';

						$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/redirect.php';
						header('Location: '. $home_url);
					}
					
					

				} else { //if (mysqli_num_rows($result) == 1)
					//The username/password are incorrect so set an error message
					$errors['username_password'] = "Username and/or password incorrect.";

				} //else (mysqli_num_rows($result) == 1)

			} else { // if (!empty($user_name) && !empty($user_password))
				$errors['username_password'] = "Please enter both username and password to sign in.";

			} // else (!empty($user_name) && !empty($user_password))

		} // isset($_POST['submit'])

	} // !isset($_SESSION['user_id'])

 ?>

 <!DOCTYPE html>
<html>

	<?php include('templates/header.php'); ?>

	<section class="container grey-text">
		<h4 class="center">Sign In</h4>

		<form class="white" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
			
			<div class="red-text center"><?php echo $errors['username_password']; ?></div>

			<label>Username</label>
			<div class="red-text right"><?php echo $errors['username']; ?></div>
			<input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($user_name); ?>">
			
			<label>Password</label>
			<div class="red-text right"><?php echo $errors['password']; ?></div>
			<input type="password" name="password" class="form-control">
						
			<div class="center">
				<input type="submit" name="submit" value="Sign in" class="btn brand z-depth=0">
			</div>
			<div>
				<p></p>
			</div>

			<div class="nav-wrapper center">
				<nav class="sign-in-up-nav center">
			      	<ul class="right hide-on-med-and-down grey-text">
			        	<li><a href="forgot-password.php">Forgot password</a></li>
			        	<li><a href="sign-up.php">Sign up</a></li>
			      	</ul>
				  </nav>
			</div>
		</form>
	</section>

	<?php
		include ('templates/footer.php');
	?>

</html>