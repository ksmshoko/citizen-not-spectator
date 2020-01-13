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
    $op = mysqli_real_escape_string($conn, $_GET['like-dislike']);

    //make sql
    $sql = "SELECT id, no_of_views, no_of_likes, no_of_dislikes
        FROM cns_subscriber_submissions AS css
        WHERE (css.id = '$id')";

    //get the query result
    $result = mysqli_query($conn, $sql);

    //fetch the resulting row(s) as an array
    $submission = mysqli_fetch_assoc($result);

    if ($submission) {

      if ($op == "L") {
        //Increase likes
        $likes = $submission['no_of_likes'] + 1;

        //create sql
        $sql = "UPDATE cns_subscriber_submissions
          SET no_of_likes = $likes
          WHERE id = $id";

      } elseif ($op == "D") {
        //Increase num of dislikes
        $dislikes = $submission['no_of_dislikes'] + 1;

        //create sql
        $sql = "UPDATE cns_subscriber_submissions
          SET no_of_dislikes = $dislikes
          WHERE id = $id";
      }
      
      //free result from memory
      mysqli_free_result($result);

      //save to database and check
      if (mysqli_query($conn, $sql)) {
        //success

      } else {
        // error
        echo "Query error: " . mysqli_error($conn);
      }
    }
    //close connection
    mysqli_close($conn);

  }

  <?php
      $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
      header( "Location: ".$home_url );
      //header("Location: ". $home_url); 
    ?>
 ?>
