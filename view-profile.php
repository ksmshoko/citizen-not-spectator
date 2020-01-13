<?php
  session_start();
  // Insert the page header
  $page_title = 'View Profile';

  //connect to database
  include('config/db_connect.php');

  //get constants for file uploads
  include('config/appconstants.php');

  if (!isset($_SESSION["user_id"])) { // End of check for form submission
    // not logged in
    $_SESSION["message"] = 'You must be logged in to view your profile.';

    $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/redirect.php';
    header('Location: '. $home_url);
    exit();  
  } else {

    $firstname = $lastname = $email = $street = $town_city = $postcode = $country = 
          $pseudoname = $contact = $gender =  $picture = $dob ='';

    // Grab the profile data from the database
    $sql = "SELECT * FROM cns_subscriber WHERE user_id = '" . $_SESSION["user_id"] . "'";
    $data = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($data);

    if ($row) {
      $firstname    = $row['firstname'];
      $lastname     = $row['lastname'];
      $gender       = $row['gender'];
      $dob          = $row['dob'];
      $street       = $row['street'];
      $town_city    = $row['town_city'];
      $postcode     = $row['postcode'];
      $country      = $row['country'];
      $contact      = $row['contact_no'];
      $email        = $row['email'];
      $pseudoname   = $row['pseudoname'];
      $picture      = $row['picture'];

    } else {
      $_SESSION["message"] = 'There was an error accessing your profile.';

      $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/redirect.php';
      header('Location: '. $home_url);
      exit();
    } // else
  } // else

  mysqli_close($conn);   


?>

<!DOCTYPE html>
<html>

  <?php include('templates/header.php'); ?>

  <section class="container grey-text">
    <h4 class="center">Profile - <?php echo $_SESSION["firstname"] . ' ' . $_SESSION["lastname"]; ?></h4>
    <form class="white" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      
      <div class="row">
        <div class="input-field col s12">
          <label >Profile picture</label>
          <?php if (!empty($picture)) {
              echo '<p><img class="circle responsive-img profile" src="' . MM_UPLOAD_PROFILES_PATH . $picture . '" alt="Profile Picture" height="25%" width="25%" align="right"/></p>';
            }  else {
              echo '<p><img class="circle responsive-img profile" src="' . MM_UPLOAD_IMAGES_PATH . 'no_img.jpg' . '" alt="Image" height="100" width="100" align="right"/></p>';
            } ?>
        </div>
      </div>
      <hr/>

      <legend>Personal Information</legend>
      
      <div class="row">
        <div class="input-field col s12">
          <input disabled type="text" class="validate" value="<?php if (!empty($firstname)) echo htmlspecialchars($firstname); ?>">
          <label >Firstname</label>
        </div>
      </div>

      <div class="row">
        <div class="input-field col s12">
          <input disabled type="text" class="validate" value="<?php if (!empty($lastname)) echo htmlspecialchars($lastname); ?>">
          <label >Lastname</label>
        </div>
      </div>

      <div class="row">
        <div class="input-field col s12">
          <input disabled type="text" class="validate" value="
          <?php 

            if (!empty($gender)) {
              if ($gender == 'M') {
                echo 'Male';
              }
              else if ($gender == 'F') {
                echo 'Female';
              }
              else {
                echo 'Rather not say';
              }
            }
             ?> ">
          <label >Gender</label>
        </div>
      </div>

      <div class="row">
        <div class="input-field col s12">
          <input disabled type="text" class="validate" value="<?php if (!empty($dob)) echo htmlspecialchars($dob); ?>">
          <label >Birthdate</label>
        </div>
      </div>

      <div class="row">
        <div class="input-field col s12">
          <input disabled type="text" class="validate" value="<?php if (!empty($pseudoname)) echo htmlspecialchars($pseudoname); ?>">
          <label >Nickname</label>
        </div>
      </div>

    <legend>Contact Information</legend>

      <div class="row">
        <div class="input-field col s12">
          <input disabled type="text" class="validate" value="<?php if (!empty($street)) echo htmlspecialchars($street); ?>">
          <label >Street</label>
        </div>
      </div>
        
      <div class="row">
        <div class="input-field col s12">
          <input disabled type="text" class="validate" value="<?php if (!empty($town_city)) echo htmlspecialchars($town_city); ?>">
          <label >City/Town</label>
        </div>
      </div>   

      <div class="row">
        <div class="input-field col s12">
          <input disabled type="text" class="validate" value="<?php if (!empty($postcode)) echo htmlspecialchars($postcode); ?>">
          <label >Postcode</label>
        </div>
      </div>

      <div class="row">
        <div class="input-field col s12">
          <input disabled type="text" class="validate" value="<?php if (!empty($country)) echo htmlspecialchars($country); ?>">
          <label >Country</label>
        </div>
      </div>

      <div class="row">
        <div class="input-field col s12">
          <input disabled type="text" class="validate" value="<?php if (!empty($contact)) echo htmlspecialchars($contact); ?>">
          <label >Mobile number</label>
        </div>
      </div>

      <div class="row">
        <div class="input-field col s12">
          <input disabled type="text" class="validate" value="<?php if (!empty($email)) echo htmlspecialchars($email); ?>">
          <label >Email address</label>
        </div>
      </div>
      
      <div class="center">
        <div class="nav-wrapper center">
          <nav class="sign-in-up-nav center">
            <ul id="nav-mobile" class="right hide-on-small-and-down">
                <li><a href="update-profile.php?user_id=<?php echo $row['user_id'] ?>" class="btn brand z-depth-0">Update profile</a></li>
            </ul>
          </nav>
        </div>
      </div>

    </form>
  </section>

  <?php
    include ('templates/footer.php');
  ?>

</html>
