<?php 
	session_start();

	//Insert the page header
	$page_title = 'View my posts';

	//Connect to the database
	include('config/db_connect.php');

	//get constants for file uploads
	include('config/appconstants.php');

	if (!isset($_SESSION["user_id"])) {
		// not logged in
		$_SESSION["message"] = 'You must be logged in to post comments.';

		$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/redirect.php';
		header('Location: '. $home_url);
		exit();
	}

	$user_id = $_SESSION["user_id"];
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//write query 
	$sql = "SELECT id, title, submission_text, when_submitted, pictures, 
				no_of_views, no_of_likes, no_of_dislikes, username, con_name 
			FROM cns_subscriber_submissions ss, cns_subscriber s, cns_constituency c
			WHERE ((ss.submitted_by = s.user_id) AND (c.con_code = ss.constituency) AND (s.user_id = '$user_id'))
			ORDER BY when_submitted DESC LIMIT 10";

	//make query and get result
	$result = mysqli_query($conn, $sql);

	//fetch the resulting rows as an array
	$submissions = mysqli_fetch_all($result, MYSQLI_ASSOC);

	//free result from memory
	mysqli_free_result($result);

	//close connection
	mysqli_close($conn);


	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 ?>


<!DOCTYPE html>
<html>

	<?php include('templates/header.php'); ?>
   
	
	<h4 class="center grey-text">My posts - <?php echo htmlspecialchars($_SESSION['firstname']) . ' ' . htmlspecialchars($_SESSION['lastname']); ?></h4>
	<div class="container">
		<div class="row">

<?php 

	if ($submissions) { 
		// there are submissions found 
		?>

			<div class="table-responsive-sm-md-lg">
				<table class="table responsive-table table-borderless" width="100%">
					<thead>
					<tr>
						<th>Title</th>
						<th>Description</th>
						<th>Posted</th>
						
						<th>Area</th>
						<th>Picture(s)</th>
					</tr>
					</thead>

					<tbody>
					<?php foreach ($submissions as $submission): ?>
						
						<tr>
							<td><em><?php echo htmlspecialchars($submission['title']); ?></em></td>
							<td><?php echo htmlspecialchars($submission['submission_text']); ?><br/></td>
							<td><?php echo htmlspecialchars($submission['when_submitted']); ?></td>
							
							<td><?php echo htmlspecialchars($submission['con_name']); ?></td>
							
							<td rowspan="2">
							<?php if (!empty($submission['pictures'])) { 

								$pic = explode(',', htmlspecialchars($submission['pictures']));
								
			                    echo '<img class="img" src="' . MM_UPLOAD_IMAGES_PATH . htmlspecialchars($pic[0]) . '" 
			                    alt="' . MM_UPLOAD_IMAGES_PATH . htmlspecialchars($pic[0]) . '" height="100" width="100" />';
			                  } else {
			                    echo '<img class="img" src="' . MM_UPLOAD_IMAGES_PATH . 'no_img.jpg' . '" alt="Image" height="100" width="100" />';
			                  } ?>

					        </td>

						</tr>

						<tr>
							<div class="right-align">
								<td colspan="4">
									<ul id="nav-mobile" class="right">
										<a class="brand-text" href="view-submission-details.php?id=<?php echo $submission['id'] ?>">Read more</a> 
										<a class="brand-text separator" href="edit-submission.php?id=<?php echo $submission['id'] ?>">Edit Post</a>
										<a class="brand-text separator" ><?php echo $submission['no_of_views'] ?> views</a>
										<a class="brand-text separator" ><?php echo $submission['no_of_likes'] ?> likes</a>
										<a class="brand-text separator" ><?php echo $submission['no_of_dislikes'] ?> dislikes</a>
									</ul>
								</td>
							</div>
						</tr>
					</tbody>
					<?php endforeach; ?>

				</table>
			</div>

		<?php } else { // no results ?>
						
			<p>You currently have no posts. Would you like to <a href="submit-issue.php">submit a post?</a></p>
		
		<?php } ?>

		</div>	
	</div>

	<?php include ('templates/footer.php'); ?>

</html>