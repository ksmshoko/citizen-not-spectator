<?php
  session_start(); 

  //Connect to the database
  include('config/db_connect.php');

  //update login-in record by recording the date and time 
  echo "Login id => " . $_SESSION["login_id"];

  $log_id = $_SESSION["login_id"];
  echo "Login id => " . '$log_id';

  //create sql
  $sql = "UPDATE cns_subscriber_sessions
          SET when_logged_out = NOW()
          WHERE login_id = $log_id";

  //save to database and check
  if (mysqli_query($conn, $sql)) {
    //success
  } else {
    // error
    echo "Query error: " . mysqli_error($conn);
  }

  // Redirect to the home page

  $_SESSION["message"] = 'Good day, '. $_SESSION["username"] . ', you have been successfully signed out.';
  $log_message = $_SESSION["message"];
  $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/redirect.php';
  header('Location: ' . $home_url);

  // If the user is logged in, delete the session vars to log them out

  if (isset($_SESSION["user_id"])) {
    // Delete the session vars by clearing the $_SESSION array
    $_SESSION = array();

    // Delete the session cookie by setting its expiration to an hour ago (3600)
    if (isset($_COOKIE[session_name()])) {
      setcookie(session_name(), '', time() - 3600);
    }

    // Destroy the session
    session_destroy();
  }

  // Delete the user ID and username cookies by setting their expirations to an hour ago (3600)
  setcookie('user_id', '', time() - 3600);
  setcookie('username', '', time() - 3600);

  
?>
