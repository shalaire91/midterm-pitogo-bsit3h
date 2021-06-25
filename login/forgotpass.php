<?php
include_once "config.php";

$username = $password = $confirm_password =  "";
$username_err = $password_err = $confirm_password_err =  "";

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
          $username = trim($_POST['uname']);
        } else {
          $username_err = "There is no account with that username";
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
    $password_err = "Password must have atleast 8 characters.";
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


  // Check input errors before inserting in database
  if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {
    $sql = "UPDATE users SET password = ? WHERE username = ?";
    
    if ($stmt = mysqli_prepare($link, $sql)) {
      mysqli_stmt_bind_param($stmt, "ss", $param_password, $param_username);
      // Creates a password hash
      $param_password = password_hash($password, PASSWORD_DEFAULT); 
      $param_username = $username;
      if (mysqli_stmt_execute($stmt)) {
         $stmt1 = $link->prepare("INSERT INTO activity_log (activity, username) VALUES (?, ?)");
         $stmt1->bind_param("ss", $activity, $username);
         $activity = "Reset a Password";
         $username = $username;
        
         $stmt1->execute();
         $stmt1->close();
        header("location: login.php");
      } else {
        echo "Something went wrong. Please try again later.";
      }
      mysqli_stmt_close($stmt);
    }
  }
  mysqli_close($link);
}
?>
<!DOCTYPE html>
<html>
<style>
.center {
  display: block;
  margin-left: auto;
  margin-right: auto;
}
</style>
<title>Reset Password</title>
<head>  
<link href="style.css" rel="stylesheet" type="text/css" />
<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro' rel='stylesheet' type='text/css'>
</head>
<body style="background-image:url(we.gif);background-attachment: fixed; background-repeat: no-repeat; background-position: absolute; top: 0; left: 0; background-size: cover; ">
  </br>
  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      <img src="img.png"  width="150" height="130"  class="center" >
      <h2 style="text-align: center;"> Rest Password</h2>
        <div class="input-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
            <label for="uname"><b>Username</b></label>
              <input type="text" placeholder="Enter Username" name="uname" id="uname" class="form-control" value="<?php echo $username; ?>">
              <span class="help-block"><?php echo $username_err; ?></span>
        </div>
        <div class="input-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
            <label for="psw"><b>New Password</b></label>
            <input type="password" placeholder="Enter Password" name="psw" id="psw" class="form-control" value="<?php echo $password; ?>">
            <span class="help-block"><?php echo $password_err; ?></span>
        </div>
        <div class="input-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
            <label for="psw-repeat"><b>Confirm Password</b></label>
            <input type="password" placeholder="Repeat Password" name="psw-repeat" id="psw-repeat" class="form-control" value="<?php echo $confirm_password; ?>">
            <span class="help-block"><?php echo $confirm_password_err; ?></span>
        </div>        
      <div class="input-group">
            <button type="submit" name="submit" class="registerbtn">Reset Password</button>
          <p>Already have an account? <a href="login.php">Sign in</a></p>
  </form>
            <p>&nbsp;</p>
</div>
</body>
</html>
