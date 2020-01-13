<?php
	session_start();
	//Insert the page header
	$page_title = 'Home';

	//Connect to the database
	include('config/db_connect.php');

	//get constants for file uploads
	include('config/appconstants.php');

	//write query 
	$sql = "SELECT id, title, submission_text, when_submitted, pictures, 
				no_of_views, no_of_likes, no_of_dislikes, username, con_name 
			FROM cns_subscriber_submissions ss, cns_subscriber s, cns_constituency c
			WHERE ((ss.submitted_by = s.user_id) AND (c.con_code = ss.constituency) AND (verified = 1))
			ORDER BY when_submitted DESC LIMIT 10";

	$sql = "SELECT css.id, title, submission_text, css.pictures, when_submitted, csc.id, csc.pictures, comments, when_commented, username, con_name
			FROM cns_subscriber_submissions AS css
			INNER JOIN cns_subscriber AS cs ON (css.submitted_by = cs.user_id)
			LEFT JOIN cns_submission_comments AS csc ON (css.id = csc.submission_id)
			INNER JOIN cns_constituency AS cc ON (cc.con_code = css.constituency)
			ORDER BY when_submitted";

	//make query and get result
	$result = mysqli_query($conn, $sql);

	//fetch the resulting rows as an array
	$submissions = mysqli_fetch_all($result, MYSQLI_ASSOC);

	//free result from memory
	mysqli_free_result($result);

	//close connection
	mysqli_close($conn);

?>

<!DOCTYPE html>
<html>

	<?php include('templates/header.php'); ?>
   
	<h2 class="center">&quot;Be Citizens not Spectators.&quot;</h2>
	<div class="container">

		<p>&quot;Be Citizens not Spectators.&quot; First learned of the phrase during the inauguration speech of Nana Akuffo Addo in January 2017. It was the media headline. To me the phrase evoked so much hope. A brief research of the phrase showed US president JFK had said it earlier. He probably also got it from someone else. The essence of the phrase shows the human drive to try to help yourself.</p>

		<p>The main point is it evokes action. Do something, be part of something. Do not just stand aside and watch.  Be active participant. This call to active participation is what drives us.</p>

		<p>The word citizenship is derived from the Greek (reference). To be a citizen means to be learned.  You must know about your community. You were expected to contribute. Knowing about your community required seeking out the knowledge.</p>

		<p>
			Back then it was the town or city square, cafes, and other public spaces for gathering the information. Also there was the physical limitation of the space. Now there is no public space or square.
		</p>

		<p>
			However, the public square is needed more than ever.
		<p>How often do you hear people complaining about a public infrastructure or problem _____? But then nothing is done about it.</p>

		<p>Or there is clearly a problem or defect but no one addresses the problem. Citizens simply go about their normal routine.  And they say “Ghana for you!”. Now we want to change that.</p>

		<p>As citizens we have a role to play.</p>
		<p>
		You see something wrong, you say something. We also want to learn about civic engagement. That brings us to civic engagement.
		</p>

	</div>
	<hr>
	<h4 class="center grey-text">Current submissions</h4>
	<div class="container">
		<div class="row">

<?php 

	if ($submissions) { 
		// there are submissions found 
		?>

			<div class="table-responsive-sm-md-lg">
				<table class="table responsive-table table-borderless highlight" width="100%">
					<thead>
					<tr>
						<th>Title</th>
						<th>Description</th>
						<th>Posted</th>
						<th>Posted by</th>
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
							<td><?php echo htmlspecialchars($submission['username']); ?></td>
							<td><?php echo htmlspecialchars($submission['con_name']); ?></td>
							
							<td rowspan="2">
							<?php if (!empty($submission['pictures'])) {
								
								$pic = explode(',', htmlspecialchars($submission['pictures']));

			                    echo '<img class="responsive-img" src="' . MM_UPLOAD_IMAGES_PATH . htmlspecialchars($pic[0]) . '" 
			                    alt="' . MM_UPLOAD_IMAGES_PATH . htmlspecialchars($pic[0]) . '" height="100" width="100" />';
			                  } else {
			                    echo '<img class="img" src="' . MM_UPLOAD_IMAGES_PATH . 'no_img.jpg' . '" alt="Image" height="100" width="100" />';
			                  } ?>

					        </td>

						</tr>

						<tr>
							<div class="right-align">
								<td colspan="5">
									<ul id="nav-mobile" class="right">
										<a class="brand-text" href="view-submission-details.php?id=<?php echo $submission['id'] ?>">Read more</a> 
										<a class="brand-text separator" href="submit-comment.php?id=<?php echo $submission['id'] ?>">Comment</a>
										<a class="brand-text separator" href="like-submit.php?id=<?php echo $submission['id'] ?>">Like</a>
										<a class="brand-text separator" href="like-submit.php?id=<?php echo $submission['id'] ?>">Dislike</a>
									</ul>
								</td>
							</div>
						</tr>
					</tbody>
					<?php endforeach; ?>

				</table>
			</div>

		<?php } else { // no results ?>
						
			<p>There are currently no posts at the moment.</p>
		
		<?php } ?>

		</div>	
	</div>

	<?php include ('templates/footer.php'); ?>

</html>