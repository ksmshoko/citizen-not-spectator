<?php
  session_start();
  // Insert the page header
  $page_title = 'Update Profile';

  //connect to database
  include('config/db_connect.php');

  //get constants for file uploads
  include('config/appconstants.php');

  if (!isset($_SESSION["user_id"])) {
    // not logged in
    $_SESSION["message"] = 'You must be logged in to update profile.';

    $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/redirect.php';
    header('Location: '. $home_url);
    exit();
  }

  $errors = array('general_err' => '', 'picture_err' => '', 'firstname_err' => '', 'lastname_err' => '', 'town_city_err' => '',
                 'email_err' => '', 'street_err' => '', 'country_err' => '', 'postcode_err' => '', 
                 'contact_err' => '', 'dob_err' => '', 'gender_err' => '', 'pseudoname_err' => '');

  $firstname = $lastname = $email = $street = $town_city = $postcode = $country = 
          $pseudoname = $contact = $gender =  $picture = $dob = $active_status = '';

  // Make sure the user is logged in before going any further.

  /////////////////////////////////////////////////////////////////////////////////////////////////// 20200107

  // if (!isset($_SESSION['user_id'])) {
  //   $errors['general'] = 'Please <a href="sign-in.php">log in</a> to access this page.</p>';
  //   echo '<p class="login">Please <a href="sign-in.php">log in</a> to access this page.</p>';
  //   exit();
  // }

  /////////////////////////////////////////////////////////////////////////////////////////////////// 20200107

  if (isset($_POST['submit'])) {
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////20200107

    //Data validation

    //check firstname
    if (empty($_POST['firstname'])) {
      $errors['firstname_err'] = "Please enter your firstname. <br />";
    } else {
      $firstname = $_POST['firstname'];
    }

    //check lastname
    if (empty($_POST['lastname'])) {
      $errors['lastname_err'] = "Please enter your lastname. <br />";
    } else {
      $lastname = $_POST['lastname'];
    }

    //check gender
    if ($_POST['gender'] == 'Choose your option') {
      $errors['gender_err'] = "Please choose gender. <br />";
    } else {
      $gender = $_POST['gender'];
    }

    //check Date of birth
    if (empty($_POST['birthdate']) || ($_POST['birthdate'] == 'yyyy/mm/dd')) {
      $errors['dob_err'] = "Please enter your date of birth. <br />";
    } else {
      $dob = $_POST['birthdate'];
    }

    //check email
    if (empty($_POST['email'])) {
      $errors['email_err'] = "Please enter your email address. <br />";
    } else {
      $email = $_POST['email'];
    }
    
    //check street
    if (empty($_POST['street'])) {
      $errors['street_err'] = "Please enter your street address. <br />";
    } else {
      $street = $_POST['street'];
    }

    //check town_city
    if (empty($_POST['town_city'])) {
      $errors['town_city_err'] = "Please enter your town/city. <br />";
    } else {
      $town_city = $_POST['town_city'];
    }

    //check postcode
    if (empty($_POST['postcode'])) {
      $errors['postcode_err'] = "Please enter your postcode. <br />";
    } else {
      $postcode = $_POST['postcode'];
    }

    //check country
    if (empty($_POST['country'])) {
      $errors['country_err'] = "Please enter your country. <br />";
    } else {
      $country = $_POST['country'];
    }

    //check contact
    if (empty($_POST['contact'])) {
      $errors['contact_err'] = "Please enter your contact number. <br />";
    } else {
      $contact = $_POST['contact'];
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////20200107
    // Grab the profile data from the POST
    $firstname = mysqli_real_escape_string($conn, trim($_POST['firstname']));
    $lastname = mysqli_real_escape_string($conn, trim($_POST['lastname']));
    $gender = mysqli_real_escape_string($conn, trim($_POST['gender']));
    $dob = mysqli_real_escape_string($conn, trim($_POST['birthdate']));
    $street = mysqli_real_escape_string($conn, trim($_POST['street']));
    $town_city = mysqli_real_escape_string($conn, trim($_POST['town_city']));
    $postcode = mysqli_real_escape_string($conn, trim($_POST['postcode']));
    $country = mysqli_real_escape_string($conn, trim($_POST['country']));
    $contact = mysqli_real_escape_string($conn, trim($_POST['contact']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $pseudoname = mysqli_real_escape_string($conn, trim($_POST['pseudoname']));
    $picture = mysqli_real_escape_string($conn, trim($_FILES['fileToUpload']['name']));
    
   //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($_FILES['fileToUpload']['name'] == "")  {
  echo "File name is not set to  " . $_FILES['fileToUpload']['name'];
}


    if ($_FILES['fileToUpload']['name'] !== "") {
      $target_dir = MM_UPLOAD_PROFILES_PATH;
      $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
      $uploadOk = 1;
      $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

      //Check if image is an actual image or not
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if ($check !== false) {
          //$errors['picture_err'] = "File is an image - " . $check["mime"] . ".";
          //echo "File is an image - " . $check["mime"] . ".<br/>";
          $uploadOk = 1;
        } else {
          $errors['picture_err'] = "File is not an image.";
          //echo "File is not an image.<br/>";
          $uploadOk = 0;
        }

        //check if file exists
        if (file_exists($target_file)) {
          $errors['picture_err'] = "Sorry, file already exists.";
          //echo "Sorry, file already exists.<br/>";
          $uploadOk = 0;
        }

        //check file size
        if ($_FILES["fileToUpload"]["size"] > 4194304) {
          $errors['picture_err'] = "Sorry, your file is too large.";
          //echo "Sorry, your file is too large.<br/>";
          $uploadOk = 0;
        }

        //Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "gif") {
          $errors['picture_err'] = "Sorry, only JPG, JPEG, PJPEG, PNG & GIF files are allowed.";
          //echo "Sorry, only JPG, JPEG, PJPEG, PNG & GIF files are allowed.<br/>";
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

    } // no file selected
    

    // echo "Name is " . $firstname .  '  - ' . $errors['firstname_err'] .'<br/>';
    // echo "lastname is " . $lastname . '  - ' . $errors['lastname_err'] .'<br/>';
    // echo "gender is " . $gender . '  - ' . $errors['gender_err'] .'<br/>';
    // echo "birthdate is " . $dob . '  - ' . $errors['dob_err'] .'<br/>';
    // echo "street is " . $street . '  - ' . $errors['street_err'] .'<br/>';
    // echo "email is " . $email . '  - ' . $errors['email_err'] .'<br/>';
    // echo "contact is " . $contact . '  - ' . $errors['contact_err'] .'<br/>';
    // echo "country is " . $country . '  - ' . $errors['country_err'] .'<br/>';
    // echo "town is " . $town_city . '  - ' . $errors['town_city_err'] .'<br/>';
    // echo "postcode is " . $postcode . '  - ' . $errors['postcode_err'] .'<br/>';
    // echo "pseudo is " . $pseudoname . '  - ' . $errors['pseudoname_err'] .'<br/>';
    // echo "Picture is " . $picture . '  - ' . $errors['picture_err'] .'<br/>';
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    // Update the profile data in the database
    if (array_filter($errors)) {
      $errors['general_err'] = 'There are some errors on the form.';
    } else {

        // Only set the picture column if there is a picture
        if (!empty($picture)) {
          $sql = "UPDATE cns_subscriber 
                  SET firstname = '$firstname', lastname = '$lastname', gender = '$gender', active_status = 1,
                      dob = '$dob', street = '$street', town_city = '$town_city', postcode = '$postcode',
                      email = '$email', country = '$country', contact_no = '$contact', pseudoname = '$pseudoname',
                      picture = '$picture' 
                  WHERE user_id = '" . $_SESSION["user_id"] . "'";
        } // if (!empty($picture))
        else {
          $sql = "UPDATE cns_subscriber 
                  SET firstname = '$firstname', lastname = '$lastname', gender = '$gender', active_status = 1,
                      dob = '$dob', street = '$street', town_city = '$town_city', postcode = '$postcode',
                      email = '$email', country = '$country', contact_no = '$contact', pseudoname = '$pseudoname'
                  WHERE user_id = '" . $_SESSION["user_id"] . "'";
        } // else if (!empty($picture))

        //////////////////////////////////////////////////

        //update database
        if (mysqli_query($conn, $sql)) {
          // update/set session variables
          $_SESSION["firstname"] = $_POST["firstname"];
          $_SESSION["lastname"] = $_POST["lastname"];
          $_SESSION["pseudoname"] = $_POST["pseudoname"];
          $_SESSION["active_status"] = 1;

          //close connection
          mysqli_close($conn);
          
          // Confirm success with the user
          $_SESSION["message"] = 'Your profile has been successfully updated.';

          $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/redirect.php';
          header('Location: '. $home_url);
        } else {
          // error
          echo "Query error: " . mysqli_error($conn);
        } // else - error updating database

        ////////////////////////////////////////////////

    } // else if (!array_filter)
  } // End of check for form submission
  else {
      // Grab the profile data from the database
      $sql = "SELECT * FROM cns_subscriber WHERE user_id = '" . $_SESSION["user_id"] . "'";
      $data = mysqli_query($conn, $sql);
      $row = mysqli_fetch_array($data);

      if ($row != NULL) {
        $firstname      = $row['firstname'];
        $lastname       = $row['lastname'];
        $gender         = $row['gender'];
        $dob            = $row['dob'];
        $street         = $row['street'];
        $town_city      = $row['town_city'];
        $postcode       = $row['postcode'];
        $country        = $row['country'];
        $contact        = $row['contact_no'];
        $email          = $row['email'];
        $pseudoname     = $row['pseudoname'];
        $picture        = $row['picture'];
        $active_status  = $row['active_status'];
      } // $row != NULL
    else {
      $errors['general_err'] = 'There was a problem accessing your profile.';
      echo "string";
    } // else
  } // else

  mysqli_close($conn);
?>

<!DOCTYPE html>
<html>

  <?php include('templates/header.php'); ?>

  <section class="container grey-text">
    <h4 class="center">Update Profile</h4>
    <form class="white" enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MM_MAXFILESIZE; ?>" />

        <div class="red-text right"><?php echo $errors['general_err']; ?></div>

        <legend>Personal Information</legend>
        
        <div class="red-text right"><?php echo $errors['firstname_err']; ?></div>
        <label for="firstname">First name:</label>
        <input type="text" id="firstname" name="firstname" value="<?php if (!empty($firstname)) echo htmlspecialchars($firstname); ?>" />
        
        <div class="red-text right"><?php echo $errors['lastname_err']; ?></div>
        <label for="lastname">Last name:</label>
        <input type="text" id="lastname" name="lastname" value="<?php if (!empty($lastname)) echo htmlspecialchars($lastname); ?>" />
                
        <div class="red-text right"><?php echo $errors['gender_err']; ?></div>
        <label for="gender">Gender:</label>
        <div class="input-group mb-3">
          <select name="gender" class="browser-default">
            <option selected>Choose your option</option>
            <option value="F">Female</option>
            <option value="M">Male</option>
            <option value="N">Rather not say</option>
          </select>
        </div>
        
        <div class="red-text right"><?php echo $errors['dob_err']; ?></div>
        <label for="birthdate">Birthdate:</label>
        <input type="date" class="datepicker" name="birthdate">
        
        <div class="red-text right"><?php echo $errors['pseudoname_err']; ?></div>
        <label for="pseudoname">Nickname:</label>
        <input type="text" id="pseudoname" name="pseudoname" value="<?php if (!empty($pseudoname)) echo htmlspecialchars($pseudoname); ?>" />
        
        <legend>Contact Information</legend>

        <div class="red-text right"><?php echo $errors['street_err']; ?></div>
        <label for="street">Street address:</label>
        <input type="text" id="street" name="street" value="<?php if (!empty($street)) echo htmlspecialchars($street); ?>" />
        
        <div class="red-text right"><?php echo $errors['town_city_err']; ?></div>
        <label for="town_city">Town/City:</label>
        <input type="text" id="town_city" name="town_city" value="<?php if (!empty($town_city)) echo htmlspecialchars($town_city); ?>" />
        
        <div class="red-text right"><?php echo $errors['postcode_err']; ?></div>
        <label for="postcode">Postcode:</label>
        <input type="text" id="postcode" name="postcode" value="<?php if (!empty($postcode)) echo htmlspecialchars($postcode); ?>" />
        
        <div class="red-text right"><?php echo $errors['email_err']; ?></div>
        <label for="email">Email address:</label>
        <input type="text" id="email" name="email" value="<?php if (!empty($email)) echo htmlspecialchars($email); ?>" />
        
        <div class="red-text right"><?php echo $errors['contact_err']; ?></div>
        <label for="contact">Contact number:</label>
        <input type="text" id="contact" name="contact" value="<?php if (!empty($contact)) echo htmlspecialchars($contact); ?>" />
        
        <div class="red-text right"><?php echo $errors['country_err']; ?></div>
        <label for="country">Country:</label>
        <input type="text" id="country" name="country" value="<?php if (!empty($country)) echo htmlspecialchars($country); ?>" />
              
        <div class="red-text right"><?php echo $errors['picture_err']; ?></div>
        
<!--         <label for="fileToUpload">Picture:</label>
        <input type="file" id="fileToUpload" name="fileToUpload" /> -->

        <div class="file-field input-field">
          <div class="btn brand z-depth-0">
            <span>Browse file</span>
            <input type="file" name="fileToUpload">
          </div>
          <div class="file-path-wrapper">
            <input class="file-path validate" type="text" placeholder="Upload profile picture">
          </div>
        </div>

        <div class="center">
        <?php if (!empty($picture)) {
          echo '<p><img class="profile" src="' . MM_UPLOAD_PROFILES_PATH . $picture . '" alt="Profile Picture" height="25%" width="25%" /></p>';
        } ?>
        </div>
        
      <div class="center">
        <input type="submit" name="submit" value="Update profile" class="btn brand z-depth=0">
      </div>
    </form>
  </section>

  <?php
    include ('templates/footer.php');
  ?>

</html>
