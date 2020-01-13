<?php 
	session_start();

	//Insert the page header
	$page_title = 'View Post';

	//Connect to the database
	include('config/db_connect.php');

	//get constants for file uploads
	include('config/appconstants.php');

	//check GET request id parameter
	if (isset($_GET['id'])) {
		$id = mysqli_real_escape_string($conn, $_GET['id']);

		//make sql
		$sql = "SELECT id, title, submission_text, when_submitted, no_of_views, no_of_likes, no_of_dislikes,
						username, con_name, pictures
				FROM cns_subscriber_submissions ss, cns_subscriber s, cns_constituency c
				WHERE ((ss.submitted_by = s.user_id) AND (ss.constituency = c.con_code)) AND (ss.id = '$id')";

		//get the query result
		$result = mysqli_query($conn, $sql);

		//fetch the resulting row(s) as an array
		$submission = mysqli_fetch_assoc($result);

		if ($submission) {
			//increase views
			$views = $submission['no_of_views'] + 1;

			//free result from memory
			mysqli_free_result($result);

			//Increase views
			//create sql
			$sql = "UPDATE cns_subscriber_submissions
					SET no_of_views = $views
					WHERE id = $id";

			//save to database and check
			if (mysqli_query($conn, $sql)) {
				//success
				//header('Location: index.php'); //redirect to the index.php page
			} else {
				// error
				echo "Query error: " . mysqli_error($conn);
			}
		}
		//close connection
		mysqli_close($conn);
	}
 ?>


 <!DOCTYPE html>
 <html>

 	<?php include('templates/header.php'); ?>

 	<div class="container center">
 		<?php if($submission): ?>
 			<h4><?php echo htmlspecialchars($submission['title']); ?></h4>
 			<p><em>Description: </em><?php echo htmlspecialchars($submission['submission_text']); ?></p>
 			<p><em>Date submitted: </em><?php echo date($submission['when_submitted']); ?></p>
 			<p><em>Submitted by: </em><?php echo ($submission['username']); ?></p>
 			<p><em>Area: </em><?php echo ($submission['con_name']); ?></p>
 			<h5>Images</h5>
 			<p>
 				<?php if (!empty($submission['pictures'])) {
 					$pic = explode(',', htmlspecialchars($submission['pictures']));

	                echo '<img class=" responsive-img img" src="' . MM_UPLOAD_IMAGES_PATH . htmlspecialchars($pic[0]) . '" 
	                alt="' . MM_UPLOAD_IMAGES_PATH . htmlspecialchars($pic[0]) . '" height="400" width="400" />';
	              } else {
	                echo '<img class="img" src="' . MM_UPLOAD_IMAGES_PATH . 'no_img.jpg' . '" alt="Image" height="100" width="100" />';
	              } ?>
 			</p>
 			<h5>Statistics</h5>
 			<p><?php echo htmlspecialchars($submission['no_of_views']); ?><em> views</em></p>
 			<p><?php echo htmlspecialchars($submission['no_of_likes']); ?><em> likes</em></p>
 			<p><?php echo htmlspecialchars($submission['no_of_dislikes']); ?><em> dislikes</em></p>
 			<div>
 				<p>
 					<ul id="nav-mobile" class="center">
						<a class="brand-text separator" href="submit-comment.php?id=<?php echo $submission['id'] ?>">Comment</a>
						<a class="brand-text separator" href="like-submit.php?id=<?php echo $submission['id'] ?>">Like</a>
						<a class="brand-text separator" href="like-submit.php?id=<?php echo $submission['id'] ?>">Dislike</a>
					</ul>
 				</p>
	 			
			</div>
			<div>
				<p>
					
				</p>
			</div>

 		<?php else: ?>
 			<h5>Submission not found</h5>

 		<?php endif; ?>

 	</div>

 	<?php include ('templates/footer.php'); ?>

 </html>