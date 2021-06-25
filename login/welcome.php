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
		background-image: url('https://i.pinimg.com/originals/d2/df/fe/d2dffe9fdd0b8a2df874db23aae6329e.gif');
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
<?php
include_once 'config.php';

session_start();
//Logout log
$username = $_SESSION['username'];
if ($_SERVER["REQUEST_METHOD"] == "POST"){
  
  mysqli_query($link,"INSERT INTO activity_log (activity,username) VALUES('Logged out','$username')");
    

    
  header('location: login.php');
}

?>
</head>
<body>
<div class="container">
	<div class="d-flex justify-content-center h-100">
		<div class="card">
			<div class="card-header">
				<h1 style="font-size: 30px;font-family: 'Press Start 2P', cursive;color: white;text-align: center;">WELCOME <?php echo $username ?></h1>
			</div>
			<div class="card-body">
				<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
					<div class="input-group form-group">
						<center>
							<h4 style="color: white;">Thanks for logging in</h4>
						</center>
					</div>
					<div class="form-group">
						<button type="submit" name="submit" id="submit" class="btn btn-danger">Logout</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
</body>
</html>