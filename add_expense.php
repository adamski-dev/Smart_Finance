<?php
	session_start();
	
		if(isset($_SESSION['logged_user_id'])){
	
		if(isset($_POST['amount_entry'])){
				
			$data_entry_validation = true;

			//expense amount entry validation		
			$expense_amount = $_POST['amount_entry'];
			
			if($expense_amount == ""){
				
				$data_entry_validation = false;
				$_SESSION['amount_error_message'] = '<div style ="color: red; font-size: 15px; text-align:center;"> Amount field must be filled out !</div>';
			}
				
		    //date validation
			$expense_date = $_POST['date_entry'];
			
			if(($expense_date < '2000-01-01') || ($expense_date > date('Y-m-d'))){
				
				$data_entry_validation = false;
				$_SESSION['date_error_message'] = '<div style ="color: red; font-size: 15px; text-align:center; margin-bottom: 15px;"> Date must be between 01/01/2000 and today </div>';
			}
			
			//sql transfer of expense data
			if($data_entry_validation == true){
				
				require_once 'db_connection.php';
				
				if($query = $database -> prepare ('INSERT INTO expenses VALUES (NULL, :user_id, :expense_category_assigned_to_user_id, :payment_method_assigned_to_user_id, :amount, :date_of_expense, :expense_comment)')){
					
					$query -> bindValue(':user_id', $_SESSION['logged_user_id'], PDO::PARAM_INT);
					$query -> bindValue(':expense_category_assigned_to_user_id', $_POST['payment_reason'], PDO::PARAM_INT);
					$query -> bindValue(':payment_method_assigned_to_user_id', $_POST['payment_methods'], PDO::PARAM_INT);
					$query -> bindValue(':amount', $expense_amount, PDO::PARAM_STR);
					$query -> bindValue(':date_of_expense', $expense_date, PDO::PARAM_STR);
					$query -> bindValue(':expense_comment', $_POST['expense_comment'], PDO::PARAM_STR);
					$query -> execute();
					
					$_SESSION['expense_transferred'] = '<div style ="color: green; font-size: 18px; text-align:center; margin-bottom: 15px;"> Expense added successfully </br> Thank You </div>';
				}
			}


		}	
	} else {
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
		
			<a class="navbar-brand" href="index.php" target="_blank">Smart Finance</a>
			
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
					
					<div class="incomes_container">
					
							<h4>Expense details entry</h4>
				
						<form method="post">
							<div class="form-group">
								<label for="expense_amount_input">Amount</label>
								<input type="number" name="amount_entry" step="0.1" min="0.1" class="form-control form-control-sm" id="expense_amount_input" <?= isset($_SESSION['date_error_message']) ? 'value="' .$_POST['amount_entry'] . '"' : 'placeholder="enter amount here"'?>>
							</div>
							  	<?php
									if(isset($_SESSION['amount_error_message'])){
										echo $_SESSION['amount_error_message'];
										unset($_SESSION['amount_error_message']);}
								?>
							<div class="form-group">
								<label for="expense_date_input">Date</label>
								<input type="date" name="date_entry" class="form-control form-control-sm mb-4" id="expense_date_input">
							</div>
							  	<?php
									if(isset($_SESSION['date_error_message'])){
										echo $_SESSION['date_error_message'];
										unset($_SESSION['date_error_message']);}
								?>
							  
							<div class="form-group">
								<label for="payment_method_selection">Payment method</label>
								<select name="payment_methods" class="form-control form-control-sm" id="payment_method_selection">
									<?php
										require_once 'db_connection.php';			
										if($query = $database -> prepare ('SELECT id, name FROM payment_methods_assigned_to_users WHERE user_id = :user_id')){
											
											$query -> bindValue(':user_id', $_SESSION['logged_user_id'], PDO::PARAM_INT);
											$query -> execute();		
											
											while ($mysql = $query -> fetch()) 
											{
												echo "<option value={$mysql['id']}>{$mysql['name']}</option>";
												//echo "<option value=".$mysql['id'].">".$mysql['name']."</option>";
											}	
										}	
									?>								
								</select>
							</div>
							
							<div class="form-group">
								<label for="payment_reason_selection">Payment reason</label>
								<select name="payment_reason" class="form-control form-control-sm" id="payment_reason_selection">
									<?php
										require_once 'db_connection.php';			
										if($query = $database -> prepare ('SELECT id, name FROM expenses_category_assigned_to_users WHERE user_id = :user_id')){
											
											$query -> bindValue(':user_id', $_SESSION['logged_user_id'], PDO::PARAM_INT);
											$query -> execute();		
											
											while ($mysql = $query -> fetch()) 
											{
												echo "<option value={$mysql['id']}>{$mysql['name']}</option>";
												//echo "<option value=".$mysql['id'].">".$mysql['name']."</option>";
											}	
										}	
									?>
								</select>
							</div>
								
							  <div class="form-group">
								<label for="comments_field">Comments</label>
								<textarea name="expense_comment" class="form-control form-control-sm mt-2 mb-4" id="comments_field" rows="4"></textarea>
							  </div>
							  	<?php
									if(isset($_SESSION['expense_transferred'])){
										echo $_SESSION['expense_transferred'];
										unset($_SESSION['expense_transferred']);}
								?>
							  
							<button class="btn btn-color_custom ml-4 mr-5" type="submit">Confirm</button>
							<button class="btn btn-warning" type="reset" onclick="window.location.href = 'main_menu.php';">Cancel</button>
							  
						</form>
						
					</div>
					
				</div>
			
			</section>
	
		</main>
		
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	
	<script src="js/bootstrap.min.js"></script>
	
</body>
</html>