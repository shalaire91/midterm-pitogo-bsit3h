<?php
// Include config file
include_once "config.php";

$username = $password = $confirm_password = $email = "";
$username_err = $password_err = $confirm_password_err = $email_err = "";
$time = date("H:i:s");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate username
  if (empty(trim($_POST['uname']))) {
    $username_err = "Please enter a username.";
  } else {
    $sql = "SELECT username FROM users WHERE username = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
      mysqli_stmt_bind_param($stmt, "s", $param_username);
      $param_username = trim($_POST['uname']);
      if (mysqli_stmt_execute($stmt)) {

        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) == 1) {
          $username_err = "This username is already taken.";
        } else {
          $username = trim($_POST['uname']);
        }
      } else {
        echo "Oops! Something went wrong. Please try again later.";
      }
      mysqli_stmt_close($stmt);
    }
  }

  // Validate password
  $password = $_POST['psw'];
  $uppercase = preg_match('@[A-Z]@', $password);
  $lowercase = preg_match('@[a-z]@', $password);
  $number    = preg_match('@[0-9]@', $password);
  $specialChars = preg_match('@[^\w]@', $password);
  if (empty($password)) {
    $password_err = "Please enter a password.";
  } elseif (strlen(trim($_POST['psw'])) < 8) {
    $password_err = "Password should be at least 8 characters";
  } elseif (!$uppercase) {
    $password_err = "Password should contain 1 upper case.";
  } elseif (!$lowercase) {
    $password_err = "Password should contain 1 lower case.";
  } elseif (!$number) {
    $password_err = "Password should contain 1 number.";
  } elseif (!$specialChars) {
    $password_err = "Password should contain 1 special character.";
  } else {
    $password = trim($_POST['psw']);
  }

  // Validate confirm password
  if (empty(trim($_POST['psw-repeat']))) {
    $confirm_password_err = "Please enter confirm password.";
  } else {
    $confirm_password = trim($_POST['psw-repeat']);
    if (empty($password_err) && ($password != $confirm_password)) {
      $confirm_password_err = "Password did not match.";
    }
  }

  //Validate email
  if (empty(trim($_POST['email']))) {
    $email_err = "Please enter email.";
  } else {
    $email = trim($_POST['email']);
  }

  // Check input errors before inserting in database
  if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {

    // Prepare an insert statement
    $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
    $sql1 = "INSERT INTO activity_log (activity, username) VALUES ('Created an Account', $username)";
    if ($stmt = mysqli_prepare($link, $sql)) {
      // Bind variables to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_password, $email);

      // Set parameters
      $param_username = $username;
      $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
      $email = $_REQUEST['email'];

      // Attempt to execute the prepared statement
      if (mysqli_stmt_execute($stmt)) {

       // Redirect to login page
        header("location: login.php");

        // prepare and bind
        $stmt1 = $link->prepare("INSERT INTO activity_log (activity, username) VALUES (?, ?)");
        $stmt1->bind_param("ss", $activity, $username);

        // set parameters and execute
        $activity = "Created an Account";
        $username = $username;
        
        $stmt1->execute();

        $stmt1->close();
      } 
      else {
        echo "Something went wrong. Please try again later.";
      }
      mysqli_stmt_close($stmt);
    }
  }
  mysqli_close($link);
}
?>