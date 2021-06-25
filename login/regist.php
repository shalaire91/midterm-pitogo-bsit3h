<?php include('signup-check.php')
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
<title>REGISTRATION PAGE</title>
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
              <input type="text" placeholder="Enter Username" name="uname" id="uname" class="form-control" value="<?php echo $username; ?>">
              <span class="help-block"><?php echo $username_err; ?></span>
          </div>

          <div class="input-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
              <input type="email" placeholder="Enter Email" name="email" id="email">
              <span class="help-block"><?php echo $email_err; ?></span>
          </div>

          <div class="input-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
              <input type="password" placeholder="Enter Password" name="psw" id="psw" class="form-control" value="<?php echo $password; ?>">
              <span class="help-block"><?php echo $password_err; ?></span>
          </div>

          <div class="input-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
              <input type="password" placeholder="Confirm Password" name="psw-repeat" id="psw-repeat" class="form-control" value="<?php echo $confirm_password; ?>">
              <span class="help-block"><?php echo $confirm_password_err; ?></span>
          </div>
        
      <div class="input-group">
            <button type="submit" name="submit" class="registerbtn">Register</button>
          <p>Already have an account? <a href="login.php">Sign in</a></p>
  </form>
            <p>&nbsp;</p>
</div>
</body>
</html>
