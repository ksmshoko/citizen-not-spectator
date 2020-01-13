<?php
	session_start();
	//Insert the page header
	$page_title = 'Home';

	//Connect to the database
	include('config/db_connect.php');

	//get constants for file uploads
	include('config/appconstants.php');

////////////////////////////////////////////////////////////////////////////////////
	// This function builds navigational page links based on the current page and the number of pages
  function generate_page_links($user_search, $sort, $cur_page, $num_pages) {
    $page_links = '';

    // If this page is not the first page, generate the "previous" link
    if ($cur_page > 1) {
      $page_links .= '<a href="' . $_SERVER['PHP_SELF'] . '?usercriteria=' . $user_search . '&sort=' . $sort . '&page=' . ($cur_page - 1) . '"><-</a> ';
    }
    else {
      $page_links .= '<- ';
    }

    // Loop through the pages generating the page number links
    for ($i = 1; $i <= $num_pages; $i++) {
      if ($cur_page == $i) {
        $page_links .= ' ' . $i;
      }
      else {
        $page_links .= ' <a href="' . $_SERVER['PHP_SELF'] . '?usercriteria=' . $user_search . '&sort=' . $sort . '&page=' . $i . '"> ' . $i . '</a>';
      }
    }

    // If this page is not the last page, generate the "next" link
    if ($cur_page < $num_pages) {
      $page_links .= ' <a href="' . $_SERVER['PHP_SELF'] . '?usercriteria=' . $user_search . '&sort=' . $sort . '&page=' . ($cur_page + 1) . '">-></a>';
    }
    else {
      $page_links .= ' ->';
    }

    return $page_links;
  }


////////////////////////////////////////////////////////////////////////////////////

	//write query 
	
	$sql = "SELECT id, title, submission_text, when_submitted, pictures, 
				no_of_views, no_of_likes, no_of_dislikes, username, con_name 
			FROM cns_subscriber_submissions AS css
			INNER JOIN cns_subscriber AS cs ON (css.submitted_by = cs.user_id)
			INNER JOIN cns_constituency AS cc ON (cc.con_code = css.constituency)
			ORDER BY when_submitted DESC LIMIT 5";

	//make query and get result
	$result = mysqli_query($conn, $sql);

	//fetch the resulting rows as an array
	$submissions = mysqli_fetch_all($result, MYSQLI_ASSOC);

	//free result from memory
	mysqli_free_result($result);

	//close connection
	//mysqli_close($conn);

?>

<!DOCTYPE html>
<html>

	<?php include('templates/header.php'); ?>
   
	<h3 class="center">&quot;Be Citizens not Spectators.&quot;</h3>
	<div class="container">

		<p>&quot;<em>Be Citizens not Spectators.</em>&quot; First learned of the phrase during the inauguration speech of Nana Akuffo Addo in January 2017. It was the media headline. To me the phrase evoked so much hope. A brief research of the phrase showed US president JFK had said it earlier. He probably also got it from someone else. The essence of the phrase shows the human drive to try to help yourself.</p>

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
	<h5 class="center grey-text">Current submissions</h5>
	<div class="container">
		<div class="row">

