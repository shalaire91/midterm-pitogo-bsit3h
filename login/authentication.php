<?php
include_once 'config.php';
// Initialize the session
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] === false) {
    header('location: login.php');
    exit;
} else if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
    header('location: welcome.php');
    exit;
}

$user_id = $_SESSION['id'];
$authentication_code = "";
$authentication_user = $authentication_err = "";

if($_SERVER['REQUEST_METHOD'] == 'POST'){

// Prepare a select statement for code
$sql = "SELECT code FROM date_auth WHERE user_id = $user_id AND NOW() >= time_added AND NOW() <= expiration ORDER BY id DESC limit 1";
$result = $link->query($sql);



//result for Code
if ($result->num_rows >= 1) {
    if ($row = $result->fetch_assoc()) {
        $authentication_code = $row['code'];
    if(empty(trim($_POST['codes']))){
        $authentication_err = "Please enter Code";
    }
    else{
        $authentication_user = trim($_POST['codes']);
    }
    if(empty($authentication_err)){
        if($authentication_code === $row['code']){
            $_SESSION['authenticated'] = true;
                        $stmt1 = $link->prepare("INSERT INTO activity_log (activity, username) VALUES (?, ?)");
                           $stmt1->bind_param("ss", $activity, $username);
                           $activity = "Success Log in";
                           $username = $_SESSION['username'];
                           $stmt1->execute();
                           $stmt1->close();
               header('location: welcome.php');
        }
        else{
            $authentication_err = "Incorrect Code!";
        }

    }
}
    else{
        echo "Something went wrong";
    }
}
}
$link->close();
?>

<!DOCTYPE html>
    <title>Authentication Code</title>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title> 
    <!--Bootsrap 4 CDN-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SdAmvia1qwwkc2ssfu0sy7c6qhr8e4curh64j8vglc0pz0mLYfxhZEccW8ERdknLPMO" crossorigin="anonymous">
    
    <!--Fontawesome CDN-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

    <!--Custom styles-->

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=0.8">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Lobster&family=Press+Start+2P&display=swap" rel="stylesheet">
    <style type="text/css">
        @import url('https://fonts.googleapis.com/css?family=Numans');

        html,body{
        background-image: url('https://media.giphy.com/media/pVGsAWjzvXcZW4ZBTE/giphy.gif');
        background-size: cover;
        background-repeat: no-repeat;
        height: 100%;
        font-family: 'Numans', sans-serif;
        text-align: center !important;
        }

        .container{
        height: 100%;
        align-content: center;
        }

        .card{
        margin-top: auto;
        margin-bottom: auto;
        width: 400px;
        background-color: rgba(0,0,0,0.5) !important;
        }

        .card-header h3{
        color: white;
        }

        .remember{
        color: white;
        }

        .remember input{
        width: 20px;
        height: 20px;
        margin-left: 15px;
        margin-right: 5px;
        }

        .login_btn{
        color: black;
        background-color: #02A8fA;
        width: 100px;
        }

        .login_btn:hover{
        color: black;
        background-color: white;
        }
    </style>

<body>
    <div class="container">
        <div class="d-flex justify-content-center h-100">
            <div class="card">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="card-header">
                        <label><h1 style="font-size: 30px;font-family: 'Press Start 2P', cursive;color: white;text-align: center;"> <b>Please Enter "<?php echo $authentication_code ?>" </b> To continue login</h1></label>
                            <h3><b>Please click button to Show Code</b></h3>
                                <input type="number" name="codes" placeholder="Enter Code" id="codes">
                                <h5><span class="help-block"><?php echo $authentication_err; ?></span></h5>
                            <p>
                                <button class="btn btn-primary" name="submit" id="submit" type="submit">Login</button>
                                <button class="btn btn-primary" name="submit" id="show_code" type="submit">Show Code</button>
                                <a href="login.php" class="btn btn-danger">Logout</a>
                            </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>