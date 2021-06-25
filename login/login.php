<?php
date_default_timezone_set("Asia/Manila");
session_start();

// Include config file

include_once "config.php";
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Check if username is empty
  if (empty(trim($_POST['uname']))) {
    $username_err = "Please enter username.";
  } else {
    $username = trim($_POST['uname']);
  }

  // Check if password is empty
  if (empty(trim($_POST['psw']))) {
    $password_err = "Please enter your password.";
  } else {
    $password = trim($_POST['psw']);
  }

  // Validate credentials
  if (empty($username_err) && empty($password_err)) {
    // Prepare a select statement
    $sql = "SELECT id, username, password FROM users WHERE username = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
      mysqli_stmt_bind_param($stmt, "s", $param_username);
      $param_username = $username;

      if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_store_result($stmt);

        // Check if username exists, if yes then verify password
        if (mysqli_stmt_num_rows($stmt) == 1) {
          mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
          if (mysqli_stmt_fetch($stmt)) {
            
            if (password_verify($password, $hashed_password)) {
              // Store data in session variables
              $_SESSION["loggedin"] = true;
              $_SESSION["id"] = $id;
              $_SESSION["username"] = $username;
              $_SESSION['authenticated'] = false;
              $user_id = $_SESSION['id'];
              $code = rand(100000, 999999);
              $dateTime = new DateTime();
              $dateTimeFormat = 'Y-m-d H:i:s';
              $time = $dateTime->format($dateTimeFormat);
              $dateTime->add(new DateInterval('PT5M'));
              $expiration = $dateTime->format($dateTimeFormat);

              /*$sql = "INSERT INTO date_auth (user_id, code, time_added, expiration) VALUES ('$user_id', '$code', '$time', '$expiration')";*/

              $stmt1 = $link->prepare("INSERT INTO date_auth (user_id, code, time_added, expiration) VALUES (?, ?, ?, ?)");
              $stmt1->bind_param("ssss", $param_id, $param_code,$param_time, $param_expiration);

              $param_id = $user_id;
              $param_code = $code;
              $param_time = $time;
              $param_expiration = $expiration;
              
              $stmt1->execute();

               $stmt1 = $link->prepare("INSERT INTO activity_log (activity, username) VALUES (?, ?)");
               $stmt1->bind_param("ss", $activity, $username);

               $activity = "Attempted Log in";
               $username = $username;
              
               $stmt1->execute();
               $stmt1->close();

              header("location: authentication.php");
            } else {
              $password_err = "The password you entered was not valid.";
            }
          }
        } else {
          $username_err = "No account have this username.";
        }
      } else {
        echo "Oops! Something went wrong. Please try again later.";
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
<title>LOGIN PAGE</title>
<head>  
<link href="style.css" rel="stylesheet" type="text/css" />
<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro' rel='stylesheet' type='text/css'>
</head>
<body style="background-image:url(we.gif);background-attachment: fixed; background-repeat: no-repeat; background-position: absolute; top: 0; left: 0; background-size: cover; ">
  </br>
   <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
		<img src="img.png"  width="150" height="130"  class="center" >
    <h2 align="center">Sign in to your Account</h2>
    <div class="input-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
      <input type="text" name="uname" class="form-control" placeholder="Enter Username" value="<?php echo $username; ?>" >
       <span class="help-block"><?php echo $username_err; ?></span>
    </div>
     <div class="input-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
      <input ttype="password" placeholder="Enter Password" name="psw" class="form-control">
      <span class="help-block"><?php echo $password_err; ?></span>
    </div>
    <div class="input-group">
      <a href="forgotpass.php">Forgot Password?</a>
      <button type="submit" class="btn" name="submit">Login</button>
    </div>
    <p>
      Don't have one? <a href="regist.php">Create an Account</a>
    </p>
  </form>
  <p>&nbsp;</p>
</body>
</html>