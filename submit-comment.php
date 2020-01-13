<?php 
	session_start();

	//Insert the page header
	$page_title = 'Post a comment';

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

	if (isset($_GET['id'])) {
		
		$_SESSION['sub_id'] = $_GET['id'];
	}


	$errors = array('general_err' => '', 'picture_err' => '', 'comment_err' => '');
	$comment_text = $picture = $title = $submission_text = $when_submitted = $no_of_views = $no_of_likes = $no_of_dislikes =
	$username = $con_name = $pictures ='';

	$sub_id = '';
	$user_id = '';

	$sub_id = $_SESSION['sub_id'];

	$user_id = $_SESSION['user_id'];

	//check GET request id parameter
	if (isset($_SESSION['sub_id'])) {
		
		// 
		$id = mysqli_real_escape_string($conn, $_SESSION['sub_id']);

////////////////////////////////////////////////////////////////////20200108
		if (isset($_POST['post'])) {

			//Data validation

			//check comment
			if (empty($_POST['comment_text'])) {
				$errors['comment_err'] = "A comment is required. <br />";
			} else {
				$comment = $_POST['comment_text'];
			}

			if (array_filter($errors)) {
				echo "There are errors on the form";
			} else {
				$comment = mysqli_real_escape_string($conn, $_POST['comment_text']);
				$pictures = mysqli_real_escape_string($conn, trim($_FILES['fileToUpload']['name']));

				//create sql
				$sql = "INSERT INTO cns_submission_comments(submission_id, submitted_by, comments, pictures) 
						VALUES( '$sub_id', '$user_id', '$comment', '$pictures')";

				//save to database and check
				if (mysqli_query($conn, $sql)) {

					//close connection
					mysqli_close($conn);

					//success
					$_SESSION["message"] = 'Your comment was posted, successfully .';

					$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/redirect.php';
					header('Location: '. $home_url);
					exit();
				} else {
					// error
					echo "Query error: " . mysqli_error($conn);
				}
			} // no errors in array_filter
		} // $_POST
		// NOT posted

		///////////////////////////////////////////////////////////////////20200108

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
			//get data from row
			$views 				= $submission['no_of_views'] + 1;
			$title 				= $submission['title'];
			$submission_text 	= $submission['submission_text'];
			$con_name 			= $submission['con_name'];
			$username 			= $submission['username'];
			$when_submitted		= $submission['when_submitted'];
			$no_of_views 		= $submission['no_of_views'];
			$no_of_likes 		= $submission['no_of_likes'];
			$no_of_dislikes 	= $submission['no_of_dislikes'];
			$pictures 			= $submission['pictures'];

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
				// nothing to do here
			} else {
				// error
				echo "Query error: " . mysqli_error($conn);
			} // else
		} else {// submission was not found

			$_SESSION["message"] = 'Sorry, the post you are looking for was not found.';

			$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/redirect.php';
			header('Location: '. $home_url);
			exit();
		} // 

	} // GET
 ?>

 <!DOCTYPE html>
 <html>

 	<?php include('templates/header.php'); ?>

 	<div class="container center">

 		<?php if(!isset($_POST['post']) && isset($_SESSION['sub_id'])): ?>

 			<h4><?php echo htmlspecialchars($title); ?></h4>
 			<p><em>Description: </em><?php echo htmlspecialchars($submission_text); ?></p>
 			<p><em>Date submitted: </em><?php echo date($when_submitted); ?></p>
 			<p><em>Submitted by: </em><?php echo ($username); ?></p>
 			<p><em>Area: </em><?php echo ($con_name); ?></p>
 			<h5>Images</h5>
 			<p>
 				<?php if (!empty($pictures)) { 
	                echo '<img class="responsive-img img" src="' . MM_UPLOAD_IMAGES_PATH . htmlspecialchars($pictures) . '" 
	                alt="' . MM_UPLOAD_IMAGES_PATH . htmlspecialchars($pictures) . '" height="400" width="400" />';
	              } else {
	                echo '<img class="img" src="' . MM_UPLOAD_IMAGES_PATH . 'no_img.jpg' . '" alt="Image" height="100" width="100" />';
	              } ?>
 			</p>
 			<h5>Statistics</h5>
 			<p><?php echo htmlspecialchars($no_of_views); ?><em> views</em></p>
 			<p><?php echo htmlspecialchars($no_of_likes); ?><em> likes</em></p>
 			<p><?php echo htmlspecialchars($no_of_dislikes); ?><em> dislikes</em></p>
			<div>
				<section class="container grey-text">
					<form class="white" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
											
						<div class="red-text right error"><?php echo $errors['comment_err']; ?></div>
						<div class="row">
				          <div class="input-field col s12">
				            <textarea id="textarea2" name="comment_text" class="materialize-textarea" data-length="1000" value="<?php echo htmlspecialchars($comment_text); ?>"></textarea>
				            <label for="textarea2">Add a comment</label>
				          </div>
				        </div>

					  	<div class="red-text right error"><?php echo $errors['picture_err']; ?></div>

						<div class="file-field input-field">
					      <div class="btn brand z-depth-0">
					        <span>Browse</span>
					        <input type="file" multiple name="fileToUpload">
					      </div>
					      <div class="file-path-wrapper">
					        <input class="file-path validate" type="text" placeholder="Upload one or more files">
					      </div>
					    </div>

				        <div class="center">
				        <?php if (!empty($picture)) {
				          echo '<p><img class="responsive-img" src="' . MM_UPLOAD_IMAGES_PATH . $picture . '" alt="Picture" height="25%" width="25%" /></p>';
				        } ?>
				        </div>

						<div class="center">
							<input type="submit" name="post" value="Post comment" class="btn brand z-depth=0">
						</div>
					</form>
				</section>
			</div>

		<?php elseif (isset($_POST['post'])): ?>
 			<!-- Not supposed to be here -->
 			<?php echo 'Something went wrong, you aren\'t supposed to be here.'  . '<br/>' ;?>

 		<?php else: ?>
 			<h5>Submission not found </h5>

 			<?php  
				$_SESSION["message"] = 'Sorry, the post you are looking for was not found.';

				$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/redirect.php';
				header('Location: '. $home_url);
				exit();
			?>
 		<?php endif; ?>

 	</div>

 	<?php include ('templates/footer.php'); ?>

 </html>