<?php
session_start();

	if (!isset($_SESSION['logged_user_id'])) {

		if (isset ($_POST['login_email'])){
			
			$login_email = filter_input(INPUT_POST, 'login_email');
			$login_password = filter_input(INPUT_POST, 'login_password');
			 
			require_once 'db_connection.php';
			
			$query = $database -> prepare ('SELECT id, password FROM users WHERE email = :email');
			$query -> bindValue(':email', $login_email, PDO::PARAM_STR);
			$query -> execute();
			
			$user = $query -> fetch();
			
			if ($user && password_verify($login_password, $user['password'])) {
				
				$_SESSION['logged_user_id'] = $user['id'];
				header('Location: main_menu.php');
				
			} else { 
					if(!$user){
						
						$_SESSION['error_message'] = '<div style ="color: red; font-size: 15px;"> Incorrect email address </br> Please try again </div>';
						header('Location: index.php');
						
					} else {
						
						$_SESSION['error_message'] = '<div style ="color: red; font-size: 15px;"> Incorrect password </br> Please try again </div>';
						header('Location: index.php');
					}
				}
			
		} else {
			header('Location: index.php');
			exit();
		}
	}
