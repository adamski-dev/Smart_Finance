<?php
session_start();

if (isset($_SESSION['logged_user_id'])) {
	header('Location: main_menu.php');
	exit();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="utf-8">
	<title>Smart Finance</title>
	<meta name="description" content="Control of personal finances">
	<meta name="keywords" content="finance, budget, savings, income, expense">
	<meta name="author" content="Adam Poplawski">
	
	<meta http-equiv="X-Ua-Compatible" content="IE=edge">
	
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="main.css" />
	<link rel="stylesheet" href="css/lock.css" type="text/css" />
	
	<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet" type='text/css' />
	
	<!--[if lt IE 9]>
	<script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
	<![endif]-->
	
</head>

<body>

		<main>
	
			<section class="login_container">
		
				<div class="container">
				
					<header>
					
						<h1> Achieve your financial goals with Smart Finance </h1>
						
					</header>
					
					<div class="row">
					
						<div class="col-md-12">
						
							<form method="post" action="login.php">
							
								<?php
									if(isset($_SESSION['error_message']))
									{
										echo $_SESSION['error_message'];
										unset($_SESSION['error_message']);
									}
								?>
								
								<input type="email" name = "login_email" placeholder="&#xe803;  Email address" style="font-family: 'lock';" onfocus="this.placeholder=''" onblur="this.placeholder='&#xe803;   Email address'" >
								<input type="password" name = "login_password" placeholder= "&#xe804;  Password" style="font-family: 'lock';" onfocus="this.placeholder=''" onblur="this.placeholder='&#xe804;   Password'" > 
								<input type="submit" value="Sign-in">
							
							</form>
					
							<footer>
						
								<div id="account_check"> Don't have an account? </div>
								<div id="sign_up"><a href="sign_up.php"> Sign-up </a></div>
						
							</footer>
						
						</div>
						
					</div>
					
				</div>
				
			</section>
		</main>
		
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	
	<script src="js/bootstrap.min.js"></script>
	
</body>
</html>