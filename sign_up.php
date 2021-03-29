<?php
	session_start();
	
	if(isset($_POST['user_name']))
	{
		//validation successful - set flag on true
		$validation_successful = true;
		
		//username string length verification
		$user_name = $_POST['user_name'];
		if((strlen($user_name)<3) || (strlen($user_name)>15))
		{
			$validation_successful = false;
			$_SESSION['error_message'] = '<div style ="color: red; font-size: 15px;"> Username must contain between 3 and 15 characters !</div>';	
		}
		if(!ctype_alnum($user_name))
		{
			$validation_successful = false;
			$_SESSION['error_message'] = '<div style ="color: red; font-size: 15px;"> Username can only contain letters and numbers !</div>';
		}
		
		//email validation
		if(empty($email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL)))
		{
			$validation_successful = false;
			$_SESSION['error_message'] = '<div style ="color: red; font-size: 15px;"> Email address format is invalid !</div>';
		} 
		
		// passwords validation
		$password_1 = $_POST['password_1'];
		$password_2 = $_POST['password_2'];
		
		if((strlen($password_1)<8) || (strlen($password_1)>20))
		{
			$validation_successful = false;
			$_SESSION['error_message'] = '<div style ="color: red; font-size: 15px;"> Password must contain between 8 and 20 characters !</div>';
		}
		if($password_1 != $password_2)
		{
			$validation_successful = false;
			$_SESSION['error_message'] = '<div style ="color: red; font-size: 15px;"> The passwords entered are not the same !</div>';
		}	
	
		//password encryption
		$hashed_password = password_hash($password_1, PASSWORD_DEFAULT);
		
		//database connection 
		require_once 'db_connection.php';
		
		if (!isset($_SESSION['server_error']))
		{
			//database email presence check
			$user_query = $database -> prepare ('SELECT id FROM users WHERE email = :email');
			$user_query -> bindValue(':email', $email, PDO::PARAM_STR);
			$user_query -> execute();
			$email_present = $user_query -> fetch();
			
			if($email_present)
			{
				$validation_successful = false;
				$_SESSION['error_message'] = '<div style ="color: red; font-size: 15px;"> This email already exists !</div>';
			}
			
			//database user name presence check
			$user_query = $database -> prepare ('SELECT id FROM users WHERE username = :username');
			$user_query -> bindValue(':username', $user_name, PDO::PARAM_STR);
			$user_query -> execute();
			$username_present = $user_query -> fetch();
			
			if($username_present)
			{
				$validation_successful = false;
				$_SESSION['error_message'] = '<div style ="color: red; font-size: 15px;"> This username already exists !</div>';
			}
			
			//write new user to database
			if($validation_successful)
			{
				$query = $database -> prepare ('INSERT INTO users VALUES (NULL, :username, :password, :email)');
				$query -> bindValue(':username', $user_name, PDO::PARAM_STR);
				$query -> bindValue(':password', $hashed_password, PDO::PARAM_STR) ;
				$query -> bindValue(':email', $email, PDO::PARAM_STR) ;
				
				if($query -> execute()) //assigned to users database tables population
				{
					$user_query = $database -> prepare('SELECT id FROM users WHERE email = :email');
					$user_query -> bindValue(':email', $email, PDO::PARAM_STR);
					$user_query -> execute();
					$user = $user_query -> fetch();
					$user_id = $user['id'];
					
					if($query = $database -> prepare ('SELECT * FROM incomes_category_default'))
					{
						$query -> execute();
						while ($database_data_row = $query -> fetch()) 
						{
							$user_query = $database -> prepare ('INSERT INTO incomes_category_assigned_to_users VALUES (NULL, :user_id, :name)');
							$user_query -> bindValue(':user_id', $user_id, PDO::PARAM_INT);
						    $user_query -> bindValue(':name', $database_data_row['name'], PDO::PARAM_STR);
							$user_query -> execute();
						}
					}
					
					if($query = $database -> prepare ('SELECT * FROM expenses_category_default'))
					{
						$query -> execute();
						while ($database_data_row = $query -> fetch()) 
						{
							$user_query = $database -> prepare ('INSERT INTO expenses_category_assigned_to_users VALUES (NULL, :user_id, :name)');
							$user_query -> bindValue(':user_id', $user_id, PDO::PARAM_INT);
						    $user_query -> bindValue(':name', $database_data_row['name'], PDO::PARAM_STR);
							$user_query -> execute();
						}
					}
					
					if($query = $database -> prepare ('SELECT * FROM payment_methods_default'))
					{
						$query -> execute();
						while ($database_data_row = $query -> fetch()) 
						{
							$user_query = $database -> prepare ('INSERT INTO payment_methods_assigned_to_users VALUES (NULL, :user_id, :name)');
							$user_query -> bindValue(':user_id', $user_id, PDO::PARAM_INT);
						    $user_query -> bindValue(':name', $database_data_row['name'], PDO::PARAM_STR);
							$user_query -> execute();
						}
						//registration OK flag
						$_SESSION['registration_OK'] = '<div style ="color: green; font-size: 15px;"> Registration successful </br> Sign-in from home page </div>';
					}
				}
			}
		}
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
						
							<form method = "post">
								<?php
									if(isset($_SESSION['error_message']))
									{
										echo $_SESSION['error_message'];
										unset($_SESSION['error_message']);
									}
									if(isset($_SESSION['registration_OK']))
									{
										echo $_SESSION['registration_OK'];
										unset($_SESSION['registration_OK']);
									}
								?>
								<input type="text" name="user_name" placeholder="&#xe800;   Username" style="font-family: 'lock';" onfocus="this.placeholder=''" onblur="this.placeholder='&#xe800;   Username'" >
								<input type="email" name="email" placeholder="&#xe803;   Email address" style="font-family: 'lock';" onfocus="this.placeholder=''" onblur="this.placeholder='&#xe803;   Email address'">
								<input type="password" name="password_1" placeholder= "&#xe804;   Password" style="font-family: 'lock';" onfocus="this.placeholder=''" onblur="this.placeholder='&#xe804;   Password'" > 
								<input type="password" name="password_2"placeholder= "&#xe804;   Confirm password" style="font-family: 'lock';" onfocus="this.placeholder=''" onblur="this.placeholder='&#xe804;   Confirm password'" > 
								<input type="submit" value="Sign-up">  
							
							</form>
					
							<footer>
								<div id="back_to_home_page"><a href="index.php" style="color: #000;"> Back to home page </a></div>
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