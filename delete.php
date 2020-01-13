<?php 

	//Connect to the database
	include('config/db_connect.php');

	if (isset($_POST['delete'])) {

		//get id of pizza to delete
		$id_to_delete = mysqli_real_escape_string($conn, $_POST['id_to_delete']);

		//make sql
		$sql = "DELETE FROM pizzas WHERE id = $id_to_delete";

		if (mysqli_query($conn, $sql)) {
			// success
			header('Location: index.php');
		} else {
			//failure
			echo "Query error: " . mysqli_error($conn);
		}

	}

	//check GET request id parameter
	if (isset($_GET['id'])) {
		$id = mysqli_real_escape_string($conn, $_GET['id']);

		//make sql
		$sql = "SELECT * FROM pizzas WHERE id = $id";

		//get the query result
		$result = mysqli_query($conn, $sql);

		//fetch the resulting row(s) as an array
		$pizza = mysqli_fetch_assoc($result);

		//free result from memory
		mysqli_free_result($result);

		//close connection
		mysqli_close($conn);
	}
 ?>


 <!DOCTYPE html>
 <html>

 	<?php include('templates/header.php'); ?>

 	<div class="container center grey-text">
 		<?php if($pizza): ?>

 			<h4><?php echo htmlspecialchars($pizza['title']); ?></h4>
 			<p>Created by: <?php echo htmlspecialchars($pizza['email']); ?></p>
 			<p><?php echo date($pizza['created_at']); ?></p>
 			<h5>Ingredients</h5>
 			<p><?php echo htmlspecialchars($pizza['ingredients']); ?></p>

 			<!--// Delete form -->
 			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
 				<input type="hidden" name="id_to_delete" value="<?php echo $pizza['id'] ?>">
 				<input type="submit" name="delete" value="Delete" class="btn brand z-depth-0">
 			</form>

 		<?php else: ?>
 			<h5>No such pizza exists</h5>

 		<?php endif; ?>

 	</div>

 	<?php include ('templates/footer.php'); ?>

 </html>