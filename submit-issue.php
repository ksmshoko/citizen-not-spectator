<?php
	session_start();

	//Insert the page header
	$page_title = 'Create a Post';

	//Connect to the database
	include('config/db_connect.php');

	//get constants for file uploads
	include('config/appconstants.php');

	if (!isset($_SESSION["user_id"])) {
		// not logged in
		$_SESSION["message"] = 'You must be logged in to post.';

		$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/redirect.php';
		header('Location: '. $home_url);
		exit();
	}

	$user_id = $_SESSION["user_id"];

	//$_GET/$_POST - a global
	
	// Prevent against XSS (Cross site scripting) by using htmlspecialchars

	//Populate constituency field

	//write query 
	$sql = "SELECT * FROM cns_constituency
			ORDER BY con_code";

	//make query and get result
	$result = mysqli_query($conn, $sql);

	//fetch the resulting rows as an array
	$constituencies = mysqli_fetch_all($result, MYSQLI_ASSOC);

	//free result from memory
	mysqli_free_result($result);


	//Populate elected official field

	//write query 
	$sql = "SELECT * FROM cns_elected_officials
			ORDER BY lastname";

	//make query and get result
	$result = mysqli_query($conn, $sql);

	//fetch the resulting rows as an array
	$officials = mysqli_fetch_all($result, MYSQLI_ASSOC);

	//free result from memory
	mysqli_free_result($result);


	//close connection
	//mysqli_close($conn);


	$errors = array('general_err' => '', 'picture_err' => '', 'title_err' => '', 
					'text_err' => '', 'official_err' => '', 'constituency_err' => '');
	$title = $submission_text = $official_tagged = $constituency =  $picture ='';


	if (isset($_POST['submit'])) {

		//Data validation

		//check title
		if (empty($_POST['title'])) {
			$errors['title_err'] = "A title is required. <br />";
		} else {
			$title = $_POST['title'];
		}

		//check description of issue
		if (empty($_POST['submission_text'])) {
			$errors['text_err'] = "Please enter a brief description of the issue. <br />";
		} else {
			$submission_text = $_POST['submission_text'];
		}

		//check official tagged
		if ($_POST['office'] == 'Choose your option') {
			$errors['official_err'] = "Choose an official you want to tag. <br />";
		} else {
			$official_tagged = $_POST['office'];
		}

		//check constituency
		if ($_POST['area'] == 'Choose your option') {
			$errors['constituency_err'] = "Choose the constituency where issue is. <br />";
		} else {
			$constituency = $_POST['area'];
		}

		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	    $target_dir = MM_UPLOAD_IMAGES_PATH;
	    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	    $uploadOk = 1;
	    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

	    //Check if image is an actual image or not
	      $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
	      if ($check !== false) {
	        //$errors['picture_err'] = "File is an image - " . $check["mime"] . ".";
	        echo "File is an image - " . $check["mime"] . ".<br/>";
	        $uploadOk = 1;
	      } else {
	        $errors['picture_err'] = "File is not an image.";
	        echo "File is not an image.<br/>";
	        $uploadOk = 0;
	      }

	    //check if file exists
	    if (file_exists($target_file)) {
	      $errors['picture_err'] = "Sorry, file already exists.";
	      echo "Sorry, file already exists.<br/>";
	      $uploadOk = 0;
	    }

	    //check file size
	    if ($_FILES["fileToUpload"]["size"] > 4194304) {
	      $errors['picture_err'] = "Sorry, your file is too large.";
	      echo "Sorry, your file is too large.<br/>";
	      $uploadOk = 0;
	    }

	    //Allow certain file formats
	    if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "gif") {
	      $errors['picture_err'] = "Sorry, only JPG, JPEG, PJPEG, PNG & GIF files are allowed.";
	      echo "Sorry, only JPG, JPEG, PJPEG, PNG & GIF files are allowed.<br/>";
	      $uploadOk = 0;
	    }

	    //check if $uploadOk is set to 0 by an error
	    if ($uploadOk == 0) {
	      //$errors['picture_err'] = "Sorry, your file was not uploaded.";
	      echo "Sorry, your file was not uploaded.<br/>";
	    // if everything is ok, try to upload file
	    } else {
	        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
	            //$errors['picture_err'] = "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
	            echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.<br/>";
	        } else {
	            //$errors['picture_err'] = "Sorry, there was an error uploading your file.";
	            echo "Sorry, there was an error uploading your file.<br/>";
	        }
	    }

	    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		if (array_filter($errors)) {
			//echo "There are errors on the form";
		} else {
			$title = mysqli_real_escape_string($conn, $_POST['title']);
			$submission_text = mysqli_real_escape_string($conn, $_POST['submission_text']);
			$constituency = mysqli_real_escape_string($conn, $_POST['area']);
			$official_tagged = mysqli_real_escape_string($conn, $_POST['office']);
			$picture = mysqli_real_escape_string($conn, trim($_FILES['fileToUpload']['name']));

			//create sql
			$sql = "INSERT INTO cns_subscriber_submissions(title, submission_text, constituency, official_tagged, submitted_by, pictures) 
					VALUES( '$title', '$submission_text', '$constituency', '$official_tagged', '$user_id', '$picture')";

			//save to database and check
			if (mysqli_query($conn, $sql)) {
				//success
				$_SESSION["message"] = 'Your issue has been successfully logged.';

				$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/redirect.php';
				header('Location: '. $home_url);
				//header('Location: index.php'); //redirect to the index.php page
			} else {
				// error
				echo "Query error: " . mysqli_error($conn);
			}
		}

	} // end of POST

	//close connection
	mysqli_close($conn);

