<?php 
	session_start();

	//Insert the page header
	$page_title = 'Redirect';
	
 ?>

 <!DOCTYPE html>
 <html>

 	<?php include('templates/header.php'); ?>

 	<div class="container center">
 		
 		<?php
	 		$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
	 		header( "refresh:5;".$home_url );
			//header("Location: ". $home_url); //redirect to the index.php page after 5 seconds
 		?>

		<?php if (isset($_SESSION["message"])) { ?>
	 		<p><?php echo $_SESSION["message"]; ?>  You will be redirected to the home page in shortly.</p>

	 	<?php } else { ?>
	 		<p>You will be redirected to the home page in shortly.</p>
	 	<?php } ?>

	 		<p>If you are not redirected to the home page, please <a href=<?php echo '$home_url' ?>>click on here</a>.</p>

 	</div>

 	<?php include ('templates/footer.php'); ?>

 </html>