<?php
	session_start();
	if(isset($_SESSION['logged_user_id'])){
			
		$dates_validation = true;
		$dates_selection = false;
		
			if((isset($_POST['start_date'])) && (isset($_POST['end_date']))){
		
				//date validation
				$start_date = $_POST['start_date'];
				$end_date = $_POST['end_date'];
				
				if(($start_date < '2000-01-01') || ($start_date > date('Y-m-d')) || ($end_date < '2000-01-01') || ($end_date > date('Y-m-d'))) {
					
					$dates_validation = false;
					$_SESSION['date_error_message'] = "<div style ='color: red; font-size: 15px; margin: 5px; display: inline-block;'> Dates entered must be between 01/01/2000 and today ! </div>";
				} 
				
				if($start_date > $end_date ) {
					
					$dates_validation = false;
					$_SESSION['date_error_message'] = "<div style ='color: red; font-size: 15px; margin: 5px; display: inline-block;'> Selected 'Date from' must be before 'Date to' ! </div>";
				}
				
				//incomes and expenses data pull from database
				if ($dates_validation == true) {
					require_once 'db_connection.php';  
					
					//incomes data pull by category name and sum of individual incomes
					if($query = $database -> prepare ('SELECT name, SUM(amount) AS sum_of_incomes FROM incomes, incomes_category_assigned_to_users WHERE incomes.user_id = :user_id AND date_of_income BETWEEN :start_date AND :end_date AND incomes.user_id = incomes_category_assigned_to_users.user_id AND incomes.income_category_assigned_to_user_id = incomes_category_assigned_to_users.id GROUP BY name ORDER BY sum_of_incomes DESC')){
											
						$query -> bindValue(':user_id', $_SESSION['logged_user_id'], PDO::PARAM_INT);
						$query -> bindValue(':start_date', $start_date, PDO::PARAM_STR);
						$query -> bindValue(':end_date', $end_date, PDO::PARAM_STR);
						$query -> execute();
						$incomes_data = $query -> fetchAll();
						$sum_of_incomes = 0;
					}
					//expenses data pull by category name and sum of individual expenses
					if($query = $database -> prepare ('SELECT name, SUM(amount) AS sum_of_expenses FROM expenses, expenses_category_assigned_to_users WHERE expenses.user_id = :user_id AND date_of_expense BETWEEN :start_date AND :end_date AND expenses.user_id = expenses_category_assigned_to_users.user_id AND expenses.expense_category_assigned_to_user_id = expenses_category_assigned_to_users.id GROUP BY name ORDER BY sum_of_expenses DESC')){
											
						$query -> bindValue(':user_id', $_SESSION['logged_user_id'], PDO::PARAM_INT);
						$query -> bindValue(':start_date', $start_date, PDO::PARAM_STR);
						$query -> bindValue(':end_date', $end_date, PDO::PARAM_STR);
						$query -> execute();
						$expenses_data = $query -> fetchAll();
						$sum_of_expenses = 0;
					}
					$dates_selection = true;
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
	<script>
		window.onload = function()
		{
			var chart1 = new CanvasJS.Chart("incomes_pie_chart", 
				{
					backgroundColor: "transparent",
					animationEnabled: true, 
					theme: "light1",
					title: 
					{
						text: "incomes summary",
						fontSize: 18,
						fontWeight: "bold",
						fontColor: "black",
						fontFamily: "roboto",
					},
					data: [
					{
						type: "pie",
						startAngle: 270,
						yValueFormatString: "0.00\"\"",
						indexLabel: "{label} {y}",
						dataPoints: 
						[
							<?php
								if($dates_selection == true){
									foreach ($incomes_data as $row){
									echo "{y:{$row['sum_of_incomes']}, label:'{$row['name']}'},"; 
									}
								}else {
									$_SESSION['no_incomes_message'] = "<div style ='color: black; font-size: 15px; margin: 15px; text-align: center;'> No incomes data - select date range please </div>";
								}																																 
							?>																	
						]
					}]
				});		
		var chart2 = new CanvasJS.Chart("expenses_pie_chart", 
					{
						backgroundColor: "transparent",
						animationEnabled: true,
						theme: "light1",					
						title: 
						{
							text: "expenses summary",
							fontSize: 18,
							fontWeight: "bold",
							fontColor: "black",
							fontFamily: "roboto",
						},
						data: [
						{
							type: "pie",
							startAngle: 117,
							yValueFormatString: "0.00\"\"",
							indexLabel: "{label} {y}",
							dataPoints: 
							[
								<?php		
									if($dates_selection == true){
										foreach ($expenses_data as $row){
										echo "{y:{$row['sum_of_expenses']}, label:'{$row['name']}'},"; 
										}	
									}else {
										$_SESSION['no_expenses_message'] = "<div style ='color: black; font-size: 15px; margin: 15px; text-align: center;'> No expenses data - select date range please </div>";
									}																																 
								?>
							]
						}]
					});
			chart1.render();
			chart2.render();
		}
	</script>
	<script>
	// Data Picker Initialization
	$('.datepicker').pickadate();
	</script>
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
				
				<div class="raw">
					
					<form>
						<div class="balance_container">
							
							<h4 style="margin-bottom: 30px; border-bottom-color: #17a2b8; padding: 5px 0;">Balance summary</h4>
							
							<!-- Button trigger modal -->
							<button type="button" class="btn btn-info mb-2" data-toggle="modal" data-target="#dates_selection">
							Select date range for summary
							</button>					
							<?php
								if(isset($_SESSION['date_error_message'])){
										echo $_SESSION['date_error_message'];
										unset($_SESSION['date_error_message']);
								}
								if($dates_selection == true){															
									foreach ($incomes_data as $row){
										$sum_of_incomes = $sum_of_incomes + floatval ($row['sum_of_incomes']);
									}
									foreach ($expenses_data as $row){
										$sum_of_expenses = $sum_of_expenses + floatval ($row['sum_of_expenses']);
									}
									$total_balance = $sum_of_incomes - $sum_of_expenses;
									
								} else {
									$total_balance = 0;
									echo '<div class="line"></div>';
								}
								
								if(($total_balance == 0)&&($dates_selection == true)){
									echo "<div class='balance_positive'>Your incomes are equal to your expenses in selected time frame.</div>";
								}
								if($total_balance > 0){
									echo "<div class='balance_positive'>You have generated {$total_balance} euro of savings in selected time frame.</br> Well done !</div>";
								}
								if($total_balance < 0){
									echo "<div class='balance_negative'>Your balance is {$total_balance} euro.</br> Try to watch your finances closely.</div>";
								}
							?>
							<div class="incomes_balance">
							
								<h3>Incomes</h3>
								
								<div id="incomes_column1">
									<div class="first_line bg-info"> Category </div>
										<?php													
											if($dates_selection == true){															
													foreach ($incomes_data as $row){
														echo "<div class='line'>{$row['name']}</div>";
													}	
											} else echo '<div class="line"></div>';
										?>
									<div class="last_line bg-info"> Total </div>
								</div>
							
								<div id="incomes_column2">
									<div class="first_line bg-info"> Amount </div>
										<?php
											if($dates_selection == true){
												foreach ($incomes_data as $row){
													echo "<div class='line'>{$row['sum_of_incomes']}</div>";}
											} else echo '<div class="line"></div>';
										?>
									<div class="last_line bg-info"> <?= ($dates_selection == true)? $sum_of_incomes : 0 ?> </div>
								</div>	
									
							</div>
							
							<div class="incomes_balance_summary">
								<?php
									if($dates_selection == true){
										echo "<div><script src='https://canvasjs.com/assets/script/canvasjs.min.js'></script></div>";
										echo "<div id='incomes_pie_chart'></div>";
											if ($sum_of_incomes == 0) {echo "<div style ='font-size: 15px; margin: 15px; text-align: center;'> The selected date range did not show any incomes </div>";}
									} else {
										echo $_SESSION['no_incomes_message'];
										unset($_SESSION['no_incomes_message']);
									}
								?>																											
							</div>
							
							<div class="expenses_balance">
								<h3>Expenses</h3>
								
								<div id="expenses_column1">
									<div class="first_line bg-info"> Category </div>
										<?php													
											if($dates_selection == true){															
													foreach ($expenses_data as $row){
														echo "<div class='line'>{$row['name']}</div>";
													}	
											} else echo '<div class="line"></div>';
										?>
									<div class="last_line bg-info"> Total </div>
								</div>
							
								<div id="expenses_column2">
									<div class="first_line bg-info"> Amount </div>
										<?php
											if($dates_selection == true){
												foreach ($expenses_data as $row){
													echo "<div class='line'>{$row['sum_of_expenses']}</div>";}
											} else echo '<div class="line"></div>';
										?>
									<div class="last_line bg-info"> <?= ($dates_selection == true)? $sum_of_expenses : 0 ?> </div>
								</div>
												
							</div>
							
							<div class="expenses_balance_summary">
								<?php
									if($dates_selection == true){
										echo "<div><script src='https://canvasjs.com/assets/script/canvasjs.min.js'></script></div>";
										echo "<div id='expenses_pie_chart'></div>";
										if ($sum_of_expenses == 0) {echo "<div style ='font-size: 15px; margin: 15px; text-align: center;'> The selected date range did not show any expenses </div>";}
									} else {
										echo $_SESSION['no_expenses_message'];
										unset($_SESSION['no_expenses_message']);
									}
								?>
							</div>
							
						</div>	
					</form>
				
				</div>
			</div>
		</section>
		
		<!-- Modal -->
		<div class="modal fade" id="dates_selection" tabindex="-1" role="dialog">
			<div class="modal-dialog modal-dialog-centered" role="document">
				
				<div class="modal-content">
					<form method="post">
				
						<div class="modal-header">
							<h5 class="modal-title" id="date_from">Date from</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
						</div>
						
							<div class="modal-body">
								<input type="date" name="start_date" id="date_from_picker" class="form-control datepicker" required>
							</div>
							
						<div class="modal-header">
							<h5 class="modal-title" id="date_to">Date to</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"> </button>
						</div>
						
							<div class="modal-body">
								<input type="date" name="end_date" id="date_to_picker" class="form-control datepicker" required>
							</div>
					  
					  
						<div class="modal-footer">

							<button type="submit" name="dates_entry" class="btn btn-info">Save</button>
							<button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
							
						</div>
					
					</form>
				
				</div>
			
			</div>
		</div>
		<!-- Modal -->
	</main>
	
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="js/bootstrap.min.js"></script>		
	
</body>
</html>													