?>

<!DOCTYPE html>
<html>

	<?php include('templates/header.php'); ?>

	<section class="container grey-text">
		<h4 class="center">Post details</h4>

		<form class="white" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
			
			<label>Title</label>
			<div class="red-text right error"><?php echo $errors['title_err']; ?></div>
			<input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($title); ?>">
						
			<div class="red-text right error"><?php echo $errors['text_err']; ?></div>
			<div class="row">
	          <div class="input-field col s12">
	            <textarea id="textarea2" name="submission_text" class="materialize-textarea form-control" data-length="1000" value="<?php echo htmlspecialchars($submission_text); ?>"></textarea>
	            <label for="textarea2">A brief description of the issue</label>
	          </div>
	        </div>

			
			<div class="input-group mb-3">
			<label>Select the elected official to tag</label>
			<div class="red-text right error"><?php echo $errors['official_err']; ?></div>
		  	<select name="office" class="browser-default">
		    	<option selected>Choose your option</option>

		    	<?php foreach ($officials as $official){ ?>
		                 <?php echo '<option value="'.$official['id'].'">
				                 		'.$official['title'].' 
				                 		'.$official['lastname'].'
				                 		'.$official['firstname'].
				                 	'</option>';
		                 ?>
		            <?php } ?>
		  	</select>
		  	</div>
		  	
			<div class="input-group mb-3">
			<label>Select area (constituency)</label>
			<div class="red-text right error"><?php echo $errors['constituency_err']; ?></div>
		  	<select name="area" class="browser-default">
		    	<option selected>Choose your option</option>
		    	<?php foreach ($constituencies as $constituency){ ?>
		                 <?php echo '<option value="'.$constituency['con_code'].'">
		                 				'.$constituency['con_name'].'
		                 				'.",".'
		                 				'.$constituency['con_district'].'
		                 				'.",".'
		                 				'.$constituency['con_province'].
		                 			'</option>';
		                 ?>
		            <?php } ?>
		  	</select>
		  	</div>
		  	
		  	<!--/////////////////////////////////////////////////////////////-->

		  	<div class="red-text right error"><?php echo $errors['picture_err']; ?></div>
		  	<!-- <div>
	        <label for="fileToUpload">Picture:</label>
	        <input type="file" id="fileToUpload" name="fileToUpload" />
	    	</div> -->

	    	<div class="file-field input-field">
		      <div class="btn brand z-depth-0">
		        <span>Browse file</span>
		        <input type="file" multiple name="fileToUpload">
		      </div>
		      <div class="file-path-wrapper">
		        <input class="file-path validate" type="text" placeholder="Upload one or more files">
		      </div>
		    </div>



	        <div class="center">
	        <?php if (!empty($picture)) {
	          echo '<p><img src="' . MM_UPLOAD_IMAGES_PATH . $picture . '" alt="Picture" height="25%" width="25%" /></p>';
	        } ?>
	        </div>

		  	<!-- -->

			<div class="center">
				<input type="submit" name="submit" value="Submit" class="btn brand z-depth=0">
			</div>
		</form>
	</section>

	<?php
		include ('templates/footer.php');
	?>

</html>