<?php 

	if ($submissions) { 
		// there are submissions found 
		?>

			<div class="table-responsive-sm-md-lg">
				<table class="table responsive-table table-borderless highlight" width="100%">
					<thead><tr></tr></thead>

					<tbody>
					<?php foreach ($submissions as $submission): ?>
						
						<tr>
							<td><em><?php echo htmlspecialchars($submission['title']); ?><br/><br/></em>
								<?php echo htmlspecialchars($submission['submission_text']); ?><br/>
								<div>
									<em>
										<ul id="nav-mobile" >
											<?php echo htmlspecialchars($submission['when_submitted']); ?>
											<?php echo htmlspecialchars($submission['username']); ?>
											<?php echo htmlspecialchars($submission['con_name']); ?>
										</ul>
									</em>
								</div>
								
								<div class="right-align">
									<ul id="nav-mobile" class="right">
										<a class="brand-text" href="view-submission-details.php?id=<?php echo $submission['id'] ?>">Read more</a> 
										<a class="brand-text separator" href="submit-comment.php?id=<?php echo $submission['id'] ?>">Comment</a>
										<a class="brand-text separator" href="submission-like-dislike.php?id=<?php echo $submission['id'].'&like-dislike=L' ?>">Like</a>
										<a class="brand-text separator" href="submission-like-dislike.php?id=<?php echo $submission['id'].'&like-dislike=D' ?>">Dislike</a>
									</ul>
								</div>
							</td>
							
							<td>
							<?php if (!empty($submission['pictures'])) {
								
								$pic = explode(',', htmlspecialchars($submission['pictures']));

			                    echo '<img class="responsive-img" src="' . MM_UPLOAD_IMAGES_PATH . htmlspecialchars($pic[0]) . '" 
			                    alt="' . MM_UPLOAD_IMAGES_PATH . htmlspecialchars($pic[0]) . '" height="100" width="100" />';
			                  } else {
			                    echo '<img class="img" src="' . MM_UPLOAD_IMAGES_PATH . 'no_img.jpg' . '" alt="Image" height="100" width="100" />';
			                  } ?>

					        </td>
						</tr>

<!------///////////////////////////////////////////////////////////////////////////////////////////-->
			<?php 
				// check comments
				
			$sql = "SELECT csc.id, comments, submission_id, when_commented, csc.pictures, 
							username 
						FROM cns_submission_comments AS csc
						INNER JOIN cns_subscriber_submissions AS css ON (css.id = csc.submission_id)
						INNER JOIN cns_subscriber AS cs ON (csc.submitted_by = cs.user_id)
						ORDER BY when_commented DESC";

				//make query and get result
				$result = mysqli_query($conn, $sql);

				//fetch the resulting rows as an array
				$comments = mysqli_fetch_all($result, MYSQLI_ASSOC);

				//free result from memory
				mysqli_free_result($result);

				/////////////////////////

				if ($comments) { ?>
				
					<table class="table responsive-table table-borderless highlight" width="100%">
						<thead>
						<tr>
							<th>Comments</th>
							
							
						</tr>
						</thead>

						<tbody>
						<?php foreach ($comments as $comment): ?>
							
							<tr>
								<td><em><?php echo htmlspecialchars($comment['comments']); ?></em><br/>
									<?php echo htmlspecialchars($comment['when_commented']); ?>
									<?php echo htmlspecialchars($comment['username']); ?>

									<div class="right-align">
										<ul id="nav-mobile" class="right">
											<a class="brand-text" href="view-submission-details.php?id=<?php echo $submission['id'] ?>">Read more</a> 
											<a class="brand-text separator" href="submit-comment.php?id=<?php echo $submission['id'] ?>">Comment</a>
											<a class="brand-text separator" href="like-submit.php?id=<?php echo $submission['id'] ?>">Like</a>
											<a class="brand-text separator" href="like-submit.php?id=<?php echo $submission['id'] ?>">Dislike</a>
										</ul>
									</div>
								</td>
								
								<td>
								<?php if (!empty($comment['pictures'])) {
									
									$pic = explode(',', htmlspecialchars($comment['pictures']));

				                    echo '<img class="responsive-img" src="' . MM_UPLOAD_IMAGES_PATH . htmlspecialchars($pic[0]) . '" 
				                    alt="' . MM_UPLOAD_IMAGES_PATH . htmlspecialchars($pic[0]) . '" height="100" width="100" />';
				                  } else {
				                    echo '<img class="img" src="' . MM_UPLOAD_IMAGES_PATH . 'no_img.jpg' . '" alt="Image" height="100" width="100" />';
				                  } ?>

						        </td>

							</tr>

						</tbody>
						<?php endforeach; ?>

					</table>
			<?php } 
			 ?>

<!------//////////////////////////////////////////////////////////////////////////////////////////11012020 -->

					</tbody>
					<?php endforeach; ?>

				</table>
			</div>

		<?php } else { // no results ?>
						
			<p>There are currently no posts at the moment.</p>
		
		<?php } ?>

		</div>	
	</div>

	<?php 
		//close connection
		mysqli_close($conn);
	 ?>

	<?php include ('templates/footer.php'); ?>

</html>