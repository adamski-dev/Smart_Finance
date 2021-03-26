<?php
session_start();

if (!isset($_SESSION['logged_user_id'])) {
	header('Location: index.php');
	exit();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<title>Smart Finance</title>
	<meta name="description" content="Control of personal finances">
	<meta name="keywords" content="finance, budget, savings, income, expense">
	<meta name="author" content="Adam Poplawski">
	
	<meta http-equiv="X-Ua-Compatible" content="IE=edge">
	
	<link rel="stylesheet" href="css/lock.css" type="text/css" >
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="main.css">
	
	
	<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
	
	
	<!--[if lt IE 9]>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
	<![endif]-->
	
</head>

<body>


	<header>
	
		<nav class="navbar navbar-light bg-color navbar-expand-md p-3">
		
			<a class="navbar-brand" href="index.html" target="_blank">Smart Finance</a>
			
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#nav_menu" aria-controls="nav_menu" aria-expanded="false" aria-label="navigation switch">
				<span class="navbar-toggler-icon"></span>
			</button>
			
			<div class="collapse navbar-collapse" id="nav_menu">
				
				<ul class="navbar-nav mr-auto">
				
					<li class="nav-item ml-auto mr-auto">
						<button class="menu_button" onclick="window.location.href = 'add_income.php';">Add income</button>
					</li>					
					
					<li class="nav-item ml-auto mr-auto">
						<button class="menu_button" onclick="window.location.href = 'add_expense.php';">Add expense</button>
					</li>					
					
					<li class="nav-item ml-auto mr-auto">
						<button class="menu_button" onclick="window.location.href = 'balance.php';">Balance</button>
					</li>					
					
					<li class="nav-item ml-auto mr-auto">
						<button class="menu_button" onclick="window.location.href = 'main_menu.php';">Settings</button>
					</li>					
					
					<li class="nav-item ml-auto mr-auto">
						<button class="menu_button" onclick="window.location.href = 'logout.php';">Log out</button>
					</li>
				
				</ul>
				
				
				
			</div>
			
		</nav>
		
	</header>
	
		<main>
		
		<section> 
			
				<div class="container">
					
					<div class="menu_container" style="">
					
							<div class="welcome"><p class="welcome_text"> “It Doesn't Matter How Much You Earn, It Is How Much You Keep"</p></div>
							<p class="welcome_message">I hope to help you control your finances by simply recording your incomes and expenses. </br> Please select from the above options to start.</br> Good Luck !</p>		
				
					</div>
					
				</div>
			
		</section>
	
		</main>
		
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	
	<script src="js/bootstrap.min.js"></script>
	
</body>
</